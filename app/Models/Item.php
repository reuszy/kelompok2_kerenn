<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_barang',
        'kode_barang',
        'kategori_id',
        'jumlah',
        'satuan',
        'lokasi',
        'kondisi',
        'deskripsi',
        'foto',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }
}
