<?php

namespace App\Imports;

use App\Helpers\Transaction;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class PayseraImport implements ToCollection, WithCustomCsvSettings
{
    
    use Importable;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        return new Transaction($collection);
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ","
        ];
    }


}
