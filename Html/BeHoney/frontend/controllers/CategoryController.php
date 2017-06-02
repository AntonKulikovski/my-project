<?php

namespace frontend\controllers;

use frontend\base\web\Controller;
use frontend\models\Category;
use Yii;
use yii\web\NotFoundHttpException;

class CategoryController extends Controller
{
    /**
     * @param string $slug
     *
     * @inheritdoc
     */
    public function actionIndex($slug)
    {
        $category = Category::find()->with('products')
            ->andWhere(['[[slug]]' => $slug])
            ->one();

        if (empty($category)) {
            throw  new NotFoundHttpException('В данный момент категория пуста');
        }
        
        return $this->render('index.sphp', [
            'category' => $category,
        ]);
    }
}
