<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['total'];

    function detail()
    {
        $this->hasMany(DetailTransaksi::class, 'transaksi_id', 'id');
    }
}
