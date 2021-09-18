<?php

namespace aki\vue;

use Yii;
use yii\helpers\Json;

Yii::setAlias('@vueroot', dirname(__FILE__));
/**
 * @author akbar joudi <akbar.joody@gmail.com>
 */
class Vue extends \yii\base\Widget
{
    public $jsName = 'app';

    /**
     * @see aki/vue/VueRouter
     */
    public $vueRouter;

    /**
     *
     * @var Array
     */
    public $data;

    /**
     *   template
     */
    public $templete;

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


    public $use;

    /**
     *
     * @var \yii\web\JsExpression
     */
    public $sockets;

    /**
     * @var boolean
     */
    public $useAxios = false;


    public function init()
    {
        $this->view->registerAssetBundle(VueAsset::class);
        if ($this->useAxios) {
            $this->view->registerAssetBundle(AxiosAsset::class);
        }

        if ($this->vueRouter) {
            $this->view->registerAssetBundle(VueRouterAsset::class);
        }
        $this->view->registerCss("
            [v-cloak] {
                display:none;
            }
        ");
    }

    public static function begin($config = array())
    {
        $obj =  parent::begin($config);
        echo '<div v-cloak id="' . $obj->id . '">';
        return $obj;
    }


    public static function end()
    {
        echo '</div>';
        return parent::end();
    }

    public function run()
    {
        return $this->renderVuejs();
    }

    public function renderVuejs()
    {
        
        $data = $this->generateData();
        $methods = $this->generateMethods();
        $watch = $this->generateWatch();
        $computed = $this->generateComputed();
        $components = $this->generateComponents();
        $sockets = $this->generateSockets();
        $el = $this->id;

        $use  = $this->initUse();
        
        $js = " 
            var $el = {
                data : " . (!empty($data) ? $data : "''") . "
            };            

            " . (($this->vueRouter) ? $this->vueRouter : null) . "
            $use
            var ".$this->jsName.$el." = new Vue({
                el: '#" . $el . "',
                " . (($this->vueRouter) ? "router," : null) . "
                " . (!empty($this->template) ? "template :'" . $this->template . "'," : null) . "
                " . (!empty($components) ? "components :" . $components . "," : null) . "
                " . (!empty($data) ? "data :".$el.".data," : null) . "
                " . (!empty($methods) ? "methods :" . $methods . "," : null) . "
                " . (!empty($watch) ? "watch :" . $watch . "," : null) . "
                " . (!empty($computed) ? "computed :" . $computed . "," : null) . "
                " . (!empty($this->beforeCreate) ? "beforeCreate :" . $this->beforeCreate->expression . "," : null) . "
                " . (!empty($this->created) ? "created :" . $this->created->expression . "," : null) . "
                " . (!empty($this->beforeMount) ? "beforeMount :" . $this->beforeMount->expression . "," : null) . "
                " . (!empty($this->mounted) ? "mounted :" . $this->mounted->expression . "," : null) . "
                " . (!empty($this->beforeUpdate) ? "beforeUpdate :" . $this->beforeUpdate->expression . "," : null) . "
                " . (!empty($this->updated) ? "updated :" . $this->updated->expression . "," : null) . "
                " . (!empty($this->beforeDestroy) ? "beforeDestroy :" . $this->beforeDestroy->expression . "," : null) . "
                " . (!empty($this->destroyed) ? "destroyed :" . $this->destroyed->expression . "," : null) . "
                " . (!empty($this->activated) ? "activated :" . $this->activated->expression . "," : null) . "
                " . (!empty($this->deactivated) ? "deactivated :" . $this->deactivated->expression . "," : null) . "
                " . (!empty($this->sockets) ? "sockets :" . $sockets . "," : null) . "
            }); 
        ";
        Yii::$app->view->registerJs($js, \yii\web\View::POS_END);
    }

    public function generateData()
    {
        if (!empty($this->data)) {
            return json_encode($this->data);
        }
    }

    public function generateMethods()
    {
        if (is_array($this->methods) && !empty($this->methods)) {
            $str = '';
            foreach ($this->methods as $key => $value) {
                if ($value instanceof \yii\web\JsExpression) {
                    $str .= $key . ":" . $value->expression . ',';
                }
            }
            $str = rtrim($str, ',');
            return "{" . $str . "}";
        }
    }


    public function generateWatch()
    {
        if (is_array($this->watch) && !empty($this->watch)) {
            $str = '';
            foreach ($this->watch as $key => $value) {
                if ($value instanceof \yii\web\JsExpression) {
                    $str .= $key . ":" . $value->expression . ',';
                }
                else{
                    $str .= $key . ":" . $value.',';
                }
            }
            $str = rtrim($str, ',');
            return "{" . $str . "}";
        }
    }

    public function generateComputed()
    {
        if (is_array($this->computed) && !empty($this->computed)) {
            $str = '';
            foreach ($this->computed as $key => $value) {
                if ($value instanceof \yii\web\JsExpression) {
                    $str .= $key . ":" . $value->expression . ',';
                }
            }
            $str = rtrim($str, ',');
            return "{" . $str . "}";
        }
    }

    public function generateComponents()
    {
        if (!empty($this->components)) {
            $components = '';
            for ($i = 0; $i < count($this->components); $i++) {
                $component =  new VueComponent($this->components[$i]);
                $components .= $component . ',';
            }
            return substr($components, 0, strlen($components) - 1);
        }
        return;
    }

    public function component($tagName, $option)
    {
        $option = json_encode($option);
        $this->view->registerJs("
            Vue.component($tagName, $option);
            ");
    }

    public function generateSockets()
    {
        if (is_array($this->sockets) && !empty($this->sockets)) {
            $str = '';
            foreach ($this->sockets as $key => $value) {
                if ($value instanceof \yii\web\JsExpression) {
                    $str .= $key . ":" . $value->expression . ',';
                }
            }
            $str = rtrim($str, ',');
            return "{" . $str . "}";
        }
    }


    public function initUse()
    {
        $use = "";
        if ($this->use) {
            foreach ($this->use as $item) {
                $use .= "{$item->expression}";
            }
        }
        return $use;
    }
}
