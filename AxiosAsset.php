<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace aki\vue;

/**
 * Description of VueAsset
 *
 * @author user
 */
class AxiosAsset extends \kartik\base\AssetBundle{
    public $sourcePath = '@bower/vue/dist';
    
    public $js = [
        'lodash.min.js',
    ];
}
