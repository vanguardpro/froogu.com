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
    
    public function getGroceriesList($query=[]){
       $collection=Yii::$app->mongodb->getCollection('groceries');
       return iterator_to_array($collection->find($query));
      // "categoryId"=>new ObjectId('54813a35ace20a7b010041ab'),
       //try to find groseires which contain word or words in name not in the same order:
       //could be chocolate milk but return chocolate butter milk etc
       //if return more when 0 saveQuery
    }
    public function getStoreList($query=[]){
       $collection=Yii::$app->mongodb->getCollection('store');
       return iterator_to_array($collection->find($query));
       //try to find groseires which contain word or words in name not in the same order:
       //could be chocolate milk but return chocolate butter milk etc
       //if return more when 0 saveQuery
    }
    public function getStore($query=[]){
       $collection=Yii::$app->mongodb->getCollection('store');
       return $collection->findOne($query);
      
    }
    public function getCategoryList($query=[]){
       $collection=Yii::$app->mongodb->getCollection('category');
       return iterator_to_array($collection->find($query));
       //try to find groseires which contain word or words in name not in the same order:
       //could be chocolate milk but return chocolate butter milk etc
       //if return more when 0 saveQuery
    }
    
    public function insertGrocerie($grocerie=[]){
       $collection=Yii::$app->mongodb->getCollection('groceries'); 
       $collection->insert($grocerie);
    }
    public function getCategory($query=[]){
       $collection=Yii::$app->mongodb->getCollection('category');
       return $collection->findOne($query);
    }
    public function insertCategory($category=[]){
       $collection=Yii::$app->mongodb->getCollection('category'); 
       $collection->insert($category);
    }
    public function insertStore($stores=[]){
       $collection=Yii::$app->mongodb->getCollection('store'); 
       $collection->insert($stores);
    }
    
    public function insertSearchResultsTableHeaders($headers=[]){
       $groceriesHeaders=Yii::$app->mongodb->getCollection('groceriesHeaders'); 
       $groceriesHeaders->insert($headers);
    }
    public function getSearchResultsTableHeaders(){
       $groceriesHeaders=Yii::$app->mongodb->getCollection('groceriesHeaders'); 
       $curosrArray=$this->cursorToArray($groceriesHeaders->find(array(), array('_id' => 0)));
       
       return $curosrArray;
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
    
    private function cursorToArray($cursor, $keep_id=FALSE){
        foreach ($cursor as $array){
                if(key_exists("_id", $array)&&!$keep_id){
                    unset($array['_id']);
                }
              
           }
        return $array;
    }

}
