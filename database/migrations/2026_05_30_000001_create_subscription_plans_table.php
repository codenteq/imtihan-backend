<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('referenceCode')->unique();
            $table->string('productReferenceCode');
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->string('currencyCode')->default('TRY');
            $table->string('paymentInterval');
            $table->integer('paymentIntervalCount')->default(1);
            $table->string('planPaymentType')->default('RECURRING');
            $table->integer('recurrenceCount')->nullable();
            $table->integer('trialPeriodDays')->nullable()->default(0);
            $table->string('status')->default('ACTIVE');
            $table->timestamps();

            $table->foreign('productReferenceCode')->references('referenceCode')->on('subscription_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
