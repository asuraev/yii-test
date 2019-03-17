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
	
	public function getRoute() {
		return '/';
	}

	/**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
		$query = Order::find();
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
			->groupBy(['services.id'])
			->asArray()
			->all();
		
        return $this->render('index',
			compact('services', 'orders', 'pages', 'ordersCount')
		);
    }
}
