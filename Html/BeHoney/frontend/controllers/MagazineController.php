<?php

namespace frontend\controllers;

use frontend\base\web\Controller;
use frontend\models\Magazine;
use frontend\models\Page;
use Yii;
use yii\data\ActiveDataProvider;

class MagazineController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $page = Page::getModelByNameFixed('magazine');
        $page = $page ? $page : new Page();
        $dataProvider = new ActiveDataProvider([
            'query' => Magazine::find()->andWhere(['[[active]]' => true])->orderBy('createdAt DESC'),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index.sphp', [
            'dataProvider' => $dataProvider,
            'page' => $page,
        ]);
    }

    /**
     * @param string $slug
     * @return string
     */
    public function actionMagazine($slug)
    {
        $magazine = Magazine::find()->andWhere(['[[active]]' => true])
            ->andWhere(['[[slug]]' => $slug])
            ->one();

        return $this->render('magazine.sphp', [
            'magazine' => $magazine,
        ]);
    }
}
