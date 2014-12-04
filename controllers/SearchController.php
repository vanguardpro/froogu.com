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
           "description"=> "Roasted brand cocnut milk 398 ml",
           "category"=>"dairy",
           "originalPrice"=>"$1.29",
           "salePrice"=>"$0.79",
           "savings"=>'38.75%',
           "store"=>"Farm Boy",
           "effective"=>"2014-12-15T21:00:00-05:00",
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
           //$mongo->insertGrocerie($row);
           //$mongo->insertSearchResultsTableHeaders($headers);
           
            $output=$mongo->getSearchResultsTableHeaders();
            return $this->render('index', [
                'model' => $model,'result' =>FALSE, 'output' => $output
            ]);
        }
        
        //return $this->render('index');
    }

}
/*

 * Array
(   Yii::$app->request->post()
    [_csrf] => YllEby1aSFQKEhANAC8YFikvBiBhFSMNJA58K2IYeBEqaT4/GwsmBg==
    [SearchForm] => Array
        (
            [product] => 23456
        )

    [search-button] => 
)
 * 
 *  */