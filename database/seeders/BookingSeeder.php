<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Car;

class BookingSeeder extends Seeder
{
    public function run()
    {
        Booking::create([
            'user_id' => 2,
            'car_id' => 1,
            'phone' => '08123456789',
            'date' => now()->addDays(3)->toDateString(),
            'time' => '10:00:00',
            'type' => 'test_drive',
            'status' => 'pending',
        ]);
    }
}
