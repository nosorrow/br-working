<?php
$checkin = date('Y-m-d', strtotime($reservation[0]->checkin));
$checkout = date('Y-m-d', strtotime($reservation[0]->checkout));
$dates = parseDates($reservation[0]->checkin, $reservation[0]->checkout);
?>

<div id="page-wrapper">
    <!-- Modal -->
    <div class="modal fade" id="addRoomModal" tabindex="-1" role="dialog" aria-labelledby="addRoomModalLabel">
        <form id="modal_add_room_form">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="addRoomModallLabel">добави стая</h4>
                    </div>
                    <div class="modal-body">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <h3 class="panel-title">налични стаи</h3>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <button id="button-available-room" type="button" class="btn btn-default">
                                                <i class="fa fa-refresh"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="room-body" class="panel-body">
                                <div class="row">
                                    <div class="col-md12" id="add-modal-error"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 form-group" id="rooms">
                                        <select class="form-control" name="rooms" id="drop_down_rooms">
                                            <option value="" id="check-date">изберете дата</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <select class="form-control" name="adults" id="adults"
                                                style="display: none"></select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <select class="form-control" name="child" id="child" style="display: none">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>  <!-- end modal body -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">затвори</button>
                        <button type="submit" class="btn btn-primary">запази</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="roomNameModal" tabindex="-1" role="dialog" aria-labelledby="roomNameModalLabel">
        <form id="modal_room_form">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="roomNameModalLabel">закачи стая</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" class="form-control" id="primary_id" name="primary_id">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="control-label">Стая:</label>
                            <select id="select" name="room_id" class="form-control">
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">затвори</button>
                        <button type="submit" class="btn btn-primary">запази</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="row" style="margin-top: 15px">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4>Информация за клиента</h4>
                    </div>
                </div>
                <div class="panel-body">

                    <ul class="list-group">
                        <li class="list-group-item"><span class="glyphicon glyphicon-user avatar"></span>
                            <strong><?php echo $reservation[0]->client_name; ?></strong>
                        </li>
                        <li class="list-group-item"><span class="glyphicon glyphicon-earphone"></span>
                            &nbsp;<?php echo $reservation[0]->tel; ?></li>
                        <li class="list-group-item">
                            <span class="glyphicon glyphicon-envelope"></span>&nbsp;
                            <?php echo $reservation[0]->email; ?></li>
                    </ul>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4>коментар</h4>
                    </div>
                </div>
                <div class="panel-body">
                    <?php echo (!$reservation[0]->comment) ? "няма коментар" :$reservation[0]->comment; ?>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        <div class="row">
                            <div class="col-md-9">
                                <?php $status = status($reservation[0]->status); ?>
                                <h4>Резервация: <?php echo $reservation[0]->reservation_id; ?> -
                                    <span id="status" class="label<?php echo $status->label; ?>">
                                <?php echo $reservation[0]->status; ?>
                                </span>&nbsp;
                                </h4>

                                <p style="font-size: small">
                                    <i class="fa fa-clock-o" data-toggle="tooltip" data-placement="bottom"
                                       title="направена на"></i>
                                    &nbsp;<?php echo $reservation[0]->created ?>
                                </p>
                            </div>
                            <div class="col-md-3 text-right">
                                <button id="edit" class="btn btn-default" onclick="displayForm()">
                                    <span class="fa fa-pencil"></span>
                                </button>
                                <button id="delete" class="btn btn-danger"
                                        onclick="deleteReservation('<?php echo $reservation[0]->reservation_id; ?>')">
                                    <span class="fa fa-trash"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="">
                                <strong><?php echo $dates['dateStr']; ?></strong>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div id="row-result" class="col-md-12"></div>
                    </div>
                    <div id="hidden-field" style="display: none">
                        <div class="well">
                            <div class="row">
                                <form id="status">
                                    <input name="reservation_id" type="hidden"
                                           value="<?php echo $reservation[0]->reservation_id; ?>">

                                    <div class="col-md-8">
                                        <select id="selectStatus" name="status" class="form-control">
                                            <option value="">-- промяна статус --</option>
                                            <option value="приета">приета</option>
                                            <option value="потвърдена">потвърдена</option>
                                            <option value="текуща">текуща</option>
                                            <option value="приключила">приключила</option>
                                            <option value="анулирана">анулирана</option>
                                        </select>
                                        <p id="error-msg"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <button id="submitStatus" type="submit" class="btn btn-primary">
                                            <span class="fa fa-save"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="well">
                            <div class="row">
                                <form id="reservation_date" class="form-inline">
                                    <input name="reservation_id" type="hidden"
                                           value="<?php echo $reservation[0]->reservation_id; ?>">

                                    <div
                                        class="input-group input-group-sm">
                                        <div class="input-group-addon">
                                            <span class="fa fa-calendar fa-lg" aria-hidden="true"></span>
                                        </div>
                                        <input type="text" name="arrival" class="form-control input-lg" id="arrival"
                                               value="<?php echo $checkin; ?>" placeholder="настаняване">
                                    </div>
                                    <div
                                        class="input-group input-group-sm">
                                        <div class="input-group-addon">
                                            <span class="fa fa-calendar fa-lg" aria-hidden="true"></span>
                                        </div>
                                        <input type="text" class="form-control input-lg" name="departure" id="departure"
                                               value="<?php echo $checkout; ?>" placeholder="напускане">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">промени</button>
                                </form>
                            </div>
                            <div class="row" id="date-error" style="display: none"></div>
                        </div>
                    </div>
                    <table class="table">
                        <?php foreach ($reservation as $key => $reserve): ?>
                            <tr>
                                <td>
                                    <i class="fa fa-check-circle-o fa-lg text-info" aria-hidden="true"></i> -
                                    <?php echo $reserve->room_type . ' | възрастни ' .
                                        $reserve->adults . ' | деца ' . $reserve->child; ?>
                                </td>
                                <td>
                                    цена: <?php echo $reserve->price .' '. settings()->currency(); ?>
                                </td>
                                <td>
                                    <?php echo $reserve->room_name ? $reserve->room_name :"закачи стая"; ?>
                                </td>
                                <td class="text-right">
                                    <button data-room_type_id="<?php echo $reserve->room_type_id; ?>"
                                            data-primary_id="<?php echo $reserve->id; ?>"
                                            data-toggle="modal" data-target="#roomNameModal"
                                            class="btn btn-sm btn-default brBtnTooltip">
                                        <i class="fa fa-plus"></i>
                                        <span class="brBtnTooltiptext">Закачи стая</span>

                                    </button>
                                    <!--</td>
                                    <td>-->
                                    <button data-delete_room_type_id="<?php echo $reserve->room_type_id; ?>"
                                            data-delete_primary_id="<?php echo $reserve->id; ?>"
                                            class="btn btn-sm btn-default cut_room brBtnTooltip">
                                        <i class="fa fa-scissors"></i>
                                        <span class="brBtnTooltiptext">Променя стая</span>

                                    </button>
                                    <button data-delete_room_type_id="<?php echo $reserve->room_type_id; ?>"
                                            data-delete_primary_id="<?php echo $reserve->id; ?>"
                                            class="btn btn-sm btn-default delete_room brBtnTooltip">
                                        <i class="fa fa-trash"></i>
                                        <span class="brBtnTooltiptext">Трие стая</span>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="4" class="text-right">
                                <button data-room_type_id="<?php echo $reserve->room_type_id; ?>"
                                        data-primary_id="<?php echo $reserve->id; ?>"
                                        data-toggle="modal" data-target="#addRoomModal"
                                        class="btn btn-sm btn-success brBtnTooltip">
                                    <i class="fa fa-plus"></i>
                                    <span class="brBtnTooltiptext">Добавя стая</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><span id="room-error"></span></td>
                        </tr>
                    </table>
                </div>
                <div class="panel-footer">
                    <h4>Тотал: <?php echo $total . ' ' . settings()->currency(); ?></h4>
                </div>
            </div>
        </div>
    </div>
</div><!-- page-wrapper -->
<script>
    $(function () {
        $('[data-toggle="tooltip"]').bstooltip()
    });

    function setDateInput() {
        $("#arrival").val("<?php echo $checkin;?>");
        $("#departure").val("<?php echo $checkout;?>");
    }

    function displayForm() {
        $("#hidden-field").fadeToggle(500);
        $("#status-result").remove();
    }

    function deleteReservation(id) {
        if (confirm("Ще изтриете ли резервация " + id)) {

            $.ajax({
                url: "<?php echo site_url("booking-dashboard/reservation/delete/");?>" + id,
                method: "GET",
                success: function (result) {
                    if (result == 1) {
                        $(location).attr('href', '<?php echo site_url('booking-dashboard/reservations');?>')
                    }
                }
            });
        }

    }

    $("form#reservation_date").on("submit", function (e) {
        e.preventDefault();
        if (confirm("Ще направите ли промяна?")) {

            var formReservationDateData = $(this).serialize();

            $.ajax({
                url: "<?php echo site_url('booking-dashboard/reservation/edit-date'); ?>",
                method: "POST",
                data: formReservationDateData,
                success: function (result) {
                    if (result == 1) {
                        location.reload();
                    } else {
                        $("#date-error").css({"color": "red", "margin-top": "10px"})
                            .html(result).fadeIn(500);
                        setDateInput();
                    }
                }
            });
        }

    });

    $("form#status").on("submit", function (e) {
        e.preventDefault();
        if (confirm("Ще направите ли промяна?")) {

            var formStatusData = $(this).serialize();
            var opt = $("#selectStatus").val();
            $.ajax({
                url: "<?php echo site_url('booking-dashboard/reservation/edit-status'); ?>",
                method: "POST",
                data: formStatusData,
                success: function (result) {
                    if (result == 1) {
                        $("#hidden-field").hide();
                        $("#row-result").append('<div id="status-result" class="alert alert-success">Успешна промяна</div>');
                        var label = newLabel(opt);
                        $("#status").addClass(label).html(opt);
                    } else {
                        $("#error-msg").css({"color": "red", "margin-top": "10px"}).html(result);
                    }
                }
            });
        }

    });

    function removeLabel() {
        var indexRemove = 1;
        var classString = $("#status").attr('class');
        var classes = classString.split(" ");
        classes.splice(indexRemove, 1);
        var newClassString = classes.join(' ');
        $('#status').removeClass();
        $('#status').addClass(newClassString);
    }

    function newLabel(data) {
        var label;
        switch (data) {
            case 'анулирана':
                data = ' label-default';
                break;
            case 'чакаща':
                data = ' label-danger';
                break;
            case 'приета':
                data = ' label-info';
                break;
            case 'текуща':
                data = ' label-warning';
                break;
            case 'потвърдена':
                data = ' label-success';
                break;
            case 'приключила':
                data = 'label-end';
                break;
            case 'от служител':
                data = 'label-primary';
                break;

        }
        removeLabel();

        return data;
    }

    $("#roomNameModal").on("show.bs.modal", function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var room_type_id = button.data('room_type_id');
        var checkin = "<?php echo $checkin;?>";
        var checkout = "<?php echo $checkout;?>";
        var primary_id = button.data('primary_id');
        var modal = $(this);
        $.ajax({
            url: "<?php echo site_url('booking-dashboard/reservation/fetch_room_names');?>",
            method: "POST",
            data: {checkin: checkin, checkout: checkout, room_type_id: room_type_id},
            success: function (result) {
                $("#primary_id").val(primary_id);
                $('#select').html(result);
            }
        });
    });

    $("#modal_room_form").on("submit", function (e) {
        e.preventDefault();
        var modalRoomFormData = $(this).serialize();
        $.ajax({
            url: "<?php echo site_url('booking-dashboard/reservation/attach_room');?>",
            method: "POST",
            data: modalRoomFormData,
            success: function (result) {
                if (result == 1) {
                    location.reload();
                }
            }
        });
    });

    $(".cut_room").click(function (e) {
        e.preventDefault();
        var reservationId = $(this).data("delete_primary_id");
        $.ajax({
            url: "<?php echo site_url('booking-dashboard/reservation/cut_room');?>",
            method: "POST",
            data: {id: reservationId},
            success: function (result) {
                if (result == 1) {
                    location.reload();
                }
            }
        });
    });

    $(".delete_room").click(function (e) {
        e.preventDefault();
        var reservationId = $(this).data("delete_primary_id");

        if (confirm("Ще изтриете ли тази стая?")) {

            $.ajax({
                url: "<?php echo site_url('booking-dashboard/reservation/delete_room');?>",
                method: "POST",
                data: {id: reservationId, reservation_id: "<?php echo $reservation[0]->reservation_id;?>"},
                success: function (result) {

                    if (result == 1) {
                        location.reload();

                    } else {
                        $("#room-error").css('color', 'red').html(result).fadeOut(5000);
                    }
                }
            });
        }
    })
</script>
<!-- ----------------------- добавя нова стая ----------------------------------->
<script>
    // --------------------- form functions ---------------------
    var arrival = '<?php echo $checkin;?>';
    var departure = '<?php echo $checkout;?>';


    function availableRooms(arrival, departure) {
        $.ajax({
            url: "<?php echo site_url('booking-dashboard/reservation/new/availRooms');?>",
            method: "POST",
            data: {arrival: arrival, departure: departure, added: ''},
            success: function (result) {
                $("#drop_down_rooms").html(result);
            }
        });
    }

    availableRooms(arrival, departure);

    $("#drop_down_rooms").change(function () {
        var room_id = $(this).val();
        $.ajax({
            url: "<?php echo site_url('booking-dashboard/reservation/new/get_room_guest');?>",
            method: "POST",
            data: {room_id: room_id},
            success: function (result) {
                var i;
                var option_a = "<option value=''>възр.</option>";
                var option_c = "<option value=''>деца</option>";
                var adults = result.adults;
                var child = result.child;

                $("#rooms").removeClass("has-error");

                for (i = 1; i <= adults; i++) {
                    option_a += "<option>" + i + "</option>";
                }
                $("#adults").html(option_a).show();

                for (i = 1; i <= child; i++) {
                    option_c += "<option>" + i + "</option>";
                }
                $("#child").html(option_c).show();
            }
        })
    });

    $("#button-available-room").click(function () {
        if (!arrival || !departure) {
            alert('Не сте избрал дати!');

        } else {
            $.get("<?php echo site_url('booking-dashboard/reservation/new/update-added-room'); ?>", function (data) {

                availableRooms(arrival, departure);
            });
        }

    });

    $("#modal_add_room_form").on("submit", function (e) {
        e.preventDefault();
        var reservation_id, room_id, room_type_id,
            adults, child, status;

        room_id = $("#drop_down_rooms").val();
        room_type_id = $("#drop_down_rooms").find(":selected").data("roomtypeid");
        adults = $("#adults").val();
        child = $("#child").val();

        var formData = {
            reservation_id: "<?php echo $reservation[0]->reservation_id;?>",
            room_id: room_id,
            room_type_id: room_type_id,
            arrival: "<?php echo $checkin;?>",
            departure: "<?php echo $checkout;?>",
            adults: adults,
            child: child,
            status: "<?php echo $reservation[0]->status;?>",
            client_id: "<?php echo $reservation[0]->client_id;?>"
        };

        $.ajax({
            url: "<?php echo site_url('booking-dashboard/reservation/add_room');?>",
            method: "POST",
            data: formData,
            success: function (result) {
                if (result == 1) {
                    location.reload();
                } else {
                    //  location.reload();
                    $("#add-modal-error").css({"color": "red", "padding": "10px"}).html("Грешка при запис в БД");
                }
                console.log(formData);
                //location.reload();
            }
        });
    });

</script>