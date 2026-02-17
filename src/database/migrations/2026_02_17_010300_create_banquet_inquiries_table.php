<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banquet_inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->integer('party_size');
            $table->date('preferred_date');
            $table->time('preferred_time')->nullable();
            $table->decimal('budget_per_person', 10, 0)->nullable();
            $table->text('course_preference')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('pending');
            $table->integer('deposit_amount')->nullable();
            $table->string('stripe_session_id')->nullable();
            $table->string('stripe_payment_id')->nullable();
            $table->string('deposit_status')->nullable();
            $table->timestamp('deposit_paid_at')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banquet_inquiries');
    }
};
