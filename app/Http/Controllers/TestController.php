<?php

namespace App\Http\Controllers;

use App\Helpers\CommissionCalculation;
use App\Helpers\Transaction;
use App\Imports\PayseraImport;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class TestController extends Controller
{

    public function __invoke(Request $request)
    {

        $calculator = new CommissionCalculation();

        if($request->test) {
            $calculator->setCustomCurrency([
                'JPY' => 129.53,
                'USD' => 1.1497,
            ]);
        }

        $filepath = base_path('test.csv');

        if ($file = fopen($filepath, "r")) {
            while (!feof($file)) {
                $input = fgetcsv($file);
                if (!$input) continue;
                $calculator->addTransaction(new Transaction($input));
            }
            fclose($file);
        } else {
            throw new Exception("Cannot open file $filepath");
        }

        return $calculator->calculate();
        
    }
}
