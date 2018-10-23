<?php
?>
<div id="page-wrapper">
    <form class="form-inline" action="<?php echo site_url(route('insert_season', [], 'post')); ?>" method="post">
        <div class="row" style="margin-top: 10px">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="range">Период: </label>
                    <input name="range" id="range" type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label  for="season_name"> Име: </label>
                    <input name="season_name" type="text" class="form-control" id="season_name" placeholder="въведи име">
                </div>
            </div>
        </div>
        <hr>
        <div class="row" style="margin-top: 10px">
            <div class="col-md-12">
                <?php foreach ($room_type as $room): ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo "#" . $room->id . " " . $room->room_type; ?></h3>
                        </div>
                        <div class="panel-body">

                            <input type="hidden" name="room_type_id_<?php echo $room->id;?>"
                                   value="<?php echo $room->id;?>">
                            <div class="form-group">
                                <label class="" for="weekday_<?php echo $room->id;?>">Делник:</label>
                                <input name="weekday_<?php echo $room->id;?>" type="number"
                                       class="form-control" id="weekday_<?php echo $room->id;?>" placeholder="цена">
                            </div>
                            <div class="form-group">
                                <label class="" for="weekend_<?php echo $room->id;?>">Уикенд:</label>
                                <input name="weekend_<?php echo $room->id;?>" type="number"
                                       class="form-control" id="weekend_<?php echo $room->id;?>" placeholder="цена">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <input type="submit" class="btn btn-default btn-lg btn-block" value="Запази">
            </div>
        </div>
    </form>
</div>
<script>
    $(function () {
        var bgDay = ["н","п","в","с","ч","п","с"];
        var bgMonth = [ "Ян", "Фев", "Март", "Апр",
            "Май", "Юни", "Юли", "Авг", "Септ",
            "Окт", "Ноемв", "Дек" ];
        $("#range").daterangepicker({
            initialText:"Въведи период",
            presetRanges:  [],
            applyButtonText: 'запази',
            clearButtonText: 'чисти',
            cancelButtonText: 'отмяна',
            dateFormat:"yy-mm-dd",
            datepickerOptions: {
                monthNamesShort: bgMonth,
                monthNames: bgMonth,
                dayNamesMin: bgDay,
                minDate: null,
                maxDate: null,
                changeMonth: true,
                changeYear: true,
                numberOfMonths:3
            }
        });
    });
</script>