<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Produk::create([
            "product_name" => "Kopi Torabika Duo",
            "product_description" => "Kopi nikmat nyaman di lambung",
            "product_price_capital" => 1000,
            "product_price_sell" => 1500
        ]);
    }
}
