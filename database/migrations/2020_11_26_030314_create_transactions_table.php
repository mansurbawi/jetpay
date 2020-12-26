
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('billing_id');
            $table->string('nama');
            $table->char('kodebank', 3);
            $table->char('kodechannel', 10);
            $table->string('kodeterminal');
            $table->string('nomorpembayaran');
            $table->dateTime('tanggaltransaksi');
            $table->string('idtransaksi');
            $table->float('totalnominal');
            $table->string('nomorjurnalpembukuan');
            $table->string('checksum');
            $table->enum('choices', ['payment', 'reversal']);
            $table->integer('userid');
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
        Schema::dropIfExists('transactions');
    }
}
