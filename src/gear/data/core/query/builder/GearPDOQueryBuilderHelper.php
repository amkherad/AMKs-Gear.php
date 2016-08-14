<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\data\core\query\builder;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\data\core\query\builder\IGearQueryBuilderHelper;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearPDOQueryBuilderHelper implements IGearQueryBuilderHelper
{
    /** @var \PDO */
    private $pdo;

    /**
     * GearPDOQueryBuilderHelper constructor.
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