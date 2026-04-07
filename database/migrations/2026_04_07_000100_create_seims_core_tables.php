<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('supply_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('symbol', 30);
            $table->timestamps();
        });

        Schema::create('supply_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supply_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('document_number')->unique();
            $table->text('description')->nullable();
            $table->decimal('current_quantity', 12, 2)->default(0);
            $table->decimal('minimum_quantity', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('supply_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supply_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('transaction_type', ['in', 'out']);
            $table->decimal('quantity', 12, 2);
            $table->decimal('balance_before', 12, 2);
            $table->decimal('balance_after', 12, 2);
            $table->string('document_number');
            $table->date('reference_date');
            $table->string('recipient_name')->nullable();
            $table->string('person_in_charge')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('equipment_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('equipment_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('equipment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('equipment_type_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('document_number')->unique();
            $table->string('control_number')->unique();
            $table->string('person_in_charge');
            $table->enum('status', ['good', 'maintenance_pre', 'maintenance_post', 'transferred', 'condemned'])->default('good');
            $table->date('last_status_date')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('equipment_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['good', 'maintenance_pre', 'maintenance_post', 'transferred', 'condemned']);
            $table->date('status_date');
            $table->string('document_number');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('equipment_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('document_type');
            $table->string('document_number')->nullable();
            $table->string('original_name');
            $table->string('stored_path');
            $table->string('mime_type')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_documents');
        Schema::dropIfExists('equipment_status_logs');
        Schema::dropIfExists('equipment_items');
        Schema::dropIfExists('equipment_types');
        Schema::dropIfExists('equipment_categories');
        Schema::dropIfExists('supply_transactions');
        Schema::dropIfExists('supply_items');
        Schema::dropIfExists('units');
        Schema::dropIfExists('supply_categories');
        Schema::dropIfExists('warehouses');
    }
};
