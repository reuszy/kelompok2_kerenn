<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'item_id',
        'jumlah',
        'kondisi_saat_dipinjam',
    ];

    // Relasi LoanItem ke Loan (satu)
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    // Relasi LoanItem ke Item (satu)
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
