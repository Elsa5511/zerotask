// NB! This script assumes that the status column is the next to last column of the table.

$("body").on("datatable-loaded", function () {
    var filterDiv = $(".data-table-actions").children("div").eq(1);
    filterDiv.append('<div class="checkbox pull-right"><label><input type="checkbox" id="show-inactive-checkbox" />' + system.translations["showInactive"] + '</label></div>');

    var dataTableElement = $("#DataTables_Table_0");

    var tableHeaders = dataTableElement.find("thead").find("tr").children("th");
    var activeStatusColumn = tableHeaders.length - 2;
    var statusColumnIndex = dataTableElement.find("tbody").find("tr").children("td.status-column").index();
    if(statusColumnIndex > -1)  activeStatusColumn = statusColumnIndex;

    var dataTable = dataTableElement.dataTable();

    $("#show-inactive-checkbox").click(function () {
        if ($(this).is(":checked")) {
            showInactive();
        }
        else {
            hideInactive();
        }
    });

    function showInactive() {
        dataTable.fnFilter("", activeStatusColumn, false, true, false, false);
    }

    function hideInactive() {
        dataTable.fnFilter(system.translations["Active"], activeStatusColumn, false, true, false, false);
    }

    hideInactive();

});
