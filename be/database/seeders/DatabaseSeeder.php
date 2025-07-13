<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed dữ liệu phụ thuộc trước
        $this->call([
            ProductAttributeSeeder::class,
            ProductsTableSeeder::class,
            ProductValueSeeder::class,
            ProductVariantSeeder::class,
            ProductVariantValueSeeder::class,
            $this->call(CourtSeeder::class),
        ]);

        // Seed Order sau khi có user + product
        $this->call([
            OrderSeeder::class,
            // Nếu có OrderDetailSeeder, thêm ở đây
            // OrderDetailSeeder::class,
        ]);
    }
}
