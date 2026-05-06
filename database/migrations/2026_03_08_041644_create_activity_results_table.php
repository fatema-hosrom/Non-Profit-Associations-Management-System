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
        Schema::create('activity_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_id');
            $table->integer('total_volunteers')->nullable();
            $table->integer('total_hours')->nullable();
            $table->integer('attendance_count')->nullable();
            $table->text('goals_achieved')->nullable();
            $table->text('challenges')->nullable();
            $table->text('notes')->nullable();
            $table->text('images')->nullable(); // روابط الصور
            $table->string('report_file')->nullable(); // ملف التقرير
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('activity_id')->references('id')->on('organization_activities')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('managers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_results');
    }
};
