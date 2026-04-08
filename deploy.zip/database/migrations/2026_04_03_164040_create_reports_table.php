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
    Schema::create('reports', function (Blueprint $table) {
        $table->id();
        $table->string('staff_name');
        $table->decimal('spend', 8, 2);
        $table->integer('messages');
        $table->integer('new_id');
        $table->timestamps(); // បង្កើតថ្ងៃខែឱ្យយើងអូតូ
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
