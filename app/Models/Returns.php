<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $guarded = ['id'];
     

        public function loan()
    {
        return $this->belongsTo(Loans::class, 'loan_id');
    }

    // Relasi Loan ke User (peminjam)
    public function user()
    {
        return $this->loan->user();
    }
    
    
}
