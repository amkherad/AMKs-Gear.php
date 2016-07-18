<?php
//$MVC_LICENSE$
//$CODESECTION_BEGIN$
class Debug{
    private static function _dumpArray($arr,$indent){
        $size=sizeof($arr);
        echo"<span style=\"color:orange;\">Array($size)</span> => [";
        foreach($arr as $k=>$e){
            echo'<br>';
            for($i=0;$i<$indent;$i++)echo"----";
            echo"<span style=\"color:green;\">'$k'</span> : ";
            if(is_array($e))self::_dumpArray($e,$indent+1);else var_dump($e);
            echo' ,<br>';
        }
        for($i=0;$i<$indent;$i++)echo"----";
        echo']';
    }
	public static function show($var){if(is_array($var))self::_dumpArray($var,1);else var_dump($var);}
	public static function alert($str){echo '<script type="text/javascript">alert(\''.strval($str).'\')</script>';}
}
//$CODESECTION_END$
?>