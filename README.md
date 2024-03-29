# vuejs

for yii2 web application

<p align="center">
    
[![Latest Stable Version](https://poser.pugx.org/aki/yii2-vue/v/stable)](https://packagist.org/packages/aki/yii2-vue)   
[![Total Downloads](https://poser.pugx.org/aki/yii2-vue/downloads)](https://packagist.org/packages/aki/yii2-vue)

</p>

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require aki/yii2-vue "*"
```

or add

```
"aki/yii2-vue": "*"
```

to the require section of your `composer.json` file.

## After installing the extension, first install the vuejs framework

Either run
```
# Move to folder
cd ./vendor/aki/yii2-vue/
```
and run command

```
# install deps
npm install
```

Usage

---

Once the extension is installed, simply use it in your code by :

```php
<?php
use aki\vue\Vue;
?>
<?php Vue::begin([
    'id' => "vue-app",
    'data' => [
        'message' => "hello world!!",
        'seen' => true,
        'todos' => [
            ['text' => "akbar joudi"],
            ['text' => "aref mohhamdzadeh"]
        ]
    ],
    'methods' => [
        'reverseMessage' => new yii\web\JsExpression("function(){"
                . "this.message = this.message.split('').reverse().join(''); "
                . "}"),
    ],
    'watch' => [
        'message' => new JsExpression('function(newval, oldval){
            console.log(newval)
        }'),
    ]
]); ?>

    <p>{{ message }}</p>
    <button v-on:click="reverseMessage">Reverse Message</button>

    <p v-if="seen">Now you see me</p>


    <ol>
        <li v-for="todo in todos">
          {{ todo.text }}
        </li>
    </ol>

    <p>{{ message }}</p>
    <input v-model="message">


<?php Vue::end(); ?>
```

Add Vue Router

---

simply use it in your code by :

```php
<?php
use aki\vue\Vue;
use aki\vue\VueRouter;
use aki\vue\VueComponent;
?>
<?php Vue::begin([
    'id' => "vue-app",
    'vueRouter'=> VueRouter::widget([
        'routes' => [
            [
                'path' => '/foo',
                'component' => new VueComponent([
                    'template' => '@vueroot/views/foo.php',
                    'components' => [
                        'yoo-component'=> new VueComponent([
                            'template' => '<div>yoo!!! {{id}}</div>',
                                'props' => [
                                    'id'
                                ]
                            ])
                        ],
                ])
            ],
            [
                'path' => '/bar',
                'component' => new VueComponent([
                    'template' => '<div>hello</div>'
                ])
            ]
        ]
    ])
]); ?>

    <router-link to="/foo">Go to Foo</router-link>
    <router-link to="/bar">Go to Bar</router-link>

    <!-- route outlet -->
    <?= VueRouter::$outlet ?><!-- <router-view></router-view> -->
<?php Vue::end(); ?>
```
