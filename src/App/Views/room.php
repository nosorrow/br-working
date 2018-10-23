<style>
    .animated {
        -webkit-animation-duration: 4s;
        animation-duration: 4s;
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both;
        -webkit-animation-name: fade-in;
        animation-name: fade-in;
    }

    @-webkit-keyframes fade-in {
        0% {
            opacity: 0.2;
        }
        100% {
            opacity: 1;
        }
    }

    @keyframes fade-in {
        0% {
            opacity: 0.2;
        }
        100% {
            opacity: 1;
        }
    }

</style>
<div class="container" id="scroll">
    <div class="row">
        <div class="col-md-12" id="add_tobag_msg" style="display: none"></div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <?php echo url()->link_back(tr_('Назад към търсенето'), 'btn btn-info btn-lg btn-block'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 room-img-big">
                    <?php foreach ($room['images'] as $image): ?>
                        <img class="img-responsive mySlides animated" src="<?php echo site_url('/').$image; ?>" width="600"
                             height="350">
                    <?php endforeach ?>
                </div>
            </div>
            <div class="row">
                <?php foreach ($room['images'] as $key => $image): ?>
                    <?php $i = $key + 1; ?>
                    <div class="col-md-3 room-img-small">
                        <img class="room-thumbnail img-responsive opacity-on opacity-off"
                             onclick="currentDiv(<?php echo $i; ?>)"
                             src="<?php echo site_url('/') . $image; ?>" width="250" height="150">
                    </div>
                <?php endforeach ?>
            </div>
        </div>
        <!-- ----------- Room info ---------- -->
        <div class="col-md-6">
            <div class="row">
                <h3 class="text-left room-h2">
                    <?php echo tr_('стая тип'); ?>: </strong><?php echo $room['room_type']; ?>
                </h3>
            </div>
            <div class="row">
                <div class="room-info">
                    <p><?php echo tr_('легла').': '. $room['beds']; ?></p>

                    <p><?php echo tr_('възрастни').': '.$room['adults']; ?></p>

                    <p><?php echo tr_('деца').': '. $room['child']; ?></p>

                    <p><?php echo $room['full_description']; ?></p>

                    <p style="letter-spacing: 2px; color:#286090">
                        <?php foreach ($room['amenities'] as $amenities): ?>
                            <?php echo '<i class="fa ' . $amenities['icon'] . ' fa-lg" aria-hidden="true"
                                data-toggle="tooltip" data-placement="top" title="' . $amenities['name'] . '"></i>' . "&nbsp;"; ?>
                        <?php endforeach; ?>
                    </p>
                    <h4><?php echo tr_('Цени за нощувка'); ?>:</h4>

                    <div class="row">
                        <div class="col-md-11 col-md-offset-1">
                            <table class="table table-stripped table-bordered">
                                <thead>
                                    <tr>
                                        <th><?php echo tr_('период'); ?></th>
                                        <th><?php echo tr_('делник'); ?></th>
                                        <th><?php echo tr_('уикенд'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td><?php echo $prices['without_season']['price_weekday'] ?></td>
                                        <td><?php echo $prices['without_season']['price_weekend'] ?></td>
                                    </tr>
                                    <?php foreach($prices['seasons'] as $season): ?>
                                        <tr>
                                            <td><?php echo $season['period'] ?></td>
                                            <td><?php echo $season['weekday'] ?></td>
                                            <td><?php echo $season['weekend'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- -------- end md-6 -->
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- If reservation -->
            <?php if ($result && $available !== 0): ?>
                <form class="form-horizontal" action="<?php echo site_url(route('booking', [], 'get')); ?>"
                      name="insert" method="GET">
                    <input type="hidden" name="r_k" value="<?php echo $r_k; ?>">

                    <div class="row" style="padding-left: 35px">
                        <div>
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><?php echo tr_('Вашата заявка за престой'); ?>:</h3>
                                </div>
                                <div class="panel-body">
                                    <p>
                                        <strong>
                                            <?php echo sessionData('dateStr') . ' | ' . tr_('възрастни') . ' '
                                                . sessionData('adults') . ' | ' . tr_('деца') . ' '
                                                . sessionData('child'); ?>
                                        </strong>
                                    </p>

                                    <div class="form-group text-center">
                                        <label for="quantity" class="col-sm-2 control-label">
                                            <?php echo tr_('брой'); ?>: </label>

                                        <div id="avail-<?php echo $key; ?>" class="col-md-4 available">
                                            <select name="quantity" class="form-control available"
                                                    onchange="displaySelect('<?php echo $r_k; ?>')">
                                                <option value="0">избери</option>
                                                <?php for ($i = 1; $i <= $available && $i <= 10; $i++): ?>
                                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group adults" style="display: none"
                                                 data-display="<?php echo $r_k; ?>">
                                                <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                                <select name="add_adults" class="form-control">
                                                    <?php for ($i = 1; $i <= $result['adults']; $i++): ?>
                                                        <option><?php echo $i; ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <?php if ($result['child'] != 0): ?>
                                                <div class="input-group child" style="display: none"
                                                     data-display="<?php echo $r_k; ?>">
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
                            </div>

                        </div>
                    </div>
                    <div class="row" style="padding-left: 35px">
                        <div class="col-md-12">
                            <div class="room-button">
                             <span class="input-group">
                        <button data-added-key="<?php echo $r_k; ?>" class="btn btn-primary btn-lg btn-block add"
                                type="submit">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                            <?php echo tr_('добави'); ?>
                        </button>
                    </span>
                            </div>
                        </div>
                    </div>
                </form>
            <?php else: ?>
                <div class="row">
                    <div class="col-md-4 col-md-offset-10">
                        <div class="room-button">
                             <span class="input-group">
                        <a href="<?php echo site_url(route('search')); ?>" class="btn btn-primary btn-lg">
                            <?php echo tr_('търси налични'); ?>
                        </a>
                    </span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div> <!-- end container -->
<script>
    var slideIndex = 1;
    showDivs(slideIndex);

    function plusDivs(n) {
        showDivs(slideIndex += n);
    }

    function currentDiv(n) {
        showDivs(slideIndex = n);
    }

    function showDivs(n) {
        var i;
        var x = document.getElementsByClassName("mySlides");
        var dots = document.getElementsByClassName("room-thumbnail");
        if (n > x.length) {
            slideIndex = 1
        }
        if (n < 1) {
            slideIndex = x.length
        }
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" opacity-off", "");
        }

        x[slideIndex - 1].style.display = "block";
        dots[slideIndex - 1].className += " opacity-off";
    }

    function displaySelect(key) {
        $('#avail-' + key).removeClass("has-error");
        $('#avail-' + key).addClass("has-success");

        $('[data-display="' + key + '"]').show(1000);
    }

    $('.form-horizontal').on("submit", function (event) {
        event.preventDefault();
        var form_data = ( $(this).serialize() );
        var button_content = $(this).find('button.add');
        var key = button_content.data('added-key');
        var x = $(this).find('select.available').val();
        if (x == 0) {
            //  alert('Error');
            $(this).find('div.available').addClass("has-error");

        } else {
            $('#avail-' + key).removeClass("has-error");
            $('#avail-' + key).removeClass("has-success");
            button_content.html('Добавяне...');

            $.ajax({
                url: "<?php echo site_url('add_to_bag'); ?>",
                method: "POST",
                data: form_data,
                success: function (result) {
                    button_content.html("<?php echo tr_('добави'); ?>");
                    $('html, body').animate({
                        scrollTop: $("#scroll").offset().top - 30
                    }, 900);
                    if (result.qty == 0) {
                        var msg = '<div class="alert alert-danger alert-dismissible">'
                            + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                            + '<span aria-hidden="true">&times;</span></button>'
                            + '<?php echo tr_('Няма повече налични стаи от тип');?> ' + result.room_type + '</div>';
                    } else {
                        var msg = '<div class="alert alert-success alert-dismissible">'
                            + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                            + '<span aria-hidden="true">&times;</span></button>'
                            + '<?php echo tr_('добавихте'); ?> ' + result.qty + ' <?php echo tr_('стаи от тип');?>: ' + result.room_type
                            + '</div>';
                    }
                    $('#add_tobag_msg').fadeIn(1000).html(msg);
                }
            });

        }
    });
</script>
