$("body").on("datatable-loaded", function () {
    var checkboxId = "show-expired-checkbox";
    var filterDiv = $(".data-table-actions").children("div").eq(1);
    filterDiv.append('<div class="checkbox pull-right"><label><input type="checkbox" id="show-expired-checkbox" />' + system.translations["showExpiredExamAttempts"] + '</label></div>');

    var checkbox = $("#" + checkboxId);
    var dataTableElement = $("#DataTables_Table_0");
    var expirationDateColumn = 2;
    var dataTable = dataTableElement.DataTable();

    $.fn.dataTable.ext.search.push(
        function(settings, searchData, index, rowData, counter ) {
            if (checkbox.is(":checked")) {
                return true;
            }
            else {
                var dateString = searchData[expirationDateColumn];

                if (dateString === "") {
                    return true;
                }
                else {
                    var expirationDate = moment(dateString, "DD.MM.YY");
                    var now = moment();
                    return expirationDate.isAfter(now);
                }
            }
        }
    );

    dataTable.draw();

    $(checkbox).click(function () {
        dataTable.draw();
    });
});

