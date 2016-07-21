<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\helpers;
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearPath
{
    private static function _combine($path1, $path2)
    {
        $path1 = strval($path1);
        $path2 = strval($path2);
        $sep = substr($path1, strlen($path1) - 1);
        $retval = ($sep == '/' || $sep == '\\')
            ? $path1 : $path1 . '/';
        $sep = substr($path2, 0, 1);
        $retval .= ($sep == '/' || $sep == '\\')
            ? substr($path2, 1) : $path2;
        return $retval;
    }

    public static function Combine($path1)
    {
        if (is_array($path1)) {
            $retval = '';
            $first = true;
            foreach ($path1 as $path) {
                if ($path == null) continue;
                $retval = strval($first)
                    ? $path : self::_combine($retval, $path);
                $first = false;
            }
            return $retval;
        } else {
            $retval = strval($path1);
            $fv = func_num_args();
            for ($i = 1; $i < $fv; $i++) {
                $retval = self::_combine($retval, func_get_arg($i));
            }
            return $retval;
        }
    }

    public static function GetUseablePath($p)
    {
        $f = substr($p, 0, 1);
        if ($f == '/' || $f == '\\') {
            $p = substr($p, 1);
        }
        return $p;
    }

    public static function GetExtension($p)
    {
        $d = strrpos($p, '.');
        if (!is_bool($d) && $d >= 0) {
            return ($d < strlen($p) - 1 ? substr($p, $d + 1) : '');
        }
        return null;
    }
}
/*</module>*/
?>