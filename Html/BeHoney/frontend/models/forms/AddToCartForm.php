<?php

namespace frontend\models\forms;

use yii\base\Model;

class AddToCartForm extends Model
{
    /**
     * @var int
     */
    public $count = 1;
    /**
     * @var int
     */
    public $productFirstId;
    /**
     * @var int
     */
    public $productSecondId;

    public function rules()
    {
        return [
            [['count'], 'required'],
            [['count', 'productFirstId', 'productSecondId'], 'integer', 'min' => 1],
        ];
    }

    public function attributeLabels()
    {
        return [
            'count' => 'Количество',
            'productFirstId' => 'Номер первого продукта',
            'productSecondId' => 'Номер второго продукт',
        ];
    }
}