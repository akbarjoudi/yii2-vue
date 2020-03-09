<?php

namespace aki\vue;

use Yii;
use yii\base\Component;


class VueComponent extends Component
{
    
    /**
     * 
     */
    public $template;

    /**
     * 
     */
    public $name;

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

    /**
     *
     * @var \yii\web\JsExpression
     */
    public $beforeCreate;

    /**
     *
     * @var \yii\web\JsExpression
     */
    public $created;

    /**
     *
     * @var \yii\web\JsExpression
     */
    public $beforeMount;

    /**
     *
     * @var \yii\web\JsExpression
     */
    public $mounted;

    /**
     *
     * @var \yii\web\JsExpression
     */
    public $beforeUpdate;

    /**
     *
     * @var \yii\web\JsExpression
     */
    public $updated;

    /**
     *
     * @var \yii\web\JsExpression
     */
    public $activated;

    /**
     *
     * @var \yii\web\JsExpression
     */
    public $deactivated;

    /**
     *
     * @var \yii\web\JsExpression
     */
    public $beforeDestroy;

    /**
     *
     * @var \yii\web\JsExpression
     */
    public $destroyed;

    /**
     * @var Array
     */
    public $components;

    /**
     * @var Array
     */
    public $props;
    

    public function exec()
    {
        $data = $this->generateData();
        $methods = $this->generateMethods();
        $watch = $this->generateWatch();
        $computed = $this->generateComputed();
        $components = $this->generateComponents();
        $props = $this->generateProps();

        $output = '';
        $path = Yii::getAlias($this->template);
        if (is_dir(dirname($path))) {
            ob_start();
            include $path;
            $output = ob_get_contents();
            ob_end_clean();

            $output = str_replace(array("\r\n", "\r"), "\n", $output);
            $lines = explode("\n", $output);
            $new_lines = array();

            foreach ($lines as $i => $line) {
                if (!empty($line))
                    $new_lines[] = trim($line);
            }
            $output = implode($new_lines);
        } else {
            $output = $this->template;
        }

        // if()
        return " { 
            template: '" . $output . "',
            ".(!empty($data) ? "data: function () {
                return {
                  ".$data."
                }
              },":null)."
            ".(!empty($methods) ? "methods :".$methods.",":null)."
            ".(!empty($watch) ? "watch :".$watch.",":null)."
            ".(!empty($computed) ? "computed :".$computed.",":null)."
            ".(!empty($components) ? "components :{".$components."},":null)."
            ".(!empty($props) ? "props :[".$props."]":null)."
        }";
    }

    public function generateProps() {
        if(!empty($this->props)){
            $str ='';
            foreach ($this->props as $key => $value) {
                $str .= "'".$value."',";
            }
            $str = substr($str, 0, strlen($str)-1);
            return $str;
        }
    }
    public function generateData() {
        if(!empty($this->data)){
            $str ='';
            foreach ($this->data as $key => $value) {
                if(is_numeric($value)){
                    $str .= $key.':'.$value.',';
                }
                else{
                    $str .= $key.':"'.$value.'",';
                }
                
            }
            $str = substr($str, 0, strlen($str)-1);
            return $str;
        }
    }
  
    public function generateMethods() {
        if(is_array($this->methods) && !empty($this->methods)){
            $str = '';
            foreach ($this->methods as $key => $value) {
                if($value instanceof \yii\web\JsExpression){
                    $str.= $key.":".$value->expression.',';
                }
            }
            $str = rtrim($str,',');
            return "{".$str."}";
        }
    }
    
    
    public function generateWatch() {
        if(is_array($this->watch) && !empty($this->watch)){
            $str = '';
            foreach ($this->watch as $key => $value) {
                if($value instanceof \yii\web\JsExpression){
                    $str.= $key.":".$value->expression.',';
                }
            }
            $str = rtrim($str,',');
            return "{".$str."}";
        }
    }
    
    public function generateComputed() {
        if(is_array($this->computed) && !empty($this->computed)){
            $str = '';
            foreach ($this->computed as $key => $value) {
                if($value instanceof \yii\web\JsExpression){
                    $str.= $key.":".$value->expression.',';
                }
            }
            $str = rtrim($str,',');
            return "{".$str."}";
        }
    }

    public function generateComponents() {
        if(!empty($this->components))
        {
            // die(var_dump(($this->components)));
            $components='';
            foreach ($this->components as $key => $value) {
                
                if(is_object($value)){
                    
                    $component =  "'".$key."':".($value)->exec();
                   
                }
                else{
                     $component =  "'".$key."':".new VueComponent($value);
                }
               
                $components .= $component.',';
            }
            return substr($components, 0, strlen($components)-1);
        }
        return;
    }
}
