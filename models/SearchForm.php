<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\MongoHelper;

/**
 * ContactForm is the model behind the contact form.
 */
class SearchForm extends Model {

    public $query;
    private $searchQuery;
    private $mongo;

    /**
     * @return array the validation rules.
     */
    public function __construct() {
        $this->mongo = new MongoHelper();
    }

    public function rules() {
        return [
            // name, email, subject and body are required
            [['query'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels() {
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
        return $this->searchQuery = $value;
    }

    public function search($query) {
        if ($this->validate()) {

            if ($query['SearchForm']['query'])
                $this->setSearchQuery($query['SearchForm']['query']);
            return $this->doSearch();
        } else {
            return false;
        }
    }

    public function tableHeader() {
        /* return array(
          array("description"=>"Description of Product"),
          array("category"=>"Category"),
          array("originalPrice"=>"Original Price"),
          array("salePrice"=>"Sale Price"),
          array("savings"=>"% Savings"),
          array("store"=>"Store Name"),
          array("effective"=>"Effective Until"),
          ); */
        $cursor = $this->mongo->getSearchResultsTableHeaders();
        $searchResuls = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($cursor));
        return $searchResuls;
    }

    private function doSearch() {
        $trim_query=  trim($this->searchQuery);
        $user_query = preg_replace("/[[:blank:]]+/", " ", $trim_query);
        $arr_query = explode(' ', $user_query);
        
        if (count($arr_query) > 1) {

            $query = array(
                '$or' => array()
            );

            foreach ($arr_query as $q) {
                $query['$or'][] = array('description' => array('$regex' => $q));
                $query['$or'][] = array('store' => array('$regex' => $q));
            }
        } else {
            

            $query = array(
                '$or' => array(
                    array('description' => array('$regex' => $user_query)),
                    array('store' => array('$regex' => $user_query))
                )
            );

        
            // ref http://stackoverflow.com/questions/14023821/php-mongodb-or-regex-search 
        }
        
        //$query = array('description' => array('$regex' => $this->searchQuery));
        //db.groceries.find({"description": {$in:[/chocolate/,/milk/]}})
        $query = array('description' => array('$in' => ['/chocolate milk/']));
       // $query = array('description')=> array('$in' => array('milk');
        $query=array('description'=>array('$in'=>array())) ;
        
        
        $query = array(
    '$or'   => array(
        array(
            '$and'  => array(
                array(
                    'description'     => array(
                        '$regex'    => 'chocolate',
                        '$options'  => 'i'
                    )
                ),
                array(
                    'description'  => array(
                        '$regex' => 'milk',
                        '$options'  => 'i'
                    )
                )
            )
        ),
        array(
            '$and'  => array(
                array(
                    'description'      => array(
                        '$regex'    => 'milk',
                        '$options'  => 'i'
                    )
                ),
                array(
                    'description'   => array(
                        '$regex' => 'chocolate',
                        '$options'  => 'i'
                    )
                )
            )
        ),
    )
);
        
        
        
        
        
        
        
        $searchResuls = $this->mongo->getGroceriesList($query);
        if (empty($searchResuls)) {
            $searchResuls = "No search results  for <b>" . $this->searchQuery . "</b>";
        }

        return $searchResuls;
     
    }

    private function _doSearch() {

        $query = array('description' => array('$regex' => $this->searchQuery));
        $searchResuls = $this->mongo->getGroceriesList($query);
        //$searchResuls = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($searchResuls));

        if (empty($searchResuls)) {
            $searchResuls = "No search results  for <b>" . $this->searchQuery . "</b>";
        }

        return $searchResuls;
    }

    public function savingsEstimator($price1, $price2, $add_sign = TRUE) {
        $price1 = str_replace('$', '', $price1);
        $price2 = str_replace('$', '', $price2);
        $percentmark = "";

        if (!is_numeric($price1)) {
            return array('error', 'Price 1 should be numeric');
        } else if (!is_numeric($price2)) {
            return array('error', 'Price 2 should be numeric');
        } else {
            if ($add_sign) {
                $percentmark = "%";
            }
            return (sprintf('%0.2f', (((float) $price1 - (float) $price2) / (float) $price1) * 100)) . $percentmark;
        }
    }

}
