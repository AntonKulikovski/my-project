<?php

use frontend\models\Order;

/* @var $this yii\web\View */
/* @var $order frontend\models\Order */
/* @var $productOrder \common\models\ProductOrder */
?>
Здравствуйте <?= $order->name ?>.
Ваш заказ был принят.
<?= $order->typePayment == Order::PAYMENT_ERIP
    ? 'Номер вашего заказа в ЕРИП ' . $order->id . ''
    : 'Номер вашего заказа ' . $order->id . ' от ' .  Yii::$app->formatter->asDate($order->createdAt) ?>
Вы заказали:

<?php foreach ($order->productOrders as $productOrder): ?>
    <?= $productOrder->name ?>
    <?= isset($productOrder->volume) ? $productOrder->volume : '' ?>
    <?= $productOrder->nameProductFirst ?>
    <?= $productOrder->nameProductSecond ?>
    <?= Yii::$app->formatter->asDecimal(
        $productOrder->price + $productOrder->priceProductFirst + $productOrder->priceProductSecond,
        2
    ) . ' руб.'?>
    <?= $productOrder->count . ' шт.' ?>
    <?= Yii::$app->formatter->asDecimal(
        $productOrder->count * ($productOrder->price + $productOrder->priceProductFirst + $productOrder->priceProductSecond),
        2
    ) . ' руб.'; ?>
<?php endforeach; ?>
Итого:
Количество: <?= $order->count ?>.
Цена: <?= Yii::$app->formatter->asDecimal($order->price, 2) . ' руб.' ?>.

Тип доставки: <?= Order::$deliveries[$order->typeDelivery] ?>.
Тип оплаты: <?= Order::$payments[$order->typePayment] ?>.

Спасибо.
