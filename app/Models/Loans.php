<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loans extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kode_peminjaman',
        'tanggal_peminjaman',
        'tanggal_pengembalian',
        'status',
        'keterangan',
    ];

    // Relasi Loan ke User (peminjam)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi Loan ke LoanItems (banyak)
    public function loanItems()
    {
        return $this->hasMany(LoanItem::class, 'loan_id'); // Tambah 'loan_id' di sini
    }
    
}
