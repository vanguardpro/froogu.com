<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SearchForm;
use app\models\MongoHelper;

class SiteController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex() {
        $model = new SearchForm();
        $output = "";
        if ($model->load(Yii::$app->request->post()) && $model->search(Yii::$app->request->post())) {
            return $this->render('index', [
                        'model' => $model, 'result' => $model->search(Yii::$app->request->post()), 'tableHeaders' => $model->tableHeader(), 'output' => $output
            ]);
        } else {
            $mongo = new MongoHelper();
            $row = array(
                "description" => "Roasted brand cocnut milk 398 ml",
                "category" => "dairy",
                "originalPrice" => "$1.29",
                "salePrice" => "$0.79",
                "savings" => '38.75%',
                "store" => "Farm Boy",
                "effective" => "2014-12-15T21:00:00-05:00",
            );
            $headers = array(
                array("description" => "Description of Product"),
                array("category" => "Category"),
                array("originalPrice" => "Original Price"),
                array("salePrice" => "Sale Price"),
                array("savings" => "% Savings"),
                array("store" => "Store Name"),
                array("effective" => "Effective Until"),
            );
            //$mongo->insertGrocerie($row);
            //$mongo->insertSearchResultsTableHeaders($headers);

            $output = $mongo->getSearchResultsTableHeaders();
            return $this->render('index', [
                        'model' => $model, 'result' => FALSE, 'output' => $output
            ]);
        }

        //return $this->render('index');
    }

    public function actionPage() {
        return $this->render('page');
    }

    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                        'model' => $model,
            ]);
        }
    }

    public function actionAbout() {
        return $this->render('about');
    }

}
