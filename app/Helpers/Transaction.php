<?php

namespace App\Helpers;

use Carbon\Carbon;

class Transaction
{
    // date of Transaction (Y-m-d)
    public Carbon $date;

    // Transaction's User
    public $userID;

    // Transaction's method, withdraw or deposit
    public $method;

    // Transaction's type: private or business
    public $type;

    // Transaction's currency
    public string $currency;

    // Transaction's amount
    public float $amount;

    public float $commissionFee = 0;

    public function __construct($csvRow)
    {
        $this->date     = Carbon::parse($csvRow[0]);
        $this->userID   = $csvRow[1];
        $this->type     = $csvRow[2];
        $this->method   = $csvRow[3];
        $this->amount   = $csvRow[4];
        $this->currency = $csvRow[5];
    }

}
