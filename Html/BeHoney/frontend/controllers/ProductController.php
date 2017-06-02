<?php

namespace frontend\controllers;

use frontend\base\web\Controller;
use frontend\models\forms\AddToCartForm;
use frontend\models\Product;
use frontend\models\ProductVolume;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ProductController extends Controller
{
    /**
     * @param string $slug
     * 
     * @inheritdoc
     */
    public function actionIndex($slug)
    {
        $product = Product::findOne(['slug' => $slug]);

        if (!$product) {
            throw  new NotFoundHttpException('Product not found');
        }

        $volumes = [];
        $addToCartForm = new AddToCartForm();
        $productVolume = '';

        /** @var $volume ProductVolume */
        /** @var $product Product */
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $volumeProduct = Yii::$app->request->post('volume');
            $imageUrl = $product->imageFile->asSmall;
            $price = 0;
            $id = 1;

            if (is_array($product->productVolumes) && !empty($product->productVolumes)) {
                foreach ($product->productVolumes as $volume) {
                    if ($volume->volume == $volumeProduct) {
                        $imageUrl = $volume->imageFile->asMain;
                        $price = $volume->price;
                        $id = $volume->id;
                    }
                }
            }

            return [
                'success' => true,
                'imageUrl' => $imageUrl,
                'price' => $price,
                'id' => $id,
            ];
        }
        if (is_array($product->productVolumes) && !empty($product->productVolumes)) {
            foreach ($product->productVolumes as $volume) {
                if ($productVolume == '') {
                    $productVolume = $volume;
                }

                $volumes[$volume->volume] = ProductVolume::$volumes[$volume->volume];
            }
        }

        return $this->render('index.sphp', [
            'product' => $product,
            'volumes' => $volumes,
            'addToCartForm' => $addToCartForm,
            'productVolume' => $productVolume,
        ]);
    }
}
