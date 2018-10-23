<style>
    /* DataTables */
    .input-data-tables {
        width: 100px !important;
    }

    table thead th, table tfoot th {
        border: 0px !important;
    }

    table thead th {
        border-bottom: 2px solid #cccccc !important;
    }

    .label {
        font-weight: normal !important;
        font-size: 14px !important;
    }

    td.details-control {
        background: url('images/details_open.png') no-repeat center center;
        cursor: pointer;
    }

    tr.shown td.details-control {
        background: url('images/details_close.png') no-repeat center center;
    }
</style>

<div id="page-wrapper">
    <?php if(isset($errors)){
        echo $errors;
    }
    ?>
    <div class="panel panel-default" style="margin-top: 15px;">
        <div class="panel-heading">
            <h3 class="panel-title">Резервации</h3>
        </div>
        <div class="panel-body">
        </div>

        <table id="table" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>ном. резерв.</th>
                <th>направена</th>
                <th>брой стаи</th>
                <th>стаи</th>
                <th>пристигане</th>
                <th>заминаване</th>
                <th>клиент</th>
                <th>статус</th>
            </tr>
            </thead>
            <!-- dataTablesHere -->
            <tfoot>
            <tr>
                <th>ном. резерв.</th>
                <th>направена</th>
                <th>брой стаи</th>
                <th>стаи</th>
                <th>пристигане</th>
                <th>заминаване</th>
                <th>клиент</th>
                <th>статус</th>
            </tr>
            </tfoot>
        </table>
        <!--        </div>-->
    </div>
</div><!-- page-wrapper -->
<script>
    $(document).ready(function () {

        // Setup - add a text input to each footer cell
        $('#table tfoot th').each(function () {
            var title = $(this).text();
            $(this).html('<input class="form-control input-sm input-data-tables" type="text" placeholder="' + title + '">');
        });

//--------------------------------------------------------------------------------------------------------
        var table = $('#table').DataTable({
            "processing": true,
            "serverSide": true,
            responsive: true,
            // select: true,
            dom: 'lfrtipB',
            buttons: [
                'csv', 'copy'
            ],
            "iDisplayLength": 50,
            "order": [[ 1, "desc" ]],
            "ajax": "data_table",
            "language": {
                "info": "показни _PAGE_ от _PAGES_ страници (_MAX_ записа)",
                "emptyTable": "Няма записи",
                "infoEmpty": "покажи 0 to 0 of 0 записа",
                "infoFiltered": "(филтрирани от _MAX_ общо записа)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "покажи _MENU_ записа",
                "loadingRecords": "зарежда...",
                "processing": "зарежда...",
                "search": "търси: ",
                "zeroRecords": "не са открити записи",
                "csv": "Експорт",
                "paginate": {
                    "first": "първа",
                    "last": "последна",
                    "next": "следваща",
                    "previous": "предишна"
                }
            },

            "createdRow": function (row, data, index) {
                if (data[7] == "чакаща") {
                    $('td', row).eq(7).html('<span class="label label-danger">' + data[7] + '</span>');
                } else if (data[7] == "анулирана") {
                    $('td', row).eq(7).html('<span class="label label-default"><del>' + data[7] + '</del></span>');
                } else if (data[7] == "приета") {
                    $('td', row).eq(7).html('<span class="label label-info">' + data[7] + '</span>');
                } else if (data[7] == "потвърдена") {
                    $('td', row).eq(7).html('<span class="label label-success">' + data[7] + '</span>');
                } else if (data[7] == "приключила") {
                    $('td', row).eq(7).html('<span class="label label-end">' + data[7] + '</span>');
                } else if (data[7] == "текуща") {
                    $('td', row).eq(7).html('<span class="label label-warning">' + data[7] + '</span>');
                } else if (data[7] == "от служител") {
                    $('td', row).eq(7).html('<span class="label label-primary">' + data[7] + '</span>');
                }

                $('td', row).eq(0).html('<a href="reservation/' + data[0] + '">' + data[0] + '</a>');
            },

            "initComplete": function () {
                this.api().columns(7).every(function () {
                    var column = this;
                    var select = $('<select class="form-control input-sm"><option value=""></option></select>')
                        .appendTo($(column.footer()).empty())
                        .on('change', function () {
                            column
                                .search(this.value)
                                .draw();
                        });

                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });

            }

        });

//------------------------------------------------------------------------------------------------------------
        // Apply the search
        table.columns().every(function () {
            var that = this;

            $('input', this.footer()).on('keyup change', function () {
                if (that.search() !== this.value) {
                    that
                        .search(this.value)
                        .draw();
                }
            });

        });

    });

</script>