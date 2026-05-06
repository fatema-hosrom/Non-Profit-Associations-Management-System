<?php
// database/migrations/2025_12_05_000002_create_activity_volunteer_requirements_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activity_volunteer_requirements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_id');

            $table->integer('required_volunteers')->nullable();
            $table->integer('volunteers_count')->default(0);
            $table->enum('volunteer_mode', ['manual', 'auto'])->default('manual');
            $table->integer('min_age')->nullable();
            $table->string('gender_requirement')->nullable();
            $table->string('skills_required')->nullable();
            $table->integer('min_hours')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('activity_id')
                ->references('id')->on('organization_activities')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_volunteer_requirements');
    }
};
