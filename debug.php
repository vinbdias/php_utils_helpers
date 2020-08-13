<?php 

abstract class DebugHelper {

    static function debug( $mix, $stop = false )
    {
        echo '<pre style="font-size:medium; background-color: #FF9; text-align:left;">';
        if( is_array($mix) )
            var_dump($mix);
        else
            var_dump(array($mix));
        echo '</pre>';
        
        if( $stop ) exit();
    }

    static function debugJsConsole($var) {
        echo '<script>console.log(' . json_encode($var) . ')</script>';
    }
}