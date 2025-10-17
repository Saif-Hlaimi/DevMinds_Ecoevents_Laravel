<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users table
            $table->string('customer_name', 255); // Customer's full name
            $table->string('customer_email', 255); // Customer's email address
            $table->string('customer_phone', 50)->nullable(); // Customer's phone number (optional)
            $table->string('customer_address', 255); // Customer's delivery address
            $table->string('customer_city', 255); // Customer's city
            $table->string('customer_postal_code', 20); // Customer's postal code
            $table->string('payment_method', 50); // Payment method (e.g., cash, card, transfer)
            $table->text('notes')->nullable(); // Optional notes for the order
            $table->decimal('total', 10, 2); // Total amount of the order
            $table->string('payment_intent_id', 255)->nullable(); // Stripe payment intent ID (optional)
            $table->string('payment_status', 50)->default('pending'); // Payment status (e.g., pending, succeeded, unpaid, cancelled)
            $table->text('admin_notes')->nullable(); // Notes for admin use
            $table->string('tracking_number', 100)->nullable(); // Tracking number for shipping
            $table->text('shipping_notes')->nullable(); // Shipping-related notes
            $table->text('delivery_notes')->nullable(); // Delivery-related notes
            $table->text('qr_code_data')->nullable(); // QR code image data or URL
            $table->timestamps(); // Created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}