<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddSavasianField extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    Schema::table('comments', function ($table) {
      $table->boolean('savasian')->default(FALSE);
    });

    DB::table('comments')
      ->where('email', 'like', '%@savaslabs.com')
      ->update(['savasian' => 1]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
