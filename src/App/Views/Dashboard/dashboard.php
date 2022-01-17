<style>
    .season_name{
        padding: 15px 10px 15px;
        margin: 3px;
        border: solid 1px green;
    }
</style>
<div id="page-wrapper">
    <div class="row" style="margin-top: 15px;">
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-sitemap fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $count_rooms; ?></div>
                            <div>стаи</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-user fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $capacity_adults; ?></div>
                            <div>възрастни</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-child fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $capacity_child; ?></div>
                            <div>деца</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-info-circle fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $occupied_room; ?></div>
                            <div>заети</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!--  -----------------  end I section --------------------------------------------------- -->
    <div class="row">

        <div class="col-md-4">

            <div class="panel panel-default equalize">
                <div class="panel-heading">
                    <h3 class="panel-title">текущи цени</h3>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                        <th></th>
                        <th>делник</th>
                        <th>уикенд</th>
                        </thead>
                        <?php foreach($room_type_price as $price): ?>
                            <tr>
                                <td><?php echo $price['room_type']; ?></td>
                                <td><?php echo $price['price_weekday'] . ' ' . settings()->currency; ?></td>
                                <td><?php echo $price['price_weekend'] . ' ' . settings()->currency; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    <?php if (!empty(active_season())): ?>
                    <div class="season_name">
                        Сезон: <?php echo active_season()->season_name . ' ('.active_season()->start_date .
                            ' - ' . active_season()->end_date . ') '; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default equalize">
                <div class="panel-heading">
                    <h3 class="panel-title">пристигащи днес</h3>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                        <th>резервация</th>
                        <th>стаи</th>
                        </thead>
                        <?php foreach ($arrivalToday as $reserveToday): ?>
                            <tr>
                                <td>
                                    <a href="<?php echo site_url('booking-dashboard/reservation/') . $reserveToday['reservation_id']; ?>">
                                        <?php echo $reserveToday['reservation_id']; ?>
                                    </a>

                                    <p><?php echo $reserveToday['client_name']; ?></p>
                                </td>
                                <td><?php echo $reserveToday['room_names']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default equalize">
                <div class="panel-heading">
                    <h3 class="panel-title">заминаващи днес</h3>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                        <th>резервация</th>
                        <th>стаи</th>
                        </thead>
                        <?php foreach ($departureToday as $departure): ?>
                            <tr>
                                <td>
                                    <a href="<?php echo site_url('booking-dashboard/reservation/') . $departure['reservation_id']; ?>">
                                        <?php echo $departure['reservation_id']; ?>
                                    </a>

                                    <p><?php echo $departure['client_name']; ?></p>
                                </td>
                                <td><?php echo $departure['room_names']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">текущи резервации</h3>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                        <th>резервация</th>
                        <th>стаи</th>
                        </thead>
                        <?php foreach ($unavailableToday as $reserve): ?>
                            <tr>
                                <td>
                                    <a href="<?php echo site_url('booking-dashboard/reservation/') . $reserve['reservation_id']; ?>">
                                        <?php echo $reserve['reservation_id']; ?>
                                    </a>
                                </td>
                                <td><?php echo $reserve['room_names']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <!--  ------------------ Charts   -->
    <div class="row">
        <div class="col-md-6">
            <div id="myfirstchart" style="height: 250px;"></div>
        </div>
    </div>
</div>
