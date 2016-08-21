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
class JqueryDataTablesParsiLanguagePack extends JqueryDataTablesLanguagePack
{
    public function __construct()
    {
        $this->emptyTable = "هیچ داده ای موجود نمیباشد";
        $this->info = "نمایش _START_ تا _END_ از مجموع _TOTAL_ مورد";
        $this->infoEmpty = "هیچ رکوردی موجود نمیباشد";
        $this->infoFiltered = "(نمایش رکوردها از _MAX_ رکورد)";
        $this->infoPostFix = "";
        $this->infoThousands = ",";
        $this->lengthMenu = "نمایش _MENU_ رکورد در هر صفحه";
        $this->loadingRecords = "Loading...";
        $this->processing = "درحال پردازش...";
        $this->search = "جستجو:";
        $this->zeroRecords = "موردی یافت نشد";

        $paginate = new JqueryDataTablesPaginateLanguagePack();
        $this->paginate = $paginate;
        $paginate->first = "ابتدا";
        $paginate->previous = "قبلی";
        $paginate->next = "بعدی";
        $paginate->last = "انتها";

        $aria = new JqueryDataTablesAriaLanguagePack();
        $this->aria = $aria;
        $aria->sortAscending = ": برای مرتب سازی سعودی";
        $aria->sortDescending = ": برای مرتب سازی نزولی";

        $this->url = "Url";
    }
}
/*</module>*/
?>