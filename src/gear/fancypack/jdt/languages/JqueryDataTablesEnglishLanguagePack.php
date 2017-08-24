<?php
//$SOURCE_LICENSE$

/*<requires>*/
//JqueryDataTablesLanguagePack
/*</requires>*/

/*<namespace.current>*/
namespace gear\fancypack\jdt\languages;
/*</namespace.current>*/
/*<namespace.use>*/
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class JqueryDataTablesEnglishLanguagePack extends JqueryDataTablesLanguagePack
{
    public function __construct()
    {
        $this->emptyTable = "No data available in table";
        $this->info = "Showing _START_ to _END_ of _TOTAL_ entries";
        $this->infoEmpty = "Showing 0 to 0 of 0 entries";
        $this->infoFiltered = "(filtered from _MAX_ total entries)";
        $this->infoPostFix = "";
        $this->infoThousands = ",";
        $this->lengthMenu = "Show _MENU_ entries";
        $this->loadingRecords = "Loading...";
        $this->processing = "Processing...";
        $this->search = "Search:";
        $this->zeroRecords = "No matching records found";

        $paginate = new JqueryDataTablesPaginateLanguagePack();
        $this->paginate = $paginate;
        $paginate->first = "First";
        $paginate->previous = "Previous";
        $paginate->next = "Next";
        $paginate->last = "Last";

        $aria = new JqueryDataTablesAriaLanguagePack();
        $this->aria = $aria;
        $aria->sortAscending = ": activate to sort column ascending";
        $aria->sortDescending = ": activate to sort column descending";

        $this->url = "Url";
    }
}
/*</module>*/
?>