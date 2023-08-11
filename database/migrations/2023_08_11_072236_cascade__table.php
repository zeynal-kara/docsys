<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Schema::dropIfExists('doc_subject_pivots');
        Schema::table('doc_subject_pivots', function (Blueprint $table) {

            $table->id();
            $table->integer('subject_id')->nullable();

            $table->foreignId('document_id')
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('cascade')->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
};
