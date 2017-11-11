vuejs
=====
for yii2 web application 

Installation
------------

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


Usage
----- 

Once the extension is installed, simply use it in your code by  :

```php
<?php
use aki\vue\Vue;
?>
<?php Vue::begin([
    'id' => "vue-app",
    'data' => [
        'userModel' => User::findOne(Yii::$app->user->id),
        'message' => "hello",
        'seen' => false,
        'todos' => [
            ['text' => "aa"],
            ['text' => "akbar"]
        ]
    ],
    'methods' => [
        'reverseMessage' => new yii\web\JsExpression("function(){"
                . "this.message =1; "
                . "}"),
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
