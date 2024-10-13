<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Discount;
use App\Models\FamilyMember;

class DiscountController extends Controller
{
    // Apply Discount based on booking
    public function applyDiscount(Request $request)
    {
        $booking = Booking::find($request->input('booking_id'));

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        $finalPrice = (float)$booking->price; // Start with the original booking price

        $discount = Discount::where('type', 'percentage')->first(); // Assume applying a percentage discount

        if ($discount) {
            $finalPrice = $discount->applyDiscount($finalPrice); // Apply discount
        }

        return response()->json(['final_price' => number_format($finalPrice, 2)]); // Return formatted final price
    }

    // Apply family member discount
    public function familyMemberDiscount(Request $request)
    {
        $userId = $request->input('user_id');
        $scheduleId = $request->input('schedule_id');

        // Check if the user has any family members
        $familyMembers = FamilyMember::where('user_id', $userId)->get();

        if ($familyMembers->isEmpty()) {
            return response()->json(['discount_applied' => false]);
        }

        foreach ($familyMembers as $familyMember) {
            // Check if this family member has booked the same schedule
            if (Booking::where('user_id', $familyMember->user_id)->where('schedule_id', $scheduleId)->exists()) {
                $discount = Discount::where('type', 'family')->first();
                $price = Booking::where('schedule_id', $scheduleId)->first()->price;

                if ($discount) { // Check if the discount was found
                    $finalPrice = $price - $discount->value; // Apply family member discount

                    return response()->json([
                        'discount_applied' => true,
                        'final_price' => number_format($finalPrice, 2)
                    ]);
                }
            }
        }

        return response()->json(['discount_applied' => false]);
    }

    // Apply recurring discount
    public function recurringDiscount(Request $request)
    {
        $userId = $request->input('user_id');
        $scheduleId = $request->input('schedule_id');

        // Check if the user has a booking for the same schedule
        $booking = Booking::where('user_id', $userId)->where('schedule_id', $scheduleId)->first();

        if ($booking) {
            $discount = Discount::where('type', 'recurring')->first();

            if ($discount) { // Check if the discount was found
                $finalPrice = $booking->price - $discount->value; // Apply recurring discount

                return response()->json([
                    'discount_applied' => true,
                    'final_price' => number_format($finalPrice, 2) // Return formatted final price
                ]);
            }
        }

        return response()->json(['discount_applied' => false]);
    }
}
