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
    Schema::create('financial_reports', function (Blueprint $table) {
        $table->id();

        $table->date('start_date');
        $table->date('end_date');

        // Montos calculados al generar el reporte
        $table->decimal('total_ingresos', 10, 2)->default(0);
        $table->decimal('total_pendiente', 10, 2)->default(0);

        $table->foreignId('created_by')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_reports');
    }
};
