<?php

use frontend\models\Order;

/* @var $this yii\web\View */
/* @var $order frontend\models\Order */
/* @var $productOrder \common\models\ProductOrder */
?>
Здравствуйте <?= $order->name ?>.
Ваш заказ был принят.
Вы заказали:

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

Спасибо.
