<?php

use yii\db\Migration;

/**
 * Class m190317_084028_import_db
 */
class m190317_084028_import_db extends Migration
{
	public function up()
	{
		$this->execute(file_get_contents(__DIR__.'/../db_dump/test_db.sql'));
	}

	public function down()
	{
		$this->dropTable('orders');
		$this->dropTable('services');
	}
}
