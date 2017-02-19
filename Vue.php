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
     * 'methods' => [
     *  'reverseMessage' => new yii\web\JsExpression("function(){"
     *      . "this.message =1; "
     *      . "}"),
     *  ]
     * @var Array
     */
    public $methods;
    
    
    public $watch;


    public function init() {
        $this->view->registerAssetBundle(VueAsset::className());
        $this->view->registerAssetBundle(AxiosAsset::className());
    }
    
    public static function begin($config = array()) {
        $obj =  parent::begin($config);
        echo '<div id="'.$obj->id.'">';
        return $obj;
    }
    
    
    public static function end() {
        echo '</div>';
        return parent::end();
    }
    
    public function run() {
        return $this->renderVuejs();       
    }
    
    public function renderVuejs() {
        $data = $this->generateData();
        $methods = $this->generateMethods();
        $watch = $this->generateWatch();
        $el = $this->id;
        $js = "
            var app = new Vue({
                el: '#".$el."',
                ".(!empty($data) ? "data :".$data.",":null)."
                ".(!empty($methods) ? "methods :".$methods."," :null)."
                ".(!empty($watch) ? "watch :".$watch."," :null)."
            }); 
        ";
        Yii::$app->view->registerJs($js, \yii\web\View::POS_END);
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
                if($value instanceof \yii\web\JsExpression){
                    $str.= $key.":".$value->expression;
                }
            }
            return "{".$str."}";
        }
    }
    
    
    public function generateWatch() {
        if(!empty($this->watch)){
            $str = '';
            foreach ($this->watch as $key => $value) {
                if($value instanceof \yii\web\JsExpression){
                    $str.= $key.":".$value->expression;
                }
            }
            return "{".$str."}";
        }
    }
    
    
}
