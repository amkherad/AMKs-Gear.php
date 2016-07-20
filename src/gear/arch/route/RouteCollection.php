<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\route;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\InvalidOperationException;
use gear\arch\helpers\Path;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class RouteCollection implements \ArrayAccess
{
    const
        Optional = 'optional',
        Params = 'params';

    public $MapedRoutes = array();
    public $IgnoredRoutes = array();

    public function MapRoute($name, $url, $defaults = null, $constraints = null)
    {
        $p = explode('/', $url);
        $size = sizeof($p);
        $min = $size;
        $max = $size;

        $r = array('name' => $name, 'url' => $url);

        $escaped = array();
        foreach ($p as $s) {
            if ($s == null) continue;
            preg_match('/(?:{)(.+)(?:})/', $s, $match);
            $escaped[] = $match[1];
        }
        $r['sections'] = $escaped;

        $r['params'] = null;
        $paramsFound = false;

        $defaultsRemover = array();
        if (is_array($defaults)) foreach ($defaults as $k => &$d) {
            foreach ($escaped as $escapedEl) if ($escapedEl == $k) $min--;
            if (is_array($d)) {
                foreach ($d as $element) {
                    $removeFromDefaults = false;
                    if ($element == self::Optional) {
                        $min--;
                        $removeFromDefaults = true;
                    } elseif ($element == self::Params) {
                        if ($paramsFound) throw new InvalidOperationException();
                        $min--;
                        $max = 9999999;
                        $d['name'] = $k;
                        $r['params'] = $d;
                        $paramsFound = true;
                        $removeFromDefaults = true;
                    }
                    if ($removeFromDefaults && array_search($k, $escaped) === false) {
                        $defaultsRemover[] = $k;
                        continue;
                    }
                }
            }
        }
        if ($min < 0) $min = 0;
        //foreach($defaultsRemover as $dr)unset($defaults[$dr]);

        $r['defaults'] = $defaults;
        $r['constraints'] = $constraints;

        $r['urlMinParts'] = $min;
        $r['urlMaxParts'] = $max;

        $this->MapedRoutes[$name] = $r;;
    }

    public function IgnoreRoute($url, $constraints = null)
    {
        array_push($this->IgnoredRoutes, array('url' => $url, 'constraints' => $constraints));
    }

    private function _doesMatch($arr, $p, $path)
    {
        $count = sizeof($p);
        if ($count >= $arr['urlMinParts'] && $count <= $arr['urlMaxParts']) {
            return true;
        }
        return false;
    }

    public function GetRoute($path)
    {
        $p = array();
        foreach (explode('/', $path) as $e) if ($e != '') $p[] = $e;
        $size = sizeof($p);
        foreach ($this->MapedRoutes as $r) {
            if ($this->_doesMatch($r, $p, $path)) {
                $result = array();
                $rSections = $r['sections'];
                $rDefaults = $r['defaults'];
                $rParams = $r['params'];
                $removes = array();
                for ($i = 0; $i < $size; $i++) {
                    $name = isset($rSections[$i]) ? $rSections[$i] : $rParams['name'];
                    if ($name != $rParams['name'])
                        $result[$name] = isset($p[$i]) ? $p[$i] : $rDefaults[$i];
                    else {
                        $removes[$name] = true;
                        $result/*[$name]*/
                        [] = isset($p[$i]) ? $p[$i] : $rDefaults[$i];
                    }
                }
                foreach ($rDefaults as $k => $def) {
                    if (!isset($result[$k]) && !isset($removes[$k]))
                        $result[$k] = $def;
                }
                return $result;
            }
        }
        return null;
    }

    public function GetVirtualPath($context, $p)
    {//  controller - action - arg1
        $path = implode('/', $p);
        //foreach($this->MapedRoutes as $r){
        //    if($this->_doesMatch($r,$p,$path)){
        //        return Path::Combine(Uri::GetRoot(),$path);
        //    }
        //}
        return Path::Combine(Uri::GetRoot(), $path);
    }

    public function __set($k, $val)
    {
        throw new InvalidOperationException("");
    }

    public function __get($k)
    {
        return null;
    }

    public function __isset($k)
    {
        return false;
    }

    public function __unset($k)
    {
    }

    public function offsetExists($o)
    {
        return false;
    }

    public function offsetGet($o)
    {
        return null;
    }

    public function offsetSet($o, $val)
    {
        throw new InvalidOperationException("");
    }

    public function offsetUnset($o)
    {
    }
}

/*</module>*/
?>