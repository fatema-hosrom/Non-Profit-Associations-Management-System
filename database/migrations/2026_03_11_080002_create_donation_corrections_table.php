<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('donation_corrections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_id')->constrained('donations')->onDelete('cascade');
            $table->text('reason');
            $table->decimal('corrected_amount', 15, 2);
            $table->dateTime('correction_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('donation_corrections');
    }
};