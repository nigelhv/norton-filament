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
          Schema::create('activities', function (Blueprint $table) {
                    $table->id();
                    $table->string('title');
                    $table->text('description');
                    $table->date('date');
                    $table->unsignedInteger('location_id');
                    $table->Integer('maths_extra_credit')->nullable();
                    $table->Integer('english_extra_credit')->nullable();
                    $table->integer('pshe_extra_credit')->nullable();
                    $table->timestamps();
                    $table->softDeletes();
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
