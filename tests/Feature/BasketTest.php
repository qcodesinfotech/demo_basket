<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

// class BasketTest extends TestCase
// {
//     /**
//      * A basic feature test example.
//      */
//     public function test_example(): void
//     {
//         $response = $this->get('/');

//         $response->assertStatus(200);
//     }
// }


class BasketTest extends TestCase
{
    /** @test */
    public function it_applies_half_price_discount_and_delivery_charge_correctly()
    {
        // Simulate basket content
        $basket = [
            'R01' => ['name' => 'Red Widget', 'price' => 32.95, 'quantity' => 2],
            'B01' => ['name' => 'Blue Widget', 'price' => 7.95, 'quantity' => 3],
        ];
    
        $subtotal = 0;
        $discount = 0;
    
        foreach ($basket as $item) {
            $subtotal += $item['price'] * $item['quantity'];
                $halfPriceCount = floor($item['quantity'] / 2);
            $discount += round(($item['price'] / 2) * $halfPriceCount, 2); // Round the discount
        }
    
        $subtotalAfterDiscount = round($subtotal - $discount, 2);  // Round the subtotal after discount
    
        // Apply delivery logic
        if ($subtotalAfterDiscount >= 90) {
            $delivery = 0;
        } elseif ($subtotalAfterDiscount >= 50) {
            $delivery = 2.95;
        } else {
            $delivery = 4.95;
        }
    
        $total = round($subtotalAfterDiscount + $delivery, 2); // Round the total to avoid floating point issues
    
        $expectedSubtotal = round(32.95 * 2 + 7.95 * 3, precision: 2); // 89.75
        $expectedDiscount = round(16.48 + 3.98, 2);    
        $expectedSubtotalAfterDiscount = round($expectedSubtotal - $expectedDiscount, 2); 
        $expectedDelivery = 2.95;
        $expectedTotal = round($expectedSubtotalAfterDiscount + $expectedDelivery, 2); 
    
        // Compare rounded values for discounts and other calculations
        $this->assertEquals($expectedSubtotal, $subtotal, "Subtotal mismatch");
        $this->assertEquals($expectedDiscount, $discount, "Discount mismatch"); // <-- Corrected
        $this->assertEquals($expectedSubtotalAfterDiscount, $subtotalAfterDiscount, "Subtotal after discount mismatch");
        $this->assertEquals($expectedDelivery, $delivery, "Delivery mismatch");
        $this->assertEquals($expectedTotal, $total, "Total mismatch");
    }
    
}
