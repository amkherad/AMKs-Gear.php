<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\jdt\viewhelpers;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\io\GearHtmlString;
use gear\fancypack\jdt\JqueryDataTablesOptions;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

class JqueryDataTablesFiltererInitializer
{
    public static function renderInitializer($useApi = true, $addScriptTag = true)
    {
        $filterTypeNone = JqueryDataTablesOptions::JdtColumnFilterModeNone;
        $filterTypeList = JqueryDataTablesOptions::JdtColumnFilterModeList;
        $filterTypeText = JqueryDataTablesOptions::JdtColumnFilterModeText;
        $filterTypeBoolean = JqueryDataTablesOptions::JdtColumnFilterModeBoolean;
        $filterTypeDateTime = JqueryDataTablesOptions::JdtColumnFilterModeDateTime;

        if ($useApi) {
            $outputHtml = <<<JavaScript
    function _listDataTableFiltererInitializer(obj) {
        // Apply the search
        obj.columns().every(function(colIndx) {
            var that = this;

            var column = this.settings()[0].aoColumns[colIndx];
            var individualColumnInfo = column;
            var filterOnEnter = this.settings()[0].filterOnEnter;

            var innerHtml;
            if (column.bSearchable) {
                individualColumnInfo = $.extend({
                    filterList: null,
                    filterMode: 'none',
                    filterUseRemoteData: false,
                    filterRemoteDataUrl: 'jdtRemoteData',
                    filterRemoteDataAjaxRequestType: 'POST',
                    filterRemoteDataAjaxRequestData: 'defaultUrl',
                    filterPlaceHolder: null,
                    filterTrueDisplayName: 'صحیح',
                    filterFalseDisplayName: 'غلط',
                    filterAddNoFilter: true,
                    filterNoFilterDisplayName: '(نمایش همه)'
                }, individualColumnInfo);
                switch (individualColumnInfo.filterMode) {
                    case '{$filterTypeNone}':

                    case '{$filterTypeText}':
                        {
                            innerHtml = (individualColumnInfo.placeHolder == null
                                    ? $('<input type="text" placeholder="جستجو ' + column.title + '..." />')
                                    : $('<input type="text" placeholder="' + individualColumnInfo.placeHolder + '" />'))
                                .addClass('jdtInput textInput');

                            break;
                        }
                    case '{$filterTypeBoolean}':
                        {
                            innerHtml = $('<select></select>');
                            if (individualColumnInfo.addNoFilter) {
                                innerHtml
                                    .append($('<option></option>')
                                        .attr('value', '')
                                        .text(individualColumnInfo.noFilterDisplayName));
                            }
                            innerHtml
                                .append($('<option></option>')
                                    .attr('value', true)
                                    .text(individualColumnInfo.trueDisplayName))
                                .append($('<option></option>')
                                    .attr('value', false)
                                    .text(individualColumnInfo.falseDisplayName))
                                .addClass('jdtInput textInput');

                            break;
                        }
                    case '{$filterTypeList}':{
                        innerHtml = $('<select></select>')
                            .addClass('jdtInput selectInput');

                        if (individualColumnInfo.addNoFilter) {
                            innerHtml
                                .append($('<option></option>')
                                    .attr('value', '')
                                    .text(individualColumnInfo.noFilterDisplayName));
                        }

                        if (individualColumnInfo.useRemoteData) {
                            $.ajax({
                                url: individualColumnInfo.remoteDataUrl,
                                type: individualColumnInfo.remoteDataAjaxRequestType,
                                data: individualColumnInfo.remoteDataAjaxRequestData,
                                success: function(data) {
                                    $(data).each(function(idx, val) {
                                        innerHtml
                                            .append($('<option></option>')
                                                .attr('value', val[0])
                                                .text(val[1]));
                                    });
                                },
                                error: function(xhr, textStatus, errorThrown) {
                                    if (individualColumnInfo.filterList != null) {
                                        $(individualColumnInfo.filterList).each(function(idx, val) {
                                            innerHtml
                                                .append($('<option></option>')
                                                    .attr('value', val[0])
                                                    .text(val[1]));
                                        });
                                    }
                                }
                            });
                        } else if (individualColumnInfo.filterList != null) {
                            $(individualColumnInfo.filterList).each(function(idx, val) {
                                innerHtml
                                    .append($('<option></option>')
                                        .attr('value', val[0])
                                        .text(val[1]));
                            });
                        } else {
                            this.data().unique().sort().each(function(d, j) {
                                innerHtml.append('<option value="' + d + '">' + d + '</option>');
                            });
                        }

                        break;
                    }
                    case '{$filterTypeDateTime}':{
                        innerHtml = $('<input/>')
                            .addClass('jdtInput dateTimeInput');

                        break;
                    }
                }
            }
            if (innerHtml != null)
                innerHtml = innerHtml.appendTo($(this.footer()).empty());

            if (filterOnEnter) {
                $('.jdtInput', this.footer()).keypress(function(e) {
                    if (e.which == 13) {
                        if (that.search() !== this.value) {
                            that
                                .search(this.value)
                                .draw();
                        }
                        return false;
                    }
                });
            } else {
                $('.jdtInput', this.footer()).on('keyup change', function() {
                    if (that.search() !== this.value) {
                        that
                            .search(this.value)
                            .draw();
                    }
                });
            }
        });
    }
JavaScript;
        } else {
            $outputHtml = <<<JavaScript
    function _listDataTableFiltererInitializer(obj) {
        // Apply the search
        $.each(obj.aoColumns, function(colIndx, column) {
            var that = this;

            var individualColumnInfo = column;
            var filterOnEnter = this.settings()[0].filterOnEnter;

            var innerHtml;
            if (column.bSearchable) {
                individualColumnInfo = $.extend({
                    filterList: null,
                    filterMode: 'none',
                    filterUseRemoteData: false,
                    filterRemoteDataUrl: 'jdtRemoteData',
                    filterRemoteDataAjaxRequestType: 'POST',
                    filterRemoteDataAjaxRequestData: 'defaultUrl',
                    filterPlaceHolder: null,
                    filterTrueDisplayName: 'صحیح',
                    filterFalseDisplayName: 'غلط',
                    filterAddNoFilter: true,
                    filterNoFilterDisplayName: '(نمایش همه)'
                }, individualColumnInfo);
                switch (individualColumnInfo.filterMode) {
                    case '{$filterTypeNone}':

                    case '{$filterTypeText}':
                        {
                            innerHtml = (individualColumnInfo.placeHolder == null
                                    ? $('<input type="text" placeholder="جستجو ' + column.title + '..." />')
                                    : $('<input type="text" placeholder="' + individualColumnInfo.placeHolder + '" />'))
                                .addClass('jdtInput textInput');

                            break;
                        }
                    case '{$filterTypeBoolean}':
                        {
                            innerHtml = $('<select></select>');
                            if (individualColumnInfo.addNoFilter) {
                                innerHtml
                                    .append($('<option></option>')
                                        .attr('value', '')
                                        .text(individualColumnInfo.noFilterDisplayName));
                            }
                            innerHtml
                                .append($('<option></option>')
                                    .attr('value', true)
                                    .text(individualColumnInfo.trueDisplayName))
                                .append($('<option></option>')
                                    .attr('value', false)
                                    .text(individualColumnInfo.falseDisplayName))
                                .addClass('jdtInput textInput');

                            break;
                        }
                    case '{$filterTypeList}':{
                        innerHtml = $('<select></select>')
                            .addClass('jdtInput selectInput');

                        if (individualColumnInfo.addNoFilter) {
                            innerHtml
                                .append($('<option></option>')
                                    .attr('value', '')
                                    .text(individualColumnInfo.noFilterDisplayName));
                        }

                        if (individualColumnInfo.useRemoteData) {
                            $.ajax({
                                url: individualColumnInfo.remoteDataUrl,
                                type: individualColumnInfo.remoteDataAjaxRequestType,
                                data: individualColumnInfo.remoteDataAjaxRequestData,
                                success: function(data) {
                                    $(data).each(function(idx, val) {
                                        innerHtml
                                            .append($('<option></option>')
                                                .attr('value', val[0])
                                                .text(val[1]));
                                    });
                                },
                                error: function(xhr, textStatus, errorThrown) {
                                    if (individualColumnInfo.filterList != null) {
                                        $(individualColumnInfo.filterList).each(function(idx, val) {
                                            innerHtml
                                                .append($('<option></option>')
                                                    .attr('value', val[0])
                                                    .text(val[1]));
                                        });
                                    }
                                }
                            });
                        } else if (individualColumnInfo.filterList != null) {
                            $(individualColumnInfo.filterList).each(function(idx, val) {
                                innerHtml
                                    .append($('<option></option>')
                                        .attr('value', val[0])
                                        .text(val[1]));
                            });
                        } else {
                            this.data().unique().sort().each(function(d, j) {
                                innerHtml.append('<option value="' + d + '">' + d + '</option>');
                            });
                        }

                        break;
                    }
                    case '{$filterTypeDateTime}':{
                        innerHtml = $('<input/>')
                            .addClass('jdtInput dateTimeInput');

                        break;
                    }
                }
            }
            if (innerHtml != null)
                innerHtml = innerHtml.appendTo($(this.footer()).empty());

            if (filterOnEnter) {
                $('.jdtInput', this.footer()).keypress(function(e) {
                    if (e.which == 13) {
                        if (that.search() !== this.value) {
                            that
                                .search(this.value)
                                .draw();
                        }
                        return false;
                    }
                });
            } else {
                $('.jdtInput', this.footer()).on('keyup change', function() {
                    if (that.search() !== this.value) {
                        that
                            .search(this.value)
                            .draw();
                    }
                });
            }
        });
    }
JavaScript;
        }

        $outputHtml = new GearHtmlString($outputHtml);
        if ($addScriptTag) {
            $outputHtml->prepend("<script type=\"text/javascript\">\n");
            $outputHtml->append("\n</script>");
        }
        return $outputHtml;
    }
}

/*</module>*/
?>