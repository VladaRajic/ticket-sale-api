<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mainVenue = DB::table('venues')->where('name', 'Main Venue')->first();
        $smallVenue = DB::table('venues')->where('name', 'Small Venue')->first();

        DB::table('events')->insert([
            [
                'venue_id' => $mainVenue->id,
                'name' => 'Concert',
                'available_tickets' => 150,
                'ticket_sales_end_date' => Carbon::now()->addDays(10),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'venue_id' => $smallVenue->id,
                'name' => 'Lecture',
                'available_tickets' => 50,
                'ticket_sales_end_date' => Carbon::yesterday(),
                'created_at' => Carbon::yesterday(),
                'updated_at' => Carbon::yesterday()
            ]
        ]);
    }
}
