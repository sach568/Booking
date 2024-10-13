<?php

// app/Http/Controllers/BookingController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookingController extends Controller
{
    // Review booking and apply discount
    public function reviewBooking(Request $request)
    {
        $bookingData = $request->all(); // Assuming the booking data is coming from the request

        // Call discount API to get applied discounts
        $discountResponse = Http::post('http://127.0.0.1:8000/api/apply-discount', [
            'booking' => ['total' => $bookingData['total']],
            'familyDiscount' => $bookingData['familyDiscount'],
            'recurringDiscount' => $bookingData['recurringDiscount'],
        ]);

        // Assuming we get the discount and total amount back from the discount API
        $discountData = $discountResponse->json();

        // Return review page with applied discount and new total
        return view('booking.review', [
            'originalTotal' => $bookingData['total'],
            'discount' => $discountData['discount'],
            'totalAfterDiscount' => $discountData['total_after_discount'],
        ]);
    }
}
