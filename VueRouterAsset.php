<?php
namespace aki\vue;

/**
 * Description of VueAsset
 *
 * @author akbar joudi <akbar.joody@gmail.com>
 */
class VueRouterAsset extends \yii\web\AssetBundle{
    public $sourcePath = '@vueroot/node_modules/vue-router/dist';
    
    public $js = [
    ];
    
    public function init()
    {
        $this->js[] = YII_ENV_DEV ? 'vue-router.js' : 'vue-router.min.js';
    }
}
