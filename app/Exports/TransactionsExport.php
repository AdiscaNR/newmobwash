<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;

class TransactionsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Transaction::all();
    }

    public function headings(): array
    {
        return [
            'id',
            'client_id',
            'date',
            'check_in',
            'check_out',
            'created_user',
            // Tambahkan kolom lain yang sesuai dengan tabel transaksi
        ];
    }
}
