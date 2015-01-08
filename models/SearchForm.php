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
    private function highlightSearch($result, $query=array()){
        if ($result && !is_string($result)) { 
            foreach ($result as $key => &$value) {
                foreach ($query as $word){
                $value['description']=  str_replace($word, '<strong>'.$word.'</strong>', $value['description']);
                }
            }
        }
        return $result;
        
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

    function pc_permute($items, $perms = array()) {
        if (empty($items)) {
            $return = array($perms);
        } else {
            $return = array();
            for ($i = count($items) - 1; $i >= 0; --$i) {
                $newitems = $items;
                $newperms = $perms;
                list($foo) = array_splice($newitems, $i, 1);
                array_unshift($newperms, $foo);
                $return = array_merge($return, $this->pc_permute($newitems, $newperms));
            }
        }
        return $return;
    }

    private function doSearch() {
        $trim_query = trim($this->searchQuery);
        //remove all blank spaces
        $user_query = preg_replace("/[[:blank:]]+/", " ", $trim_query);
        //shrink by spaces and create array of words
        //we can try to make first call as is e.g, add synonims etc, but for now just word by word
        $arr_query = explode(' ', $user_query);
        $counter = 0;
        $v_counter = 0;
        // echo "<pre>" . print_r($arr_query, TRUE) . "</pre>";
        $counter = count($arr_query);

        //here there is a limit of 5 query words or 120 combinations (could be increased to 6, not more!)
        if ($counter > 1 && $counter < 6) {

            $query1 = array(
                '$or' => array()
            );



            $set = $this->pc_permute($arr_query);
            $a = 0;
            $s_counter = count($set); //this is counter of all possible combination if word 5 - 120 combintaions , 6 - 720 (search up to 4 sec)
            // in theory $s_counter should be the same as $counter 
            foreach ($set as $k => $v) {
                $v_counter = count($v); //0, 1, 2, 3, 4, 5 ...this is counter of words (I would take 5 max)

                $i = 0;
                foreach ($v as $k1 => $v1) {
                    for ($v_c_var = 0; $v_c_var < $v_counter; $v_c_var++) {
                        if ($i == $v_c_var) {
                            for ($var = 0; $var < $s_counter; $var++) {
                                ${'subquery' . $var}[$k1]['description'] = array('$regex' => $v1, '$options' => 'i');
                            }
                        }
                    }

                    $i++;
                }
                for ($a_c_var = 0; $a_c_var < $s_counter; $a_c_var++) {

                    if ($a == $a_c_var) {

                        $query1['$or'][$a_c_var] = array('$and' => ${'subquery' . $a_c_var});
                    }
                }

                $a++;
            }
            
        } else {


            $query1 = array(
                '$or' => array(
                    array('description' => array('$regex' => $user_query, '$options' => 'i')),
                )
            );


            // ref http://stackoverflow.com/questions/14023821/php-mongodb-or-regex-search 
        }

   
        $searchResuls = $this->mongo->getGroceriesList($query1);
        $searchResuls=$this->highlightSearch($searchResuls, $arr_query);
        //echo "<pre>".print_r($searchResuls, TRUE)."</pre>";
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
