<?php
// database/migrations/2025_12_05_000001_create_activity_donation_settings_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activity_donation_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_id');
            $table->decimal('target_amount', 12, 2)->nullable();
            $table->decimal('collected_amount', 12, 2)->default(0);
            $table->enum('donation_status', ['open', 'completed', 'closed'])->default('open');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('activity_id')
                ->references('id')->on('organization_activities')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_donation_settings');
    }
};
