<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('organization_activities', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->enum('activity_type', ['donation', 'volunteer', 'both']);
            $table->string('location', 255)->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('image', 255)->nullable();

            $table->enum('status', ['active', 'closed', 'draft'])->nullable();
            $table->boolean('is_published')->nullable();

            $table->unsignedBigInteger('manager_id')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();

            $table->timestamps();

            $table->foreign('manager_id')->references('id')->on('managers')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('managers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_activities');
    }
};
