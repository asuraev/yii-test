<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use \app\models\Order;
use \app\models\Service;
use \yii\data\Pagination;

class SiteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
	
	/**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex($status = 'all')
    {
		switch ($status) {
			case 'pending':
				$status = Order::STATUS_PENDING;
			break;
			case 'in_progress':
				$status = Order::STATUS_IN_PROGRESS;
			break;
			case 'completed':
				$status = Order::STATUS_COMPLETED;
			break;
			case 'canceled':
				$status = Order::STATUS_CANCELED;
			break;
			case 'error':
				$status = Order::STATUS_ERROR;
			break;
			default: 
				$status = false;
		}
		$where = $status !== false ? ['status' => $status] : '';
		$query = Order::find()->where($where);
		
		
		$ordersCount = $query->count();
		$pages = new Pagination(['totalCount' => $ordersCount, 'pageSize' => 100]);
	
		$orders = $query->offset($pages->offset)
			->asArray()
			->with('service')
			->orderBy(['id' => SORT_DESC])
			->limit(100)
			->all();
		
		
		$services = Service::find()
			->select("services.*, COUNT(orders.id) AS order_count")
			->leftJoin('orders', 'orders.service_id = services.id')
			->where($where)
			->groupBy(['services.id'])
			->asArray()
			->all();
		
        return $this->render('index',
			compact('services', 'orders', 'pages', 'ordersCount', 'status')
		);
    }
}
