<?php

namespace app\models;

use yii\db\ActiveRecord;

class Service extends ActiveRecord
{
	public $id;
	public $name;
	
	public static function tableName()
    {
        return '{{services}}';
    }
	
	public function getOrders()
	{
		return $this->hasMany(Order::class, ['service_id' => 'id']);
	}
}

