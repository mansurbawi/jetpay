<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('nomorpembayaran');
            $table->string('idtagihan')->nullable();
            $table->string('id_type');
            $table->integer('prioritas');
            $table->string('nomorinduk');
            $table->string('nama');
            $table->string('fakultas')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('strata')->nullable();
            $table->string('periode')->nullable();
            $table->string('angkatan')->nullable();
            $table->string('kodetransaksi')->nullable();
            $table->float('totalnominal')->nullable();
            $table->float('satuan')->nullable();
            $table->date('startdate')->nullable();
            $table->date('enddate')->nullable();
            $table->enum('status', ['LUNAS', 'BELUM']);
            $table->string('user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billings');
    }
}
