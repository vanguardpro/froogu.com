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
            $categoryName = "dairy";
            $deacription = "Red pears product of u.s.a., extra fancy grade red d'anjou pears & pc® goat's milk cheese sweet and juicy red pears, combined with the refreshing bite of goat's cheese, create a perfectly light, elegant dessert.";
            $opric = 4.99;
            $spric = 3.95;
            $price2 = "$1.79 lb / 3.95/kg";
            $storeName = "Independent";
            $ends = "2014-12-11 00:00:00";
            
            $price1 = "$" . $opric;
            //$price2="$".$spric;
           
            
            $saved = $model->savingsEstimator($opric, $spric);

            $sarts = "2014-12-01 00:00:00";
           
            $start_date = new \MongoDate(strtotime($sarts));
            $end_date = new \MongoDate(strtotime($ends));
            $effective = date('M j', strtotime($ends));
            $category = $mongo->getCategory(["name" => $categoryName]);
            $store = $mongo->getStore(['name' => $storeName]);
            $row = array(
                "description" => $deacription,
                "category" => $categoryName,
                "categoryId" => $category['_id'],
                "originalPricePure" => $opric,
                "originalPrice" => $price1,
                "salePricePure" => $spric,
                "salePrice" => $price2,
                "savings" => $saved,
                "store" => $storeName,
                "storeId" => $store['_id'],
                "effective" => "Until " . $effective,
                "startDate" => $start_date,
                "endDate" => $end_date,
            );
            $category = array(
                'name' => 'dairy'
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
            $tableHeaders=$model->tableHeader();
            
            //$mongo->insertGrocerie($row);
            //$mongo->insertSearchResultsTableHeaders($headers);
            //$mongo->insertCategory($category);
            //$output = $mongo->getGroceriesList();
            return $this->render('index', [
                        'model' => $model, 'result' => FALSE, 'tableHeaders' => $tableHeaders
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
