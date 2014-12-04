<?php

namespace app\models;

use yii;
use yii\base\Model;
use yii\mongodb\Collection;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MongoHelper
 *
 * @author const
 */
class MongoHelper extends Model {

    //put your code here
    private $queries, $groceries, $categories, $stores;
    public function __construct() {
        ;
    }
    
    public function saveQuery($query){
     $queries = Yii::$app->mongodb->getCollection('queries'); 
     $queryStats = Yii::$app->mongodb->getCollection('queryStats'); 
     
     //1 check if query exists, update counter yes->update counter, no - save 1
     //2 add query stats id, timestamp;
    }
    
    public function getQueryList($searchString){
      $queries = Yii::$app->mongodb->getCollection('queries'); 
      //condition would be seperate words and look into groseires where contain word or words
      //should be order by counter the more the better
     
    }
    
    public function getGroceriesList($query){
       $groceries=Yii::$app->mongodb->getCollection('groceries'); 
       //try to find groseires which contain word or words in name not in the same order:
       //could be chocolate milk but return chocolate butter milk etc
       //if return more when 0 saveQuery
    }
    
    public function insertGrocerie($grocerie=[]){
       $groceries=Yii::$app->mongodb->getCollection('groceries'); 
       $groceries->insert($grocerie);
    }
    
    public function insertSearchResultsTableHeaders($headers=[]){
       $groceriesHeaders=Yii::$app->mongodb->getCollection('groceriesHeaders'); 
       $groceriesHeaders->insert($headers);
    }
    public function getSearchResultsTableHeaders(){
       $groceriesHeaders=Yii::$app->mongodb->getCollection('groceriesHeaders'); 
       return iterator_to_array($groceriesHeaders->find());
    }

    public function mongoColection() {

        $collection = Yii::$app->mongodb->getCollection('customer');
        //$collection->insert(['name' => 'John Smith', 'status' => 1]);
        $collection->insert(array('name' => 'John', 'lastname' => 'Smith', 'status' => 3));
    }

    public function mongoTest() {

        $collection = Yii::$app->mongodb->getCollection('customer');
        $condition = array (
            
                'OR',
                array ('AND', array('name' => 'John'), array('lastname' => 'Smith')), 
                array('name' => 'John Smith'), 
                //array('status' => array(1, 2, 3))
            
        );
        
        $cursor=$collection->find($condition);
        return iterator_to_array($cursor);
    }

}
