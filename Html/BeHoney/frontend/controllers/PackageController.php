<?php

namespace frontend\controllers;

use common\base\filters\AjaxOnly;
use common\base\filters\JsonResponse;
use frontend\base\web\Controller;
use frontend\models\Package;
use frontend\models\PackageTag;
use frontend\models\Page;
use frontend\models\ProductVolume;
use frontend\models\Tag;
use Yii;
use yii\web\NotFoundHttpException;

class PackageController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'jsonResponse' => [
                'class' => JsonResponse::className(),
                'only' => [
                    'tag',
                    'select',
                ],
            ],
            'ajaxOnly' => [
                'class' => AjaxOnly::className(),
                'only' => [
                    'tag',
                    'select',
                ],
            ],
        ]);
    }

    /**
     * @param string $slug
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex($slug = 'all')
    {
        /**
         * @var $productFirst ProductVolume;
         * @var $productSecond ProductVolume;
         */
        $packages = PackageTag::getPackageTagByName($slug);

        if (empty($packages)) {
            throw  new NotFoundHttpException('В данный момент подарочных наборов нет');
        }

        $page = Page::getModelByNameFixed('package');
        $tag = Tag::findOne(['[[slug]]' => $slug]);
        $priceProduct = Package::getPriceProductForPackage();
        $existTags = PackageTag::find()->select('tagId')
            ->distinct()
            ->all();

        return $this->render('index.sphp', [
            'packages' => $packages,
            'priceProduct' => $priceProduct,
            'existTags' => $existTags,
            'slug' => $slug,
            'tag' => $tag,
            'page' => $page,
        ]);
    }

    /**
     * @param string $slug
     * @return array
     */
    public function actionTag($slug = 'all')
    {
        $packages = PackageTag::getPackageTagByName($slug);
        $tagName = $slug != 'all' ? Tag::findOne(['[[slug]]' => $slug])->name : 'Все наборы';
        $priceProduct = Package::getPriceProductForPackage();
        $packageView = $this->renderPartial('_packages.sphp', [
            'packages' => $packages,
            'priceProduct' => $priceProduct,
        ]);
        $breadcrumbs = $this->renderAjax('_breadcrumbs.sphp', [
            'slug' => $slug,
            'tagName' => $tagName,
        ]);

        return [
            'success' => true,
            'packageView' => $packageView,
            'breadcrumbs' => $breadcrumbs,
            'tagName' => $tagName,
        ];
    }

    /**
     * @param null|string $slug
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionPackage($slug = null)
    {
        /**
         * @var $productFirst ProductVolume
         * @var $productSecond ProductVolume
         */
        $package = Package::findOne(['[[slug]]' => $slug]);

        if (!$package) {
            throw new NotFoundHttpException('Package not found');
        }

        $page = Page::getModelByNameFixed('package');

        return $this->render('package.sphp', [
            'package' => $package,
            'page' => $page,
        ]);
    }
}
