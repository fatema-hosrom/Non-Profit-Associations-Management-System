<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('activity_volunteer_requirements', function (Blueprint $table) {
            // نحذف العمود القديم أولاً
            $table->dropColumn('gender_requirement');
        });

        Schema::table('activity_volunteer_requirements', function (Blueprint $table) {
            // نضيف العمود الجديد كـ enum
            $table->enum('gender_requirement', ['male', 'female', 'both'])->default('both');
        });
    }

    public function down(): void
    {
        Schema::table('activity_volunteer_requirements', function (Blueprint $table) {
            $table->dropColumn('gender_requirement');
        });

        Schema::table('activity_volunteer_requirements', function (Blueprint $table) {
            $table->string('gender_requirement')->nullable();
        });
    }
};
