<!-- /#page-wrapper -->
<div id="page-wrapper">
    <div class="row" style="padding-top: 10px">
        <div class="col-md-12" id="msg">
            <?php
            $msg = flash('msg');
            if ($msg) {
                echo alert('success', $msg);
            }
            ?>
        </div>
        <div class="col-md-12">
            <?php foreach ($types as $key => $type): ?>
                <?php $image = unserialize($type->img_type_url); ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="panel-title">Стая от тип - <?php echo $type->room_type; ?></h3>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="btn-group" role="group" aria-label="...">
                                    <a href="<?php echo site_url(route('room_types_edit', [$type->room_type_slug], 'get')) ?>"
                                       class="btn btn-default" role="button">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </a>
                                    <a href="<?php echo site_url(route('room_types_delete', [$type->room_type_slug, $type->id], 'get')) ?>"
                                       class="btn btn-danger" role="button"
                                       onclick="return confirm('Наистина ли ще изтриете този запис?');">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="<?php echo site_url('/') . $image[0]; ?>" alt="<?php echo $type->room_type; ?>"
                                     style="width: 300px; height: 200px;">
                            </div>
                            <div class="col-md-4">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <span class="room-type-properties"><i class="fa fa-info" aria-hidden="true"></i>
                                        брой:</span> <?php echo $type->quantity; ?>
                                    </li>
                                    <li class="list-group-item">
                                        <span class="room-type-properties"><i class="fa fa-bed" aria-hidden="true"></i>
                                        легла:</span> <?php echo $type->beds; ?>
                                    </li>
                                    <li class="list-group-item">
                                        <span class="room-type-properties"><i class="fa fa-users" aria-hidden="true"></i>
                                        макс. гости:</span> <?php echo $type->max_guests; ?>
                                    </li>
                                    <li class="list-group-item">
                                        <span class="room-type-properties"><i class="fa fa-user" aria-hidden="true"></i>
                                        възрастни:</span> <?php echo $type->adults; ?>
                                    </li>
                                    <li class="list-group-item">
                                        <span class="room-type-properties"><i class="fa fa-user" aria-hidden="true"></i>
                                        деца:</span> <?php echo $type->child; ?>
                                    </li>
                                    <li class="list-group-item">
                                        <span class="room-type-properties">
                                        цена:</span> <?php echo " делник: " . number_format($type->price_weekday, 2) . settings()->currency .
                                        ' ' . " уикенд: " . number_format($type->price_weekend, 2) . settings()->currency; ?>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <div class="well">
                                    <?php echo $type->description; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
            <div class="panel panel-default">
                <div class="panel-body text-center">
                    <div class="glyphicon-border">
                        <a href="<?php echo site_url(route('room_types_new')); ?>">
                            <span class="glyphicon glyphicon-plus" style="font-size: 30px; color:#999"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- /#page-wrapper -->
<script>
    $(document).ready(function () {
        $('.alert-success').fadeOut(2500);
        var maxHeight = 0;
        $(".equalize").each(function () {
            if ($(this).height() > maxHeight) {
                maxHeight = $(this).height();
            }
        });
        $(".equalize").each(function () {
            $(".equalize").height(maxHeight);
        });
    });
</script>
