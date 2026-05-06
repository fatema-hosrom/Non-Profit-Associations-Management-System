<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('volunteers', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 20);
            $table->string('gender');
            $table->integer('age');
            $table->string('nationality');
            $table->string('address');

            $table->text('skills')->nullable();
            $table->text('experience')->nullable();
            $table->string('education_level')->nullable();
            $table->string('availability')->nullable();
            $table->string('preferred_roles')->nullable();
            $table->string('languages')->nullable();
            $table->string('emergency_contact')->nullable();

            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteers');
    }
};
