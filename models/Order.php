<?php

namespace app\models;

use yii\db\ActiveRecord;

class Order extends ActiveRecord
{
	const MODE_MANUAL	= 0;
	const MODE_AUTO		= 1;
	
	const STATUS_PENDING		= 0;
	const STATUS_IN_PROGRESS	= 1;
	const STATUS_COMPLETED		= 2;
	const STATUS_CANCELED		= 3;
	const STATUS_ERROR			= 4;

	public $id;
	public $user;
	public $link;
	public $quantity;
	public $service_id;
	public $status;
	public $created_at;
	public $mode;
	
	public static function tableName()
    {
        return '{{orders}}';
    }
	
	public function getService() 
	{
		return $this->hasOne(Service::class, ['id' => 'service_id']);
	}
}

