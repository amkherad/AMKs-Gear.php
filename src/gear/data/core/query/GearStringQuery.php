<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\data\core\query;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\data\core\query\IGearQuery;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearStringQuery implements IGearQuery
{
    /** @var string */
    private
        $query
    ;

    /**
     * GearStringQuery constructor.
     * @param $query string
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->query;
    }
}
/*</module>*/
?>