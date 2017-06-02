<?php

namespace frontend\controllers;

use common\base\web\UploadAction;
use frontend\base\web\Controller;
use frontend\models\Page;
use frontend\models\Review;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Response;


class ReviewController extends Controller
{
    /**
     * @return array
     */
    public function actions()
    {
        return [
            'upload-image' => [
                'class' => UploadAction::className(),
                'context' => 'image-review',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actionIndex()
    {
        $model = new Review();

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            
            if ($model->save()) {
                return [
                    'success' => true,
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Ошибка при отправке формы.',
                ];
            }
        } 

        $page = Page::getModelByNameFixed('review');
        $page  = $page ? $page : new Page();

        $dataProvider = new ActiveDataProvider([
            'query' => Review::find()->andWhere(['[[active]]' => true])->orderBy('createdAt DESC'),
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);

        return $this->render('index.sphp', [
            'dataProvider' => $dataProvider,
            'page' => $page,
            'model' => $model,
        ]);
    }
}