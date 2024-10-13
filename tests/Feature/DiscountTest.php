<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Discount;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\FamilyMember;
use App\Models\User;

class DiscountTest extends TestCase
{

    /** @test */
    public function it_applies_discount_correctly()
    {
        // Create a unique user with a random email
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test-' . uniqid() . '@example.com', // Unique email
            'password' => bcrypt('password'),
        ]);

        // Create a schedule
        $schedule = Schedule::create([
            'name' => 'Test Schedule',
            'description' => 'Description of schedule',
            'price' => 200
        ]);

        // Create a booking with initial price
        $booking = Booking::create([
            'user_id' => $user->id,
            'schedule_id' => $schedule->id,
            'price' => 200
        ]);

        // Create a percentage-based discount
        Discount::create([
            'type' => 'percentage',
            'value' => 10, // 10% discount
        ]);

        // Send API request to apply discount
        $response = $this->postJson('/api/apply-discount', [
            'booking_id' => $booking->id,
        ]);

        // Check if the final price is as expected after discount
        $response->assertStatus(200)
                 ->assertJson(['final_price' => '180.00']); // Price after 10% discount
    }

    /** @test */
    public function it_applies_family_member_discount()
    {
        // Create a unique user with a random email
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test-' . uniqid() . '@example.com', // Unique email
            'password' => bcrypt('password'),
        ]);

        // Create a family member
        $familyMember = FamilyMember::create([
            'user_id' => $user->id,
            'name' => 'Family Member'
        ]);

        // Create a schedule for the family member to book
        $schedule = Schedule::create([
            'name' => 'Schedule for Family Member',
            'description' => 'Schedule description',
            'price' => 150
        ]);

        // Create a booking for the family member
        Booking::create([
            'user_id' => $familyMember->user_id,
            'schedule_id' => $schedule->id,
            'price' => 150
        ]);

        // Create a family discount
        Discount::create([
            'type' => 'family',
            'value' => 20 // $20 discount for family member
        ]);

        // Send API request
        $response = $this->postJson('/api/family-member-discount', [
            'user_id' => $user->id,
            'schedule_id' => $schedule->id
        ]);

        // Check if discount is applied correctly
        $response->assertStatus(200)
                 ->assertJson(['discount_applied' => true, 'final_price' => '130.00']); // Final price after $20 discount
    }

    /** @test */
    public function it_applies_recurring_discount()
    {
        // Create a unique user with a random email
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test-' . uniqid() . '@example.com', // Unique email
            'password' => bcrypt('password'),
        ]);

        // Create a schedule
        $schedule = Schedule::create([
            'name' => 'Recurring Schedule',
            'description' => 'Schedule description',
            'price' => 200
        ]);

        // Create a booking
        Booking::create([
            'user_id' => $user->id,
            'schedule_id' => $schedule->id,
            'price' => 200
        ]);

        // Create a recurring discount
        Discount::create([
            'type' => 'recurring',
            'value' => 50 // $50 discount for recurring booking
        ]);

        // Send API request
        $response = $this->postJson('/api/recurring-discount', [
            'user_id' => $user->id,
            'schedule_id' => $schedule->id
        ]);

        // Check if recurring discount is applied correctly
        $response->assertStatus(200)
                 ->assertJson(['discount_applied' => true, 'final_price' => '150.00']); // Final price after recurring discount
    }
}
