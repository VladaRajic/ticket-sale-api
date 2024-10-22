<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('venues')->insert([
            [
                'name' => 'Main Venue',
                'capacity' => 150,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Small Venue',
                'capacity' => 50,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
