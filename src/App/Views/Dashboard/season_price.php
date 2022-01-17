<?php
if (isset($season)) {
    extract($season);
    $_range['start'] = $start_date;
    $_range['end'] = $end_date;
    $range = json_encode($_range);
}
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h3><?php echo $season_name; ?></h3>

            <h3><?php echo $start_date . " - " . $end_date; ?></h3>
            <hr>
        </div>
    </div>
    <form id="update" class="form-inline" action="<?php echo site_url('booking-dashboard/seasons/update'); ?>" method="post">
        <div class="row" style="margin-top: 10px">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="start_date">Период: </label>
                    <input name="range" id="range" type="text" class="form-control" value='<?php echo $range ?>'>
                </div>
                <div class="form-group">
                    <label for="season_name"> Име: </label>
                    <input type="text" class="form-control" id="season_name" placeholder="въведи име"
                           name="season_name" value="<?php echo $season_name; ?>">
                </div>
                <input name="season_id" type="hidden" value="<?php echo $season_id; ?>">
            </div>
        </div>
        <hr>
        <div class="row" style="margin-top: 10px">
            <div class="col-md-12">
                <?php foreach ($room_type_price as $room): ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color:#555">
                                <strong><?php echo $room['room_type']; ?></strong></h3>
                        </div>
                        <div class="panel-body">
                                <div class="form-group">
                                    <label class="" for="weekday_<?php echo $room['room_type_id'] ?>" style="color:#777">Делник:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><?php echo settings()->currency(); ?></div>
                                        <input type="number" class="form-control"
                                               id="weekday_<?php echo $room['room_type_id'] ?>" placeholder="цена"
                                               name="weekday_<?php echo $room['room_type_id'] ?>"
                                               value="<?php echo $room['price_weekday']; ?>">
                                        <div class="input-group-addon">.00</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="" for="weekend_<?php echo $room['room_type_id'] ?>" style="color:#777">Уикенд:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><?php echo settings()->currency(); ?></div>
                                        <input type="number" class="form-control"
                                               id="weekend_<?php echo $room['room_type_id'] ?>" placeholder="цена"
                                               name="weekend_<?php echo $room['room_type_id'] ?>"
                                               value="<?php echo $room['price_weekend']; ?>">
                                        <div class="input-group-addon">.00</div>
                                    </div>
                                </div>
                            </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <input type="submit" class="btn btn-default" value="Запази">
            </div>
            <div class="col-md-9">
                <div id="result"></div>
            </div>
        </div>
    </form>
</div>
<script>
    $(document).ready(function () {
        var bgDay = ["н", "п", "в", "с", "ч", "п", "с"];
        var bgMonth = ["Ян", "Фев", "Март", "Апр",
            "Май", "Юни", "Юли", "Авг", "Септ",
            "Окт", "Ноемв", "Дек"];
        $("#range").daterangepicker({
            initialText: "Въведи период",
            presetRanges: [],
            applyButtonText: 'запази',
            clearButtonText: 'чисти',
            cancelButtonText: 'отмяна',
            dateFormat: "yy-mm-dd",
            datepickerOptions: {
                monthNamesShort: bgMonth,
                monthNames: bgMonth,
                dayNamesMin: bgDay,
                minDate: null,
                maxDate: null,
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 3
            }
        });

        $("#update").on("submit", function(e){

            e.preventDefault();

            var formData = $("#update").serialize();

            $.ajax({
                url: "<?php echo site_url('booking-dashboard/seasons/update')?>",
                method: "POST",
                data:formData,
                dataType:"text",
                success: function(result){
                    var htmlAlert = '<div class="alert alert-info">'+result+'</div>';
                    $("#result").html(htmlAlert).fadeOut(5000);
                    $("#result:hidden").html(htmlAlert).fadeIn(1000).fadeOut(5000);
                }
            });
        });

    });
</script>