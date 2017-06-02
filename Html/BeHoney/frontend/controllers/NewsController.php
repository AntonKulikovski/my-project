<?php

namespace frontend\controllers;

use frontend\base\web\Controller;
use frontend\models\News;
use frontend\models\Page;
use Yii;
use yii\data\ActiveDataProvider;

class NewsController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $page = Page::getModelByNameFixed('news');
        $page = $page ? $page : new Page();
        $dataProvider = new ActiveDataProvider([
            'query' => News::find()->andWhere(['[[active]]' => true])->orderBy('createdAt DESC'),
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
    public function actionNews($slug)
    {
        $news = News::find()->andWhere(['[[active]]' => true])
            ->andWhere(['[[slug]]' => $slug])
            ->one();
        
        return $this->render('news.sphp', [
            'news' => $news,
        ]);
    }
}
