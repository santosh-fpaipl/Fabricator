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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('sid')->unique(); // FB-PO-0000
            $table->string('doc_id')->nullable(); // DG-PO-0000
            $table->string('doc_type')->nullable(); // BrandApp-PurchaseOrder
            $table->date('doc_date')->nullable(); // Respective Document Date

            // Brand - As per DG-PO-0000 
            $table->string('product_sid')->nullable(); // Article code (ex: #234589)
            $table->integer('order_qty')->default(0); // Qty of (ex: 500) pcs

            $table->integer('quantity')->default(0); // total of quantities (ex: 700 mtr)
            $table->json('quantities'); // grid of option & range with qty

            $table->integer('supplier_id')->default(1); // Default: Monaal Creation
            $table->integer('brand_id')->default(1); // Default: Desigal (Metro Fashion)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders_tables');
    }
};
