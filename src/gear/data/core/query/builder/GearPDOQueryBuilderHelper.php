<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\data\core\query\builder;
/*</namespace.current>*/
/*<namespace.use>*/
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearPdoQueryBuilderHelper implements IGearQueryBuilderHelper
{
    /** @var \PDO */
    private $pdo;

    /**
     * GearPdoQueryBuilderHelper constructor.
     * @param $pdo \PDO
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function escapeValue($value)
    {
        return $this->pdo->quote($value);
    }
}
/*</module>*/
?>