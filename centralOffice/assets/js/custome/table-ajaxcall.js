
function applyCommonTableFeatures(myTable) {
    $.fn.dataTable.Buttons.defaults.dom.container.className =
        'dt-buttons btn-overlap btn-group btn-overlap';
    myTable.buttons().container().appendTo($('.tableTools-container'));
    var defaultCopyAction = myTable.button(1).action();

    myTable.button(1).action(function (e, dt, button, config) {
        defaultCopyAction(e, dt, button, config);
        $('.dt-button-info').addClass('gritter-item-wrapper gritter-info gritter-center white');
    });

    var defaultColvisAction = myTable.button(0).action();
    myTable.button(0).action(function (e, dt, button, config) {
        defaultColvisAction(e, dt, button, config);
        if ($('.dt-button-collection > .dropdown-menu').length == 0) {
            $('.dt-button-collection')
                .wrapInner('<ul class="dropdown-menu dropdown-light dropdown-caret dropdown-caret" />')
                .find('a').attr('href', '#').wrap("<li />")
        }
        $('.dt-button-collection').appendTo('.tableTools-container .dt-buttons')
    });

    myTable.on('select', function (e, dt, type, index) {
        if (type === 'row') {
            $(myTable.row(index).node()).find('input:checkbox').prop('checked', true);
        }
    });

    myTable.on('deselect', function (e, dt, type, index) {
        if (type === 'row') {
            $(myTable.row(index).node()).find('input:checkbox').prop('checked', false);
        }
    });

    $('#dynamic-tables thead input[type=checkbox]').on('click', function () {
        var checked = this.checked;
        $('#dynamic-table tbody tr').each(function () {
            checked ? myTable.row(this).select() : myTable.row(this).deselect();
        });
    });

    $('#dynamic-tables')
        .on('processing.dt', function (e, settings, processing) {
            if (processing) {
                $('#customLoader').show();
            } else {
                $('#customLoader').hide();
            }
        });


    // GLOBAL DEBOUNCE SEARCH
    let debounceTimer;
    $('#dynamic-tables_filter input')
        .off('keyup.DT input.DT') // remove default
        .on('input', function () {
            clearTimeout(debounceTimer);
            let value = this.value;
            debounceTimer = setTimeout(function () {
                myTable.search(value).draw();
            }, 1000);
        });
}

function GetTableButtons() {
    return [
        {
            "extend": "colvis",
            "text": "<i class='fa fa-search bigger-110 blue'></i> <span class='hidden'>Show/hide columns</span>",
            "className": "btn btn-white btn-primary btn-bold",
            columns: ':not(:first):not(:last)'
        },
        {
            extend: "copy",
            text: "<i class='fa fa-copy bigger-110 pink'></i>",
            className: "btn btn-white btn-primary btn-bold",
        },
        {
            extend: 'csv',
            text: "<i class='fa fa-database bigger-110 orange'></i> <span class='hidden'>Export to CSV</span>",
            className: "btn btn-white btn-primary btn-bold",
            action: function (e, dt, button, config) {
                let params = dt.ajax.params();
                params.export_all = 1;
                params.export_csv = 1;
                let iframe = document.getElementById("downloadFrame");
                if (!iframe) {
                    iframe = document.createElement("iframe");
                    iframe.name = "downloadFrame";
                    iframe.id = "downloadFrame";
                    iframe.style.display = "none";
                    document.body.appendChild(iframe);
                }
                let form = document.createElement("form");
                form.method = "POST";
                form.action = dt.ajax.url();
                form.target = "downloadFrame";
                function appendFormData(obj, parentKey = '') {
                    for (let key in obj) {
                        if (!obj.hasOwnProperty(key)) continue;
                        let value = obj[key];
                        let input = document.createElement("input");
                        if (typeof value === "object" && value !== null) {
                            appendFormData(value, parentKey ? `${parentKey}[${key}]` : key);
                        } else {
                            input.type = "hidden";
                            input.name = parentKey ? `${parentKey}[${key}]` : key;
                            input.value = value;
                            form.appendChild(input);
                        }
                    }
                }
                appendFormData(params);
                document.body.appendChild(form);
                form.submit();
                setTimeout(() => {
                    form.remove();
                }, 1000);
            }
        },

        {
            extend: "pdf",
            text: "<i class='fa fa-file-pdf-o bigger-110 red'></i>",
            className: "btn btn-white btn-primary btn-bold"
        },
        {
            extend: "print",
            text: "<i class='fa fa-print bigger-110 grey'></i>",
            className: "btn btn-white btn-primary btn-bold",
            autoPrint: false
        }
    ];
}