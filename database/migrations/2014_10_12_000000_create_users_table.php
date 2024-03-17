<?php

use App\Enums\EducationLevel;
use App\Enums\Role;
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
        $educationLevel = [
            EducationLevel::Primary->value,
            EducationLevel::Middle->value,
            EducationLevel::High->value,
            EducationLevel::University->value,
        ];

        $role = [
            Role::Student->value,
            Role::Admin->value,
        ];

        Schema::create('users', function (Blueprint $table) use ($educationLevel, $role) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone')->unique()->nullable();
            $table->string('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->foreignId('country_id')->index()->nullable();
            $table->foreignId('city_id')->index()->nullable();
            $table->foreignId('state_id')->index()->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('language_id')->index()->default(true);
            $table->string('avatar')->nullable();
            $table->integer('gender')->nullable();
            $table->enum('education_level', $educationLevel)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->enum('role', $role)->default(Role::Student->value);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
