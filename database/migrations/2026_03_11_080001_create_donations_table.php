<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained('donors')->onDelete('cascade');
            $table->foreignId('activity_id')->constrained('organization_activities')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->enum('donation_type', ['cash','online','check','other']);
            $table->dateTime('date');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('managers')->onDelete('cascade');
            $table->boolean('is_deleted')->default(false);
            $table->dateTime('deleted_at')->nullable();
            $table->foreignId('deleted_by')->nullable()->constrained('managers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('donations');
    }
};