<div id="page-wrapper">
    <!-- -- Form --- -->
    <form id="new_reservation_form" method="post"
          action="<?php echo site_url('booking-dashboard/reservation/new/new-reservation') ?>">
        <input type="hidden" value="<?php echo $reservation_id; ?>">

        <div class="row" style="margin-top: 10px">
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h3 class="panel-title">резервация - <?php echo $reservation_id; ?></h3>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div
                                class="input-group input-group-sm<?php echo empty(validation_error('arrival')) ? '' :' has-error'; ?>">
                                <div class="input-group-addon">
                                    <span class="fa fa-calendar fa-lg" aria-hidden="true"></span>
                                </div>
                                <input type="text" name="arrival" class="form-control" id="arrival"
                                       value="<?php echo $now; ?>" placeholder="настаняване">
                            </div>
                            <?php echo validation_error('arrival'); ?>
                        </div>
                        <div class="form-group">
                            <div
                                class="input-group input-group-sm<?php echo empty(validation_error('departure')) ? '' :' has-error'; ?>">
                                <div class="input-group-addon">
                                    <span class="fa fa-calendar fa-lg" aria-hidden="true"></span>
                                </div>
                                <input type="text"
                                       class="form-control input-lg" name="departure" id="departure"
                                       value="<?php echo $tomorrow; ?>" placeholder="напускане">
                            </div>
                            <?php echo validation_error('departure'); ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <div class="row">
                                <div class="col-md-9">
                                    <h3 class="panel-title">налични стаи</h3>
                                </div>
                                <div class="col-md-3">
                                    <button id="button-available-room" type="button" class="btn btn-default">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="room-body" class="panel-body">
                        <div class="row">
                            <div class="col-md-12 form-group" id="rooms">
                                <select class="form-control" name="rooms" id="drop_down_rooms">
                                    <option value="" id="check-date">изберете дата</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <select class="form-control" name="adults" id="adults" style="display: none"></select>
                            </div>
                            <div class="col-md-6 form-group">
                                <select class="form-control" name="child" id="child" style="display: none"></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button id="add-room" type="button" class="btn btn-default">избери</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">избрани стаи</h3>
                    </div>
                    <div class="panel-body">
                        <div id="added_rooms"></div>
                        <?php echo validation_error('added_rooms'); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">клиент</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-6">
                                <div
                                    class="form-group <?php echo empty(validation_error('name')) ? '' :' has-error'; ?>">
                                    <label for="name">Име *</label>
                                    <input id="name" name="name" type="text" class="form-control" placeholder="Име"
                                           value="<?php echo oldValue('name'); ?>">
                                    <?php echo validation_error('name'); ?>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div
                                    class="form-group<?php echo empty(validation_error('email')) ? '' :' has-error'; ?>">
                                    <label for="email">Email *</label>
                                    <input id="email" name="email" type="text" class="form-control" placeholder="e-mail"
                                           value="<?php echo oldValue('email'); ?>">
                                    <?php echo validation_error('email'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div
                                    class="form-group<?php echo empty(validation_error('telefon')) ? '' :' has-error'; ?>">
                                    <label for="telefon">Телефон *</label>
                                    <input id="telefon" name="telefon" type="text" class="form-control"
                                           placeholder="телефон"
                                           value="<?php echo oldValue('telefon'); ?>">
                                    <?php echo validation_error('telefon'); ?>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div
                                    class="form-group<?php echo empty(validation_error('text')) ? '' :' has-error'; ?>">
                                    <label for="text">Коментар</label>
                                    <textarea id="textarea" name="text" class="form-control" rows="4"></textarea>
                                    <?php echo validation_error('text'); ?>
                                    <div id="count"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-xs-offset-6 text-right">
                                <input id="submit" type="submit" class="btn btn-success"
                                       value="резервирай">
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </form>
</div>
<script>
    // --------------------- form functions ---------------------
    var arrival = $("#arrival");
    var departure = $("#departure");

    function display_added() {
        $.get("<?php echo site_url('booking-dashboard/reservation/new/display-added'); ?>", function (data) {
            $('#added_rooms').html(data).fadeIn(1000);
        });

    }
    display_added();

    function availableRooms(arrival, departure, added='') {
        $.ajax({
            url: "<?php echo site_url('booking-dashboard/reservation/new/availRooms');?>",
            method: "POST",
            data: {arrival: arrival, departure: departure, added: added},
            success: function (result) {
                $("#check-date").remove();
                $("#drop_down_rooms").html(result);
                display_added();
            }
        });
    }

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
        if (!arrival.val() || !departure.val()) {
            alert('Не сте избрал дати!');

        } else {
            $.get("<?php echo site_url('booking-dashboard/reservation/new/update-added-room'); ?>", function (data) {

                availableRooms(arrival.val(), departure.val(), data);
            });
        }

    });

    $("#arrival").change(function () {
        $("#button-available-room").prop('disabled', false);

        availableRooms(arrival.val(), departure.val());

        delete_added_room();
    });

    $("#departure").change(function () {
        $("#button-available-room").prop('disabled', false);

        if (!arrival.val()) {
            alert('Изберете дата на пристигане!');
            $("#departure").val("");
        } else {
            delete_added_room();
        }
    });

    $("#add-room").on("click", function () {
        var room_id, room_name, room_type_id, adults, child, qty;
        room_id = $("#drop_down_rooms").val();
        room_name = $("#drop_down_rooms").find(":selected").text();
        room_type_id = $("#drop_down_rooms").find(":selected").data("roomtypeid");
        adults = $("#adults").val();
        child = $("#child").val();
        qty = 1;
        var formData = {
            arrival: arrival.val(),
            departure: departure.val(),
            room_id: room_id,
            room_name: room_name,
            room_type_id: room_type_id,
            adults: adults,
            child: child,
            qty: qty
        };

        $.ajax({
            url: "<?php echo site_url('booking-dashboard/reservation/new/add-room');?>",
            method: "post",
            data: formData,
            success: function (result) {
                if (result == "has-error"){
                    $("#rooms").addClass(result);
                } else {
                    var i, array_added;
                    array_added = [];
                    for (i = 0; i < result.length; i++) {
                        array_added.push(result[i].room_id);
                    }
                    $("#rooms").removeClass(result);

                    availableRooms(arrival.val(), departure.val(), array_added);
                }
            }
        })
    });

    function delete_added_room(id='') {
        $.ajax({
            url: "<?php echo site_url('booking-dashboard/reservation/new/delete-added-room');?>",
            method: "post",
            data: {key: id},
            success: function (result) {
                availableRooms(arrival.val(), departure.val(), result);

            }
        });
    }

</script>