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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id')->nullable()->constrained('languages')->nullOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('id_number')->unique();
            $table->string('mobile_number')->unique();
            $table->string('email_address')->unique();
            $table->date('date_of_birth');
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
