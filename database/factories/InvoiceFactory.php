<?php

namespace Database\Factories;

use App\Models\PaymentCoupon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'price' => $this->faker->randomNumber,
            'start_date' => $this->faker->date,
            'end_date' => $this->faker->date,
            'discount' => $this->faker->randomNumber,
            'total' => $this->faker->randomNumber,
            'paid_at' => $this->faker->dateTime,
            'payment_coupon_id' => PaymentCoupon::factory(),
        ];
    }
}
