<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use app\models\Order;
USE app\controllers\SiteController;
/* @var $this yii\web\View */

$this->title = 'Yii Test';
?>
<div class="site-index">
	
	<?php
	echo Nav::widget([
		'options' => ['class' => 'navbar-nav nav-tabs'],
		'items' => [
			['label' => 'All orders', 'url' => [Yii::$app->homeUrl],		'active' => $filter['status'] == 'all'],
			['label' => 'Pending', 'url' => [Url::to('/pending')],			'active' => $filter['status'] == 'pending'],
			['label' => 'In progress', 'url' => [Url::to('/in_progress')],	'active' => $filter['status'] == 'in_progress'],
			['label' => 'Completed', 'url' => [Url::to('/completed')],		'active' => $filter['status'] == 'completed'],
			['label' => 'Canceled', 'url' => [Url::to('/canceled')],		'active' => $filter['status'] == 'canceled'],
			['label' => 'Error', 'url' => [Url::to('/error')],				'active' => $filter['status'] == 'error'],
		],
	]);
	?>
	
	<form class="pull-right form-inline search-form" action="<?= Url::to('/'.('all' != $filter['status'] ? $filter['status'] : ''))?>" method="get">
		<div class="input-group">
			<input type="text" name="search" class="form-control" value="<?= $filter['search']?>" placeholder="Search orders">
			<span class="input-group-btn search-select-wrap">
				<select class="form-control search-select" name="search_type">
					<option value="<?= SiteController::SEARCH_TYPE_ORDER_ID?>" <?= (SiteController::SEARCH_TYPE_ORDER_ID == $filter['search_type'] ? 'selected="selected"' : '') ?>>Order ID</option>
					<option value="<?= SiteController::SEARCH_TYPE_LINK?>" <?= (SiteController::SEARCH_TYPE_LINK == $filter['search_type'] ? 'selected="selected"' : '') ?>>Link</option>
					<option value="<?= SiteController::SEARCH_TYPE_USERNAME?>" <?= (SiteController::SEARCH_TYPE_USERNAME == $filter['search_type'] ? 'selected="selected"' : '') ?>>Username</option>
				</select>
				<button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
			</span>
		</div>
	</form>
	
	
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
							<?php $path = '/'.('all' != $filter['status'] ? $filter['status'] : '').('all' != $filter['mode'] ? '/mode/'.$filter['mode'] : ''); ?>
							<li <?= ('all' == $filter['service'] ? 'class="active"' : '') ?>><a href="<?= Url::to([$path]) ?>"><span class="label-id"><?=$allOrdersCount?></span> All</a></li>
							<?php foreach ($services as $service): ?>
								<?php $path = '/'.('all' != $filter['status'] ? $filter['status'] : '').('/service/'.$service['id']).('all' != $filter['mode'] ? '/mode/'.$filter['mode'] : ''); ?>
							<li <?= ($service['id'] === $filter['service'] ? 'class="active"' : '') ?>><a href="<?= Url::to([$path]) ?>"><span class="label-id"><?= (isset($service['order_count']) ? $service['order_count'] : 0 )?></span> <?= $service['name'];?></a></li>
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
							<?php $path = '/'.('all' != $filter['status'] ? $filter['status'] : '').('all' != $filter['service'] ? '/service/'.$filter['service'] : ''); ?>
							<li <?= ('all' == $filter['mode'] ? 'class="active"' : '') ?>><a href="<?= Url::to([$path]) ?>">All</a></li>
							<?php $path = '/'.('all' != $filter['status'] ? $filter['status'] : '').('all' != $filter['service'] ? '/service/'.$filter['service'] : '').'/mode/'.Order::MODE_MANUAL; ?>
							<li <?= (Order::MODE_MANUAL === $filter['mode'] ? 'class="active"' : '') ?>><a href="<?= Url::to([$path]) ?>">Manual</a></li>
							<?php $path = '/'.('all' != $filter['status'] ? $filter['status'] : '').('all' != $filter['service'] ? '/service/'.$filter['service'] : '').'/mode/'.Order::MODE_AUTO; ?>
							<li <?= (Order::MODE_AUTO === $filter['mode'] ? 'class="active"' : '') ?>><a href="<?= Url::to([$path]) ?>">Auto</a></li>
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
	<div class="col-sm-8">
		<?= LinkPager::widget(['pagination' => $pages]); ?>
	</div>
	<div class="col-sm-4 pagination-counters">
		<?php if ($pagination['total'] > 100): ?>
			<?= $pagination['from']?> to <?= ($pagination['to'] > $pagination['total'] ? $pagination['total'] : $pagination['to'])?> of <?= $pagination['total']?>
		<?php else:?>
			<?= $pagination['total']?>
		<?php endif;?>
	</div>
	
</div>
