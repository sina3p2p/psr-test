<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class CommissionCalculation 
{
    public $private_commission  = 0.03;
    public $business_commission = 0.03;
    public    $currencies_rates = [];
    protected $weekly_trx       = [];
    protected $weekly_user_trx  = [];
    protected $commissions      = [];

    const CURRENCIES_DECIMAL_PLACES = [
        'EUR' => 2,
        'JPY' => 0,
        'USD' => 2
    ];

    public function __construct()
    {
        $this->currencies_rates = data_get(
            Http::get("https://developers.paysera.com/tasks/api/currency-exchange-rates")->json(), 
            'rates'
        );
        return $this;
    }

    public function addTransaction(Transaction $trx)
    {
        $start = $trx->date->startOfWeek(Carbon::MONDAY)->format('d-m-Y');
        $end   = $trx->date->endOfWeek(Carbon::SUNDAY)->format('d-m-Y');
        $this->weekly_trx["{$start} - {$end}"][] = $trx;
        return $this;
    }

    public function calculate()
    {
        foreach($this->weekly_trx as $week_trx)
        {

            $this->weekly_user_trx = [];

            foreach($week_trx as $trx)
            {
                if($trx->method == "withdraw")
                {
                    $this->commissions[] = $this->handleWithdraw($trx);
                }else{
                    $this->commissions[] =  $this->calculateFinalAmount($this->getTransactionAmount($trx), 0.0003);
                }
            }
        }

        return $this->commissions;
    }


    private function handleWithdraw(Transaction $trx)
    {
        $amount = $this->getTransactionAmount($trx);
        $price  = $amount['amount'];

        if($trx->type == "private")
        {   
            $commission = 0;
            $total_withdraw_user_per_week = data_get($this->weekly_user_trx, "{$trx->userID}.total", 0);
            $count_withdraw_user_per_week = data_get($this->weekly_user_trx, "{$trx->userID}.count", 0);

            if
            (
                $count_withdraw_user_per_week < 3 &&  
                $total_withdraw_user_per_week < 1000
            )
            {
                if($total_withdraw_user_per_week + $price < 1000)
                {
                    $commission = 0;
                }
                else
                {
                    $amount['amount'] = $total_withdraw_user_per_week +  $price - 1000;
                    $commission = $this->calculateFinalAmount($amount , 0.003);
                }
            }
            else
            {
                $commission = $this->calculateFinalAmount($amount, 0.003);
            }

            $this->weekly_user_trx[$trx->userID] = [
                'total' => $total_withdraw_user_per_week + $price,
                'count' => $count_withdraw_user_per_week + 1
            ];

            return $commission;

        }

        return $this->calculateFinalAmount($amount, 0.005);
    }

    public function getTransactionsWeekly()
    {
        return $this->weekly_trx;
    }

    private function getTransactionAmount(Transaction $trx)
    {
        $currency = $trx->currency;
        $rate = $this->currencies_rates[$currency];
        $amount = $trx->amount / $rate;

        return [
            'amount'   => $amount,
            'rate'     => $rate,
            'currency' => $currency
        ];
    }

    private function getRatedPrice($amount)
    {
        return $amount['amount'] * $amount['rate'];
    }

    private function calculateFinalAmount($amount, $commission = 0)
    {
        return $this->round_up($this->getRatedPrice($amount) * $commission, data_get(self::CURRENCIES_DECIMAL_PLACES, $amount['currency'], 0));
    }

    private function round_up($number, $precision = 0)
    {
        $fig = pow(10, $precision);
        return (ceil($number * $fig) / $fig);
    }

    public function setCustomCurrency($array)
    {
        $this->currencies_rates = array_merge($this->currencies_rates, $array);
    }

}