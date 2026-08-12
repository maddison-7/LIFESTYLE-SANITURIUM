<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('healthcare_staff', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->string('specialty')->nullable();
            $table->text('biography')->nullable();
            $table->string('photo')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('healthcare_staff');
    }
};
