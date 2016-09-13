<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\jdt\element;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\io\IGearOutputStream;
use gear\fancypack\core\elements\GearHtmlElement;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class JdtTableElement extends GearHtmlElement
{
    private
        $columns,
        $renderHeader,
        $renderFooter;

    public function __construct($id, $columns, $renderHeader = true, $renderFooter = true)
    {
        $this->setAttribute('id', $id);

        $this->columns = $columns;
        $this->renderHeader = $renderHeader;
        $this->renderFooter = $renderFooter;
    }

    /**
     * @param IGearOutputStream $stream
     * @return void
     */
    public function renderToStream($stream)
    {
        $thead = '<thead><tr>';
        $tfoot = '<tfoot><tr>';
        $tbody = '<tbody><tr>';
        foreach($this->columns as $column) {
            $thead .= "<th>{$column->title}</th>";
            $tfoot .= "<td>{$column->title}</td>";
            $tbody .= "<td></td>";
        }
        $thead .= '</tr></thead>';
        $tfoot .= '</tr></tfoot>';
        $tbody .= '</tr></tbody>';

        $stream->write('<table');
        $stream->write($this->getSerializedAttributes());
        $stream->write('>');
        $stream->write($thead);
        $stream->write($tbody);
        $stream->write($tfoot);
        $stream->write('</table>');
    }
}
/*</module>*/
?>