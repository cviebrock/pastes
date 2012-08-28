<?php

class Update_Pastes_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('pastes', function ($table) {
			$table->integer('views')->default(0);
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('pastes', function ($table) {
			$table->drop('views');
		});
	}

}