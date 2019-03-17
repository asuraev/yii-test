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
    public function actionIndex($status = 'all', $service = 'all', $mode = 'all')
    {
		switch ($status) {
			case 'pending':
				$status_id = Order::STATUS_PENDING;
			break;
			case 'in_progress':
				$status_id = Order::STATUS_IN_PROGRESS;
			break;
			case 'completed':
				$status_id = Order::STATUS_COMPLETED;
			break;
			case 'canceled':
				$status_id = Order::STATUS_CANCELED;
			break;
			case 'error':
				$status_id = Order::STATUS_ERROR;
			break;
			default: 
				$status_id = false;
		}
		
		$where = $status_id !== false ? ['status' => $status_id] : [];
		if ('all' != $mode) {
			$where = array_merge($where, ['mode' => (int)$mode]);
		}
		$allOrdersCount = Order::find()->where($where)->count();
		if ('all' != $service) {
			$where = array_merge($where, ['service_id' => (int)$service]);
		}
		$query = Order::find()->where($where);
		
		$ordersCount = $query->count();
		$pages = new Pagination(['totalCount' => $ordersCount, 'pageSize' => 100]);
	
		$orders = $query->offset($pages->offset)
			->asArray()
			->with('service')
			->orderBy(['id' => SORT_DESC])
			->limit(100)
			->all();
		
		$countSubquery = Order::find()
			->select('service_id, COUNT(id) AS order_count')
			->where($where)
			->groupBy('service_id');
		$services = Service::find()
			->select("services.*, order_count")
			->leftJoin(['o' => $countSubquery], 'o.service_id = services.id')
			->asArray()
			->all();
		
		$filter = compact('status', 'service', 'mode');
		
        return $this->render('index',
			compact('services', 'orders', 'pages', 'allOrdersCount', 'filter')
		);
    }
}
