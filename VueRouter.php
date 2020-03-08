<?php
namespace aki\vue;

use Yii;
use yii\base\Event;

Yii::setAlias('@vueroot', dirname(__FILE__));
/**
 * @author akbar joudi <akbar.joody@gmail.com>
 */
class VueRouter extends \yii\base\Widget
{
    public static $outlet = "<router-view></router-view>";
    /**
     * 
     */
    public $routes = [];



    /**
     * 
     */
    public function run()
    {
        // die(var_dump(($this->routes[0]['component']->exec())));
        $str = "
            const routes = [
              ";
        
        for($i=0; $i<count($this->routes); $i++)
        {
            $str.= "{path:'".$this->routes[$i]['path']."', component:".$this->routes[$i]['component']->exec()."},";
        }
        $str = substr($str, 0, strlen($str)-1);
        $str .="  
            ]
        ";
        $routerstr = $str."
        const router = new VueRouter({
            routes
        })
        ";
        return $routerstr;
    }


}
