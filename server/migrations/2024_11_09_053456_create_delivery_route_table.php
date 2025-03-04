<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_routes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('uuid');
            $table->string('public_id');
            $table->string('internal_id');
            // $table->string('api_key');
            $table->string('company_uuid');
            $table->string('service_area_uuid');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->json('days_of_week'); // Store array of weekdays (1-7)
            $table->enum('repeat_frequency', ['daily', 'weekly', 'monthly', 'yearly', 'custom']);
            $table->date('start_date')->nullable(); // For non-recurring routes
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
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
        Schema::dropIfExists('delivery_routes');
    }
};
