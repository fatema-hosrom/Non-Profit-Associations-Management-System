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
        Schema::create('activity_volunteer_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_id');
            $table->unsignedBigInteger('volunteer_id');
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('request_date')->useCurrent();
            $table->timestamp('decision_date')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->timestamp('removed_at')->nullable();
            $table->unsignedBigInteger('removed_by')->nullable();
            $table->string('removal_reason')->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('activity_id')->references('id')->on('organization_activities')->onDelete('cascade');
            $table->foreign('volunteer_id')->references('id')->on('volunteers')->onDelete('cascade');
            $table->foreign('removed_by')->references('id')->on('managers')->onDelete('set null');

            // Indexes
            $table->index(['activity_id', 'status']);
            $table->index(['volunteer_id', 'status']);
            $table->index('request_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_volunteer_assignments');
    }
};
