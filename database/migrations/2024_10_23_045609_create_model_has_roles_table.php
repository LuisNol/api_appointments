<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger(PermissionRegistrar::$pivotRole);
            $table->string('model_type');
            $table->unsignedBigInteger('model_id'); // Model morph key
            $table->index(['model_id', 'model_type'], 'model_has_roles_model_id_model_type_index');

            // Foreign key references
            $table->foreign(PermissionRegistrar::$pivotRole)
                ->references('id')->on('roles')
                ->onDelete('cascade');

            $table->primary([PermissionRegistrar::$pivotRole, 'model_id', 'model_type'],
                'model_has_roles_role_model_type_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_has_roles');
    }
};
