<?php

namespace App\Helpers;

// use App\Services\CommissionFee\Enums\OperationType;
use Carbon\Carbon;

class Transaction
{
    /** @var Carbon $date Operation date (Y-m-d) */
    public Carbon $date;

    public $userID;

    /** @var OperationType $type Operation type, one from those specified in enum */
    public $method;

    public $type;

    /** @var string $currency Operation's currency, one from those specified in enum */
    public string $currency;

    /** @var float $amount Operation amount, rounded up to decimal places */
    public float $amount;

    // public float $commissionFee = 0;

    public function __construct($csvRow)
    {
        $this->date     = Carbon::parse($csvRow[0]);
        $this->userID   = $csvRow[1];
        $this->type     = $csvRow[2];
        $this->method   = $csvRow[3];
        $this->amount   = $csvRow[4];
        $this->currency = $csvRow[5];
        // $this->commissionFee = $csvRow[4];
    }
}
