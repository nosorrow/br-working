<div id="page-wrapper" style="padding-top: 10px">
    <div class="row">
        <div class="col-md-6">
            <h4>Сезони</h4>
        </div>
    </div>
    <div id="flash">
        <?php echo flash('msg'); ?>
    </div>
    <?php if (($seasons)): ?>
        <?php foreach ($seasons as $season): ?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <span style="font-weight: bold"><?php echo $season->season_name; ?></span>
                            <?php echo active_season_label($season->season_id); ?>
                        </div>
                        <div class="col-md-3">
                            <?php echo $season->start_date . ' - ' . $season->end_date; ?>
                        </div>
                        <div class="col-md-3">

                        </div>
                        <div class="col-md-3 text-right">
                            <div class="btn-group" role="group" aria-label="...">
                                <a href="<?php echo site_url(route('edit_season', [$season->season_id], 'get')) ?>"
                                   class="btn btn-default" role="button">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </a>
                                <button href="" class="btn btn-danger" role="button"
                                    onclick="delete_season(<?php echo $season->season_id;?>)">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php else: ?>
        <?php redirect(route('add_season')); ?>
    <?php endif; ?>
    <div class="panel panel-default">
        <div class="panel-body text-center">
            <div class="glyphicon-border-seasons" data-toggle="tooltip" data-placement="bottom" title="Нов сезон">
                <a href="<?php echo site_url(route('add_season')); ?>">
                    <span class="glyphicon glyphicon-plus" style="font-size: 20px; color:#999"></span>
                </a>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#flash").fadeOut(5000);
    });

    $(function () {
        $('[data-toggle="tooltip"]').bstooltip()
    });

    function delete_season(id) {
        confirm('Наистина ли ще изтриете този запис?');

        console.log(id);
        $.ajax({
            url: "<?php echo site_url('booking-dashboard/seasons/');?>" + id + '/delete',
            method: "GET",
            success: function (result) {
                if (result == 1) {
                    location.reload();
                }
            }
        });
    }
</script>
