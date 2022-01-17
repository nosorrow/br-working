<?php
$tr_placeholder_arrival = tr_('настаняване');
$tr_placeholder_departure = tr_('напускане');
$tr_placeholder_adults = tr_('възрастни');
$tr_placeholder_child = tr_('деца');
$tr_placeholder_search = tr_('търсене');
$tr_beds = tr_('легла');
$str = explode("_", get_locale_lang());
$lang = $str[0];
?>
<style>
    .container-hover {
        background-color: #000;
        position: relative;
        width: 100%;
    }

    .image-hover {
        opacity: 1;
        display: block;
        width: 100%;
        height: auto;
        transition: .5s ease;
        backface-visibility: hidden;
    }

    .middle {
        transition: .5s ease;
        opacity: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%)
    }

    .container-hover:hover .image-hover {
        opacity: 0.5;
    }

    .container-hover:hover .middle {
        opacity: 1;
    }
    .input-group-addon, input{
        border-radius: 0 !important;
    }
</style>
<div class="container">
    <div class="row" style="padding: 5px;">
        <div class="col-md-1 col-md-offset-10">
            <a href="<?php echo site_url('lang/bg_BG'); ?>">
                <img src="<?php echo site_url('images/bg.png'); ?>" alt="bg-flag">
            </a>
            <a href="<?php echo site_url('lang/en_US'); ?>">
                <img src="<?php echo site_url('images/en.png'); ?>" alt="en-flag">
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div id="scroll" class="panel-body">
                    <div class="text-center">
                        <!-- ------------------  Start Booking-Room-Search Form ----------------------- -->
                        <form id="searchForm" action="<?php echo site_url(route('search', [''], 'get')); ?>"
                              name="insert"
                              method="get">
                            <div class="col-md-3 col-sm-12" style="margin-right: 0 !important; padding:5px 5px 5px 5px !important;">
                                <div
                                    class="input-group<?php echo empty(validation_error('checkin')) ? '' :' has-error'; ?>">
                                    <div class="input-group-addon" style="border-radius: 0 !important;">
                                        <span class="fa fa-calendar fa-lg" aria-hidden="true"></span>
                                    </div>
                                    <input type="text" name="checkin" class="form-control" id="checkin" autocomplete="off"
                                           placeholder="<?php echo $tr_placeholder_arrival; ?>"
                                           value="<?php echo oldValue('checkin'); ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12" style="margin-right: 0 !important; padding:5px 5px 5px 5px !important;">
                                <div
                                    class="input-group<?php echo empty(validation_error('checkout')) ? '' :' has-error'; ?>">
                                    <div class="input-group-addon">
                                        <span class="fa fa-calendar fa-lg" aria-hidden="true"></span>
                                    </div>
                                    <input type="text" class="form-control" name="checkout" id="checkout" autocomplete="off"
                                           placeholder="<?php echo $tr_placeholder_departure; ?>"
                                           value="<?php echo oldValue('checkout'); ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12" style="margin-right: 0 !important; padding:5px 5px 5px 5px !important;">
                                <div
                                    class="input-group<?php echo empty(validation_error('adults')) ? '' :' has-error'; ?>">
                                    <div class="input-group-addon">
                                        <span class="fa fa-user fa-lg" aria-hidden="true"></span>
                                    </div>
                                    <input type="number" class="form-control" name="adults" id="adults"
                                           placeholder="<?php echo tr_('гости'); ?>"
                                           value="<?php echo oldValue('adults'); ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12" style="margin-right: 0 !important; padding:5px 5px 5px 5px !important;">
                                <span class="input-group">
                                <button id="searchButton" class="btn btn-info btn-outline btn-block" data-toggle="tooltip"
                                        data-placement="bottom"
                                        title="<?php echo tr_('търси свободни стаи за дата'); ?>"
                                        type="submit"><i class="fa fa-search" aria-hidden="true"></i>
                                    <?php echo $tr_placeholder_search; ?>
                                </button>
                            </span>
                            </div>

                        </form>
                    </div>
                    <!-- End Booking-room Form -->
                </div><!-- ------------------- End panel-body ----------------------- -->
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 0px">
        <div class="col-md-10 col-md-offset-1">
            <div class="">
                <?php echo flash('error'); ?>
                <?php echo flash('msg'); ?>
                <?php if (isset($errors)) {
                    echo $errors;
                }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" id="add_tobag_msg" style="display: none"></div>
        </div>
    </div>
    <!-- ----------------- Показва резултата от търсенето -------------------------------- -->
    <?php if (!empty($rooms)): ?>
    <!-- Add result container -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">

                    <div class="row text-left" style="margin-top: 30px; padding-left: 10px;">
                        <div class="col-md-9">
                            <h4>
                                <?php echo $dateStr; ?> | <?php echo $tr_placeholder_adults . ' ' . $adults; ?>
                                <?php echo (!$child) ? '' :' | ' . $tr_placeholder_child . ' ' . $child; ?>
                            </h4>
                        </div>
                    </div>
                    <!-- -- display Added rooms -->
                    <div class="row">
                        <div id="displayAdded" class="col-md-12" style="margin: 5px; display: none"></div>
                    </div>
                    <!-- -- end display Added rooms -->
                    <?php foreach ($rooms as $key => $result): ?>
                    <?php if ($result['available'] !== 0): ?>
                    <?php $disabled = ""; ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color: #777; font-weight: 700; letter-spacing: 2px">
                                <?php echo $result['room_type']; ?>
                            </h3>
                            <?php else: ?>
                            <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <h3 class="panel-title" style="font-weight: 700; letter-spacing: 2px">
                                        <?php $disabled = "disabled"; ?>

                                        <?php echo $result['room_type']; ?>
                                    </h3>

                                    <p style="font-size: small"><?php echo tr_('Няма свободна стая'); ?>!</p>
                                    <?php endif; ?>
                                </div>
                                <div class="panel-body">
                                    <div class="row">

                                        <form data-display="<?php echo $key; ?>" method="post" class="addRoom">
                                            <!-- image -->
                                            <div class="col-md-3">
                                                <div class="container-hover">
                                                    <?php $image = unserialize($result['img_type_url']); ?>
                                                    <img id="room-img" class="image-hover thumb-size img-responsive"
                                                         src="<?php echo site_url('/') . $image[0]; ?>">

                                                    <div class="middle">
                                                        <a class="btn btn-warning"
                                                           href="<?php echo site_url(route('roominfo', [$result['room_type_slug']], 'GET') . '?r_k=' . $key); ?>">
                                                            <?php echo tr_('повече информация'); ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--End image -->
                                            <!-- Content -->
                                            <div class="col-md-6">
                                                <div class="well">
                                                    <?php echo $result['description']; ?>
                                                </div>
                                                <ul class="list-group">
                                                    <li class="list-group-item">
                                                        <i class="fa fa-bed" aria-hidden="true"> </i>
                                                        <?php echo $tr_beds . ' : ' . $result['beds']; ?>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <i class="fa fa-user" aria-hidden="true"> </i>
                                                        <?php echo $tr_placeholder_adults . ': ' . $result['adults']; ?>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <i class="fa fa-child" aria-hidden="true"> </i>
                                                        <?php echo $tr_placeholder_child . ': ' . $result['child']; ?>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <p style="letter-spacing: 2px; color:#286090">
                                                            <?php foreach ($result['amenities'] as $amenities): ?>
                                                                <?php echo '<i class="fa ' . $amenities['icon'] . '" aria-hidden="true"
                                                                data-toggle="tooltip" data-placement="top" title="' .
                                                                    $amenities['name'] . '"></i>' . "&nbsp;"; ?>
                                                            <?php endforeach; ?>
                                                        </p>
                                                    </li>
                                                </ul>
                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <div id="avail-<?php echo $key; ?>" class="available">
                                                            <select name="quantity" class="form-control available"
                                                                    onchange="displaySelect('<?php echo $key; ?>')" <?php echo $disabled; ?>>
                                                                <option value="0"><?php echo tr_('брой'); ?></option>
                                                                <?php for ($i = 1; $i <= $result['available'] && $i <= 10; $i++): ?>
                                                                    <option
                                                                        value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                                <?php endfor; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4">

                                                        <div class="input-group adults" style="display: none"
                                                             data-display="<?php echo $key; ?>">
                                                            <span class="input-group-addon"><span
                                                                    class="fa fa-user"></span></span>
                                                            <select name="add_adults" class="form-control">
                                                                <?php for ($i = 1; $i <= $result['adults']; $i++): ?>
                                                                    <option><?php echo $i; ?></option>
                                                                <?php endfor; ?>
                                                            </select>
                                                        </div>

                                                    </div>
                                                    <div class="col-xs-4">

                                                        <?php if ($result['child'] != 0): ?>
                                                            <div class="input-group child" style="display: none"
                                                                 data-display="<?php echo $key; ?>">
                                                                <span class="input-group-addon"><span
                                                                        class="fa fa-child"></span></span>
                                                                <select name="add_child" class="form-control">
                                                                    <option value="0">0</option>
                                                                    <?php for ($i = 1; $i <= $result['child']; $i++): ?>
                                                                        <option><?php echo $i; ?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        <?php endif; ?>

                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Content -->
                                            <!-- Add Button and price -->
                                            <div class="col-md-3 text-center">
                                                <h3><?php echo tr_('цена'); ?>:</h3>

                                                <h2><?php echo number_format($result['price'], 2) . ' ' . settings()->currency(); ?></h2>

                                                <p><span
                                                        style="font-size: 12px; color: #777">( <?php echo sprintf(tr_('за %d нощувка'), 1); ?>
                                                        )</span></p>
                                                <input name="r_k" type="hidden" value="<?php echo $key; ?>">

                                                <div style="margin-top: 30px">
                                                    <button data-added-key="<?php echo $key; ?>"
                                                            class="btn btn-primary btn-lg add">
                                                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                                        <?php echo tr_("добави"); ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php else: ?>
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <div style="margin-top: 50px; padding: 5px;">
                                <img src="<?php echo site_url('images/pool.jpg'); ?>" class="img-responsive"
                                     alt="Responsive image">
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div><!-- end container -->
        <script>
            $.datepicker.setDefaults($.datepicker.regional[<?php echo json_encode($lang); ?>]);
        </script>
        <?php if (request_get('adults')): ?>
            <script>
                $(function () {
                    $('html, body').animate({
                        scrollTop: $("#scroll").offset().top - 30
                    }, 900);
                });

                function displayAdded() {
                    $.get("<?php echo site_url('display_booked_rooms'); ?>", function (data) {
                        $('#displayAdded').html(data).fadeIn(1000);
                    });
                }

                displayAdded();

                $('.addRoom').on("submit", function (event) {
                    event.preventDefault();
                    var form_data = ( $(this).serialize() );
                    var button_content = $(this).find('button.add');
                    var key = button_content.data('added-key');
                    var x = $(this).find('select.available').val();
                    var sel = $(this).find("[name=quantity]");
                    if (x == 0) {
                        $(this).find('div.available').addClass("has-error");

                    } else {
                        var tr_action = "<?php echo tr_('добавяне');?>"
                        $('#avail-' + key).removeClass("has-error");
                        $('#avail-' + key).removeClass("has-success");
                        button_content.html('<i class="fa fa-circle-o-notch fa-spin"></i> '
                            + tr_action + ' ... ').prop('disabled', true);

                        $.ajax({
                            url: "<?php echo site_url('add_to_bag');?>",
                            method: "POST",
                            data: form_data,
                            success: function (result) {
                                displayAdded();

                                setTimeout(function () {
                                    var btn_cont = "<?php echo tr_('добави');?>"
                                    button_content.html(btn_cont).prop('disabled', false);
                                    $('html, body').animate({
                                        scrollTop: $("#scroll").offset().top - 30
                                    }, 900);
                                }, 500);

                                if (result.qty == 0) {
                                    var translate_1 = "<?php echo tr_('Няма повече налични стаи от тип');?>";

                                    var msg = '<div class="alert alert-danger alert-dismissible">'
                                        + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                        + '<span aria-hidden="true">&times;</span></button>'
                                        + translate_1 + ' ' + result.room_type + '</div>';
                                } else {
                                    var translate_2 = "<?php echo tr_('добавихте');?>";
                                    var translate_3 = "<?php echo tr_('стаи от тип');?>";

                                    var msg = '<div class="alert alert-success alert-dismissible">'
                                        + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                        + '<span aria-hidden="true">&times;</span></button>'
                                        + translate_2 + ' ' + result.qty + ' ' + translate_3 + ' ' + result.room_type
                                        + '</div>';
                                }
                                $('#add_tobag_msg').fadeIn(1000).html(msg);
                            }
                        });
                    }
                });

                //Delete added rooms
                $(document).on('click', '#btn_delete', function () {
                    var id = $(this).data("delete_id");
                    $.ajax({
                        url: "<?php echo site_url('delete_added')?>",
                        method: "POST",
                        data: {id: id},
                        dataType: "text",
                        success: function (result) {
                            displayAdded();
                            var al = '<div class="alert alert-success">' + result + '</div>';
                            $("#add_tobag_msg").html(al).fadeIn(200).fadeOut(5000);
                        }
                    });
                });

                function displaySelect(key) {
                    $('#avail-' + key).removeClass("has-error");
                    $('#avail-' + key).addClass("has-success");

                    $('[data-display="' + key + '"]').show(1000);
                }
            </script>
        <?php endif; ?>
<?php 
