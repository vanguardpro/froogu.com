<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

use app\models\SearchForm;
use app\models\MongoHelper;
class SearchController extends \yii\web\Controller
{
    public function actionIndex()
    {
       $model = new SearchForm();
       $output="";
        if ($model->load(Yii::$app->request->post()) && $model->search(Yii::$app->request->post())) {
         return $this->render('index', [
                'model' => $model, 'result' => $model->search(Yii::$app->request->post()),'tableHeaders'=>$model->tableHeader(), 'output'=>$output
         ]);
        } else {
            $mongo=new MongoHelper();
            $row=array(
           "description"=> "Carnation evaporated milk selected varieties 370 ml",
           "category"=>"dairy",
           "category"=>"dairy",
           "originalPricePure"=>"2.10",
           "originalPrice"=>"$2.10",
           "salePricePure"=>"1.34",
           "salePrice"=>"3/$4 or $1.34 ea.",
           "savings"=>'36.00%',
           "store"=>"Independent",
           "storeId"=>"Independent",
           "effective"=>"Until Dec 10",
           "startDate"=>"2014-12-01T21:00:00-05:00",
           "endDate"=>"2014-12-05T21:00:00-05:00",
           
           );
            $store=array(
                "name"=>"Bulk Barn"
            );
            $headers=array(
            array("description"=>"Description of Product"),
            array("category"=>"Category"),
            array("originalPrice"=>"Original Price"),
            array("salePrice"=>"Sale Price"),
            array("savings"=>"% Savings"),
            array("store"=>"Store Name"),
            array("effective"=>"Effective Until"),
         );
       
           
            $output=$mongo->getStoreList();
            return $this->render('index', [
                'model' => $model,'result' =>FALSE, 'output' => $output
            ]);
        }
        
       
    }

}
