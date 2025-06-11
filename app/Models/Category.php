<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'categories';


    public function items()
    {
        return $this->hasMany(Item::class, 'kategori_id');
    }
}
