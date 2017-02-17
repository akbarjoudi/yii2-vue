<?php

namespace aki\vue;

use Yii;
/**
 * This is just an example.
 */
class Vue extends \yii\base\Widget
{
    /**
     *
     * @var Array
     */
    public $data;
    
    /**
     * [
     *  reverseMessage: function () {
     *       this.message = this.message.split('').reverse().join('')
     *     }
     * ]
     * @var Array
     */
    public $methods;


    public function init() {
        $this->view->registerAssetBundle(VueAsset::className());
        //generate data
//        $data = $this->generateData();
        //generate method
//        $methods = $this->generateMethods();
        
       
        
    }
    
    public static function begin($config = array()) {
        $obj =  parent::begin($config);
        
        $el = $obj->id;
        $data = $obj->generateData();
        $methods = $obj->generateMethods();
        $js = "
            var app = new Vue({
                el: '#".$el."',
                ".(!empty($data) ? "data :".$data:null).",
                ".(!empty($methods) ? "methods :".$methods:null).",
            }); 
        ";
        Yii::$app->view->registerJs($js, \yii\web\View::POS_END);
        echo '<div id="'.$obj->id.'">';
        return $obj;
    }
    
    
    public static function end() {
        echo '</div>';
        return parent::end();
    }
    
    
    
    public function generateData() {
        if(!empty($this->data)){
            return json_encode($this->data);
        }
    }
  
    public function generateMethods() {
        if(!empty($this->methods)){
            $str = '';
            foreach ($this->methods as $key => $value) {
                $str.= $key.":".$value->expression;
            }
            return "{".$str."}";
        }
    }
    
}
