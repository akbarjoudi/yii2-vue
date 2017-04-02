<?php
namespace aki\vue;

use Yii;
/**
 * @author akbar joudi <akbar.joody@gmail.com>
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
    
    /**
     *
     * @var Array 
     */ 
    public $watch;
    
    /**
     *
     * @var Array
     */
    public $computed;

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
        $computed = $this->generateComputed();
        $el = $this->id;
        $js = "
            var app = new Vue({
                el: '#".$el."',
                ".(!empty($data) ? "data :".$data.",":null)."
                ".(!empty($methods) ? "methods :".$methods."," :null)."
                ".(!empty($watch) ? "watch :".$watch."," :null)."
                ".(!empty($computed) ? "computed :".$computed."," :null)."
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
        if(is_array($this->methods) && !empty($this->methods)){
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
        if(is_array($this->watch) && !empty($this->watch)){
            $str = '';
            foreach ($this->watch as $key => $value) {
                if($value instanceof \yii\web\JsExpression){
                    $str.= $key.":".$value->expression;
                }
            }
            return "{".$str."}";
        }
    }
    
    public function generateComputed() {
        if(is_array($this->computed) && !empty($this->computed)){
            $str = '';
            foreach ($this->computed as $key => $value) {
                if($value instanceof \yii\web\JsExpression){
                    $str.= $key.":".$value->expression;
                }
            }
            return "{".$str."}";
        }
    }
    
    public function component($tagName, $option) {
        $option = json_encode($option);
        $this->view->registerJs("
            Vue.component($tagName, $option);
            ");
    }
}
