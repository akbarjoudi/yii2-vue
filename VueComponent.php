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



    public function exec()
    {
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
        return " { template: '" . $output . "' }";
    }
}
