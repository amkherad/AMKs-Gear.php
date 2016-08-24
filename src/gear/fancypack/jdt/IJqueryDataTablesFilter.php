<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\jdt;
/*</namespace.current>*/
/*<namespace.use>*/
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IJqueryDataTablesFilter
{
    /**
     * @return int
     */
    function getDraw();
    /**
     * @param int $draw
     * @return void
     */
    function setDraw($draw);

    /**
     * @return int|null
     */
    function getStart();
    /**
     * @param int|null $start
     * @return void
     */
    function setStart($start);

    /**
     * @return int|null
     */
    function getLength();
    /**
     * @param int|null $length
     * @return void
     */
    function setLength($length);

    /**
     * @return string
     */
    function getGeneralFilter();
    /**
     * @param string $generalFilter
     * @return void
     */
    function setGeneralFilter($generalFilter);
    /**
     * Returns string compare mode.
     *
     * @return string
     */
    function getCompareMode();
    /**
     * @param string $compareMode
     * @return void
     */
    function setCompareMode($compareMode);

    /**
     * @return array
     */
    function getColumns();
    /**
     * @param array $columns
     * @return void
     */
    function setColumns($columns);

    /**
     * @return mixed
     */
    function getExtendedOptions();
    /**
     * @param mixed $exOptions
     * @return void
     */
    function setExtendedOptions($exOptions);
}
/*</module>*/
?>