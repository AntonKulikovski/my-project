<?php

namespace frontend\controllers;

use frontend\base\web\Controller;
use frontend\models\Category;
use frontend\models\Package;
use frontend\models\PackageTag;
use frontend\models\Product;
use Yii;

class SitemapController extends Controller
{
    public function actionIndex()
    {
        if (!$xml_sitemap = Yii::$app->cache->get('sitemap')) {
            $urls = array();
            $categories = Category::find()->all();

            /** @var Category $category */
            foreach ($categories as $category) {
                $urls[] = [
                    Yii::$app->urlManager->createUrl(['/category/' . $category->slug . '/']),
                    'daily'
                ];
            }

            $products = Product::find()->all();

            /** @var Product $product */
            foreach ($products as $product) {
                $urls[] = [
                    Yii::$app->urlManager->createUrl(['/product/' . $product->slug . '/']),
                    'weekly'
                ];
            }

            $urls[] = [
                Yii::$app->urlManager->createUrl(['/podarochnye-nabory/all/']),
                'weekly'
            ];
            $packages = Package::find()->all();

            /** @var Package $package */
            foreach ($packages as $package) {
                $urls[] = [
                    Yii::$app->urlManager->createUrl(['/podarochnyj-nabor/' . strtolower($package->type) . '/' . $package->slug . '/']),
                    'weekly'
                ];
            }

            $existTags = PackageTag::find()->select('tagId')
                ->distinct()
                ->all();

            /** @var PackageTag $existTag */
            foreach ($existTags as $existTag) {
                $urls[] = [
                    Yii::$app->urlManager->createUrl(['/podarochnye-nabory/' . $existTag->tag->slug . '/']),
                    'weekly'
                ];
            }

            $urls[] = [
                Yii::$app->urlManager->createUrl(['/pay/']),
                'weekly'
            ];
            $urls[] = [
                Yii::$app->urlManager->createUrl(['/about/']),
                'weekly'
            ];
            $urls[] = [
                Yii::$app->urlManager->createUrl(['/news/']),
                'weekly'
            ];
            $xml_sitemap = $this->renderPartial('index', [
                'host' => Yii::$app->request->hostInfo,
                'urls' => $urls,
            ]);


            Yii::$app->cache->set('sitemap', $xml_sitemap, 3600 * 12);
        }

        header('Content-Type: application/xml');

        print $xml_sitemap;
    }
}
