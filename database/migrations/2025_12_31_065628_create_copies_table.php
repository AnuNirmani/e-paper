<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('copies', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('customer_id');
        $table->unsignedBigInteger('publication_id');

        $table->text('message')->nullable();

        $table->timestamps();

        // Foreign Keys
        $table->foreign('customer_id')
              ->references('id')->on('customers')
              ->onDelete('cascade');

        $table->foreign('publication_id')
              ->references('id')->on('publications')
              ->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('copies');
    }
};
