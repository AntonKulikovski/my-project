<?php

use frontend\models\Order;

/* @var $this yii\web\View */
/* @var $order frontend\models\Order */
/* @var $productOrder \common\models\ProductOrder */
?>
Здравствуйте
Вы получили новый заказ от <?= $order->name ?>.

<?= isset($order->lastName) ? $order->lastName : '' ?>
<?= isset($order->name) ? $order->name : '' ?>
<?= isset($order->middleName) ? $order->middleName : '' ?> заказал:

<?php foreach ($order->productOrders as $productOrder): ?>
    <?= $productOrder->name ?>
    <?= isset($productOrder->volume) ? $productOrder->volume : '' ?>
    <?= number_format($productOrder->price, 2, ',', ' ') . ' руб.' ?>
    <?= $productOrder->count . ' шт.' ?>
    <?= $productOrder->count * $productOrder->price . ' руб.'; ?>
<?php endforeach; ?>

Итого:
Количество: <?= $order->count ?>.
Цена: <?= number_format($order->price, 2, ',', ' ') . ' руб.' ?>.

Тип доставки: <?= Order::$deliveries[$order->typeDelivery] ?>.
Тип оплаты: <?= Order::$payments[$order->typePayment] ?>.
<?php if (isset($order->statusPayment)): ?>
    Статус оплаты: <?= $order->statusPayment ?>.
<?php endif;?>

Телефон: <?= $order->phone; ?>
Email: <?= $order->email; ?>
