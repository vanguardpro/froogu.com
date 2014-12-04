<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class SearchForm extends Model
{
    public $query;
    private $searchQuery;
 

    /**
     * @return array the validation rules.
     */
    
    
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['query'], 'required'],
          
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'query' => 'Product Name:',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param  string  $email the target email address
     * @return boolean whether the model passes validation
     */
    
    public function setSearchQuery($value) { 
        return $this->searchQuery=$value; 
        
    }
    
    public function search($query)
    {
        if ($this->validate()) {
            
           if($query['SearchForm']['query'])
           $this->setSearchQuery($query['SearchForm']['query']);
           return $this->doSearch();
                  
            
        } else {
            return false;
        }
    }
    public function tableHeader(){
        return array(
            array("description"=>"Description of Product"),
            array("category"=>"Category"),
            array("originalPrice"=>"Original Price"),
            array("salePrice"=>"Sale Price"),
            array("savings"=>"% Savings"),
            array("store"=>"Store Name"),
            array("effective"=>"Effective Until"),
         );
    }
    
    private function doSearch(){
        if($this->searchQuery!='milk'){
            return "No Search Results for <b>".$this->searchQuery."</b>";
        }
        $test=$this->savingsEstimator("$1.29", "$0.88");
        
        $searchResuls="Nothing Found for <b>".$this->searchQuery."</b>";
       
       return $searchResuls;
    }
    
    private function savingsEstimator($price1, $price2, $add_sign=TRUE){
        $price1=  str_replace('$', '', $price1);
        $price2=  str_replace('$', '', $price2);
        $percentmark="";
        
        if(!is_numeric($price1)){
            return array('error','Price 1 should be numeric');
        }
        else if(!is_numeric($price2)){
             return array('error','Price 2 should be numeric');
        }
        else {
            if($add_sign){
                $percentmark="%";
                
            }
            return (sprintf('%0.2f',(((float)$price1-(float)$price2)/(float)$price1)*100)).$percentmark;
        }
    }
}
