<?php
namespace aki\vue;

/**
 * Description of VueAsset
 *
 * @author akbar joudi <akbar.joody@gmail.com>
 */
class VueAsset extends \yii\web\AssetBundle{
    public $sourcePath = '@bower/vue/dist';
    
    public $js = [
    ];
    
    public function init()
    {
        $this->js[] = YII_ENV_DEV ? 'vue.js' : 'vue.min.js';
    }
}
