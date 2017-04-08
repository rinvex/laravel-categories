<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategorizablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('rinvex.category.tables.categorizables'), function (Blueprint $table) {
            // Columns
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('categorizable_id');
            $table->string('categorizable_type');
            $table->timestamps();

            // Indexes
            $table->unique(['category_id', 'categorizable_id', 'categorizable_type'], 'categorizables_ids_type_unique');
            $table->foreign('category_id')->references('id')->on('categories')
                  ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('rinvex.category.tables.categorizables'));
    }
}
