<?php

namespace frontend\controllers;

use common\models\Slider;
use frontend\base\web\Controller;
use frontend\models\Category;
use frontend\models\ContactForm;
use frontend\models\Magazine;
use frontend\models\Package;
use frontend\models\Page;
use frontend\models\Review;
use Yii;
use yii\web\NotFoundHttpException;
use yii\base\Action;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $page = null;

    /**
     * @param Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        /** @var  $action Action */
        $page = Page::getModelByNameFixed($action->id);
        $this->page  = $page ? $page : new Page();
        
        return parent::beforeAction($action);
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $categories = Category::find()
            ->joinWith('products')
            ->orderBy('-(' . Category::tableName() . '.position) DESC')
            ->all();
        
        $packages = Package::find()
            ->andWhere(['[[active]]' => true])
            ->orderBy('-(position) DESC')
            ->all();
        $slider = Slider::find()->orderBy('-(position) DESC')->all();
        $media = $this->getDataInstagram();
        $priceProduct = Package::getPriceProductForPackage();
        $reviews = Review::find()
            ->andWhere(['[[main]]' => true])
            ->orderBy('-(position) DESC')
            ->all();
        $magazines = Magazine::find()
            ->andWhere(['[[main]]' => true])
            ->andWhere(['[[active]]' => true])
            ->orderBy('createdAt DESC')
            ->all();

        return $this->render('index.sphp', [
            'categories' => $categories,
            'reviews' => $reviews,
            'magazines' => $magazines,
            'priceProduct' => $priceProduct,
            'packages' => $packages,
            'slider' => $slider,
            'media' => $media,
            'page' => $this->page,
        ]);
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $bodyText = $this->renderPartial('email/_text-template.sphp', array(
                    'model' => $model,
                ), true);
                $bodyHtml = $this->renderPartial('email/_html-template.sphp', array(
                    'model' => $model,
                ), true);
                
                if ($model->sendEmail(Yii::$app->params['adminEmail'], $bodyText, $bodyHtml)) {
                    return [
                       'success' => true,
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Не удалось отправить почту. Попробуйте еще раз или свяжитесь с администратором!',
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Вы ввели неверные данные. Попробуйте еще раз!',
                ];
            }
        }

        return $this->render('contact.sphp', [
            'model' => $model,
            'page' => $this->page,
        ]);
    }

    /**
     * Displays Pay page.
     *
     * @return mixed
     */
    public function actionPay()
    {
        return $this->render('pay.sphp', [
            'page' => $this->page,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about.sphp', [
            'page' => $this->page,
        ]);
    }

    /**
     * @return array
     */
    public function getDataInstagram()
    {
        $token = Yii::$app->params['instagram']['token'];
        $user_id = Yii::$app->params['instagram']['userId'];
        $instagram_cnct = curl_init(); // инициализация cURL подключения
        curl_setopt($instagram_cnct, CURLOPT_URL, "https://api.instagram.com/v1/users/" . $user_id . "/media/recent?access_token=" . $token); // подключаемся
        curl_setopt($instagram_cnct, CURLOPT_RETURNTRANSFER, 1); // просим вернуть результат
        curl_setopt($instagram_cnct, CURLOPT_TIMEOUT, 15);
        $media = json_decode(curl_exec($instagram_cnct)); // получаем и декодируем данные из JSON
        curl_close($instagram_cnct); // закрываем соединение

        return $media;
    }

    /**
     * @return string
     */
    public function actionError()
    {
        $categories = Category::find()
            ->joinWith('products')
            ->orderBy('-(' . Category::tableName() . '.position) DESC')
            ->all();
        $exception = Yii::$app->errorHandler->exception;

        /** @var $exception NotFoundHttpException */
        if ($exception !== null) {
            return $this->render('error.sphp', [
                'exception' => $exception,
                'categories' => $categories,
            ]);
        }
        
        $this->redirect(['site/index'], 301)->send();
    }

    /**
     * @return string
     */
    public function actionOrder()
    {
        return $this->render('order.sphp', [
            'page' => $this->page,
        ]);
    }

    /**
     * @return string
     */
    public function actionPolitic()
    {
        return $this->render('politic.sphp', [
            'page' => $this->page,
        ]);
    }
}

