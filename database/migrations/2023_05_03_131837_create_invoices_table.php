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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->string('product');

            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->decimal('amount_collection', 8, 2)->nullable();
            $table->decimal('amount_commission',8 , 2);
            $table->decimal('discount', 8, 2);
            $table->string('rate_vat');
            $table->decimal('value_vat', 8, 2);//يعني لازم 8 خانات على يسار الفاصلة وخانتين فقط على يمين الفاصلة
            $table->decimal('total', 8, 2);
            $table->string('status', 50);
            $table->integer('value_status');
            $table->text('note')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
