<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('orders')) return;
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method',50)->nullable()->after('total');
            }
            if (!Schema::hasColumn('orders', 'payment_intent_id')) {
                $table->string('payment_intent_id',255)->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status',50)->default('pending')->after('payment_intent_id');
            }
            if (!Schema::hasColumn('orders', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('orders', 'tracking_number')) {
                $table->string('tracking_number',100)->nullable()->after('admin_notes');
            }
            if (!Schema::hasColumn('orders', 'shipping_notes')) {
                $table->text('shipping_notes')->nullable()->after('tracking_number');
            }
            if (!Schema::hasColumn('orders', 'delivery_notes')) {
                $table->text('delivery_notes')->nullable()->after('shipping_notes');
            }
            if (!Schema::hasColumn('orders', 'qr_code_data')) {
                $table->text('qr_code_data')->nullable()->after('delivery_notes');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('orders')) return;
        Schema::table('orders', function (Blueprint $table) {
            foreach (['qr_code_data','delivery_notes','shipping_notes','tracking_number','admin_notes','payment_status','payment_intent_id','payment_method'] as $col) {
                if (Schema::hasColumn('orders', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
