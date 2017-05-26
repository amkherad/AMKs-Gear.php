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
class GearCollectionHelpers
{
    /**
     * Performs cross join (cartezian) on array of arrays.
     * @param array $arrays
     * @return array
     */
    public static function crossJoin($arrays) {
        $results = [];

        foreach ($arrays as $group) {
            $results = self::expandItems($results, $group);
        }

        return $results;
    }

    private static function expandItems($sourceItems, $tails)
    {
        $result = [];

        if (empty($sourceItems)) {
            foreach ($tails as $tail) {
                $result[] = [$tail];
            }
            return $result;
        }

        foreach ($sourceItems as $sourceItem) {
            foreach ($tails as $tail) {
                $result[] = array_merge($sourceItem, [$tail]);
            }
        }

        return $result;
    }

    /**
     * Performs cross join (cartezian) on array of strings.
     * @param array $strings
     * @param string|null $separator
     * @param bool|false $filterNulls
     * @return array
     */
    public static function crossJoinStrings($strings, $separator = null, $filterNulls = false) {

        $strings = self::crossJoin($strings);

        $result = [];
        foreach ($strings as $strList) {
            if ($filterNulls) {
                $strList = array_filter($strList);
                if ($strList == null) continue;
            }
            $result[] = implode($separator, $strList);
        }

        return $result;
    }
}
/*</module>*/
?>