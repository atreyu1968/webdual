<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTareasTableAddAssignment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tareas', function (Blueprint $table) {
            // Añadir campo para el usuario asignado
            $table->unsignedBigInteger('asignado_id')->nullable()->after('creador_id');
            $table->foreign('asignado_id')->references('id')->on('users')->onDelete('set null');

            // Añadir campo para la fecha de asignación
            $table->timestamp('fecha_asignacion')->nullable()->after('asignado_id');

            // Añadir campo para prioridad
            $table->enum('prioridad', ['baja', 'media', 'alta'])->default('media')->after('estado');

            // Añadir campo para comentarios
            $table->text('comentarios')->nullable()->after('prioridad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->dropForeign(['asignado_id']);
            $table->dropColumn('asignado_id');
            $table->dropColumn('fecha_asignacion');
            $table->dropColumn('prioridad');
            $table->dropColumn('comentarios');
        });
    }
}