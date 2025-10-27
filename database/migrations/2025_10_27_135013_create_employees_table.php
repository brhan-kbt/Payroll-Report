<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->string('name'); // Full name
            $table->string('employee_id')->unique(); // Unique employee identifier
            $table->string('department')->nullable(); // Department name
            $table->string('position')->nullable(); // Job title/position
            $table->string('email')->nullable(); // Optional email
            $table->string('phone')->nullable(); // Optional phone number
            $table->date('date_of_joining')->nullable(); // Date the employee joined
            $table->date('date_of_birth')->nullable(); // DOB
            $table->string('gender')->nullable(); // Gender (optional)
            $table->string('address')->nullable(); // Address
            $table->boolean('is_active')->default(true); // Active/Inactive employee
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
