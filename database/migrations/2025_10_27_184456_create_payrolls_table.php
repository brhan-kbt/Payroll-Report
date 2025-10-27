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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->decimal('taxable_transport', 15, 2)->default(0);
            $table->decimal('overtime', 15, 2)->default(0);
            $table->decimal('department_allowance', 15, 2)->default(0);
            $table->decimal('position_allowance', 15, 2)->default(0);
            $table->decimal('gross_earning', 15, 2)->default(0);
            $table->decimal('pension_school', 15, 2)->default(0);
            $table->decimal('income_tax', 15, 2)->default(0);
            $table->decimal('staff_pension', 15, 2)->default(0);
            $table->decimal('advance_loan', 15, 2)->default(0);
            $table->decimal('net_pay', 15, 2)->default(0);
            $table->decimal('labor_association', 15, 2)->default(0);
            $table->decimal('social_committee', 15, 2)->default(0);
            $table->decimal('allowance', 15, 2)->default(0);

            $table->date('payroll_month');
            $table->string('payroll_status')->nullable();
            $table->date('payroll_date');
            $table->timestamps();

            $table->unique(['employee_id', 'payroll_month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
