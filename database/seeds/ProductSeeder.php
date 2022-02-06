<?php

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('products')->delete();
        \DB::table('products')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'PS5',
                'description' => 'The PlayStation 5 (PS5) is a home video game console developed by Sony Interactive Entertainment.',
                'price' => 1200.00,
                'created_at' => now(),
                'updated_at' => now()
            ),
            1 =>  
            array (
                'id' => 2,
                'name' => 'AMD Ryzen™ 7 3700X',
                'description' => 'A beautifully balanced design for serious PC enthusiasts.',
                'price' => 375.00,
                'created_at' => now(),
                'updated_at' => now()
            ),
           2 =>  
            array (
                'id' => 3,
                'name' => '2022 BMW Z4',
                'description' => "The BMW Z4 is a bit of a departure from the German brand's usual fare yet still carries the distinct personality of a BMW.",
                'price' => 49900.00,
                'created_at' => now(),
                'updated_at' => now()
            )
        ));
    }
}
