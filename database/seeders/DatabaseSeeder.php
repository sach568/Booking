<?php

// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\FamilyMember;
use App\Models\Schedule;
use App\Models\Booking;
use App\Models\Discount;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create a user with ID 1
        $user1 = User::create([
            'name' => 'Test User 1',
            'email' => 'testuser1@example.com',
            'password' => bcrypt('password')
        ]);

        // Create a user with ID 2
        $user2 = User::create([
            'name' => 'Test User 2',
            'email' => 'testuser2@example.com',
            'password' => bcrypt('password')
        ]);

        // Create family members
        FamilyMember::create([
            'user_id' => $user2->id,
            'name' => 'Family Member 1'
        ]);

        // Create schedules
        $schedule1 = Schedule::create([
            'name' => 'Test Schedule 1',
            'description' => 'A schedule for testing',
            'price' => 100
        ]);

        $schedule2 = Schedule::create([
            'name' => 'Test Schedule 2',
            'description' => 'Another schedule for testing',
            'price' => 200
        ]);

        // Create bookings for user 2 (with family member)
        Booking::create([
            'user_id' => $user2->id, // Booked by user 2
            'schedule_id' => $schedule1->id, // First booking for user 2
            'price' => 100
        ]);

        Booking::create([
            'user_id' => $user2->id, // Booked by user 2
            'schedule_id' => $schedule2->id, // Second booking for recurring discount
            'price' => 200
        ]);

        // Create discounts
        Discount::create([
            'type' => 'family',
            'value' => 20, // Example value for family discount
            'max_uses' => 10,
            'max_amount' => 50
        ]);

        Discount::create([
            'type' => 'recurring',
            'value' => 50, // Example value for recurring discount
            'max_uses' => 10,
            'max_amount' => 50
        ]);
    }
}
