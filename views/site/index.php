<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use app\models\Order;
/* @var $this yii\web\View */

$this->title = 'Yii Test';
?>
<div class="site-index">
	
	<?php
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav nav-tabs'],
        'items' => [
            ['label' => 'All orders', 'url' => [Yii::$app->homeUrl],		'active' => $status === false],
			['label' => 'Pending', 'url' => [Url::to('/pending')],			'active' => $status === Order::STATUS_PENDING],
			['label' => 'In progress', 'url' => [Url::to('/in_progress')],	'active' => $status === Order::STATUS_IN_PROGRESS],
			['label' => 'Completed', 'url' => [Url::to('/completed')],		'active' => $status === Order::STATUS_COMPLETED],
			['label' => 'Canceled', 'url' => [Url::to('/canceled')],		'active' => $status === Order::STATUS_CANCELED],
			['label' => 'Error', 'url' => [Url::to('/error')],				'active' => $status === Order::STATUS_ERROR],
        ],
    ]);
    ?>
	
	
	<table class="table order-table">
		<thead>
			<tr>
				<th>ID</th>
				<th>User</th>
				<th>Link</th>
				<th>Quantity</th>
				<th class="dropdown-th">
					<div class="dropdown">
						<button class="btn btn-th btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							Service
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
							<li class="active"><a href=""><span class="label-id"><?=$ordersCount?></span> All</a></li>
							<?php foreach ($services as $service): ?>
								<li><a href=""><span class="label-id"><?= $service['order_count']?></span> <?= $service['name'];?></a></li>
							<?php endforeach;?>
						</ul>
					</div>
				</th>
				<th>Status</th>
				<th class="dropdown-th">
					<div class="dropdown">
						<button class="btn btn-th btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Mode
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
							<li class="active"><a href="">All</a></li>
							<li><a href="">Manual</a></li>
							<li><a href="">Auto</a></li>
						</ul>
					</div>
				</th>
				<th>Created</th>
			</tr>
		</thead>
    <tbody>
	<?php foreach ($orders as $order) :?>
		<tr>
			<td><?= $order['id']; ?></td>
			<td><?= $order['user']; ?></td>
			<td class="link"><?= $order['link']; ?></td>
			<td><?= $order['quantity']; ?></td>
			<td class="service">
				<span class="label-id"><?= $order['service']['id']; ?></span> <?= $order['service']['name']; ?>
			</td>
			<td>
				<?php
					switch ($order['status']) {
						case Order::STATUS_PENDING:
							echo 'Pending';
						break;
						case Order::STATUS_IN_PROGRESS:
							echo 'In progress';
						break;
						case Order::STATUS_COMPLETED:
							echo 'Completed';
						break;
						case Order::STATUS_CANCELED:
							echo 'Canceled';
						break;
						case Order::STATUS_ERROR:
							echo 'Error';
						break;
					}
				?>
			</td>
			<td>
				<?php
					if (app\models\Order::MODE_MANUAL == $order['mode']) {
						echo 'Manual';
					}
					else {
						echo 'Auto';
					}
				?>
			</td>
			<td><nobr><?= date('Y-m-d', $order['created_at'])?></nobr> <nobr class="nowrap"><?= date('H:i:s', $order['created_at'])?></nobr></td>
		</tr>
	<?php endforeach;?>
    </tbody>
  </table>
	
<?= LinkPager::widget(['pagination' => $pages]); ?>
	
</div>
