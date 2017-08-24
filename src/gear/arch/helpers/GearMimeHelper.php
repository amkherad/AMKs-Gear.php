<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\helpers;
/*</namespace.current>*/
/*<namespace.use>*/
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearMimeHelper
{
    public static $mimes = [
        'image' => [
            'jpg', 'jpeg', 'png', 'tiff', 'tif', 'gif', 'bmp', 'jpe', 'dib', 'jfif'
        ],
        'text' => [
            'css', 'html', 'xhtml', 'htm', 'mht',
            [
                'plain' => 'txt'
            ]
        ],
        'application' => [
            'js', 'xml'
        ]
    ];

    public static function getMimeFromExtension($ext)
    {
        $ext = strtolower(GearPath::GetExtension($ext));
        foreach (self::$mimes as $media => $mimes) {
            foreach ($mimes as $key => $value) {
                if ($value == $ext) {
                    if (is_numeric($key)) {
                        return $media.'/'.$value;
                    } else {
                        return $media.'/'.$key;
                    }
                }
            }
        }
        return null;
    }
}
/*</module>*/
?>