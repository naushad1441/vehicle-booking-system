<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable(); // Allow descriptions to be null
            $table->decimal('daily_rate', 8, 2); // Store daily rate with two decimal places
            $table->decimal('hourly_rate', 8, 2)->nullable(); // Allow hourly rate to be null (optional)
            $table->boolean('available')->default(true); // Default vehicle to available
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
