<div class="container">
    <div id="scroll" class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-info-sign"></span>
                        <?php echo tr_('Информация за резервацията');?></h3>
                </div>
                <div class="col-md-6 text-right">
                    <?php echo url()->link_back('<span class="glyphicon glyphicon-arrow-left"></span>', 'btn btn-info'); ?>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <!-- ------------------------------------- bookig --------------------------------------- -->
            <div class="row">
                <?php echo flash('error'); ?>
            </div>

            <div class="panel panel-default">
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-5">
                            <?php echo $display; ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="ajax"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">

                            <div id="load" class="load" style="display: none">
                                <h3><?php echo tr_('изпраща се информация'); ?> ...... </h3>

                                <p><img src="<?php echo site_url('images/spinner.gif'); ?>"></p>
                            </div>

                            <div class="row form-size"><!-- Start Form -->
                                <form onsubmit="ShowLoading()" method="post" action="">
                                    <?php csrf_field(); ?>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <div
                                                class="form-group <?php echo empty(validation_error('name')) ? '' :' has-error'; ?>">
                                                <label for="name"><?php echo tr_('име'); ?> *</label>
                                                <input id="name" name="name" type="text" class="form-control" placeholder="<?php echo tr_('име');?>"
                                                       value="<?php echo oldValue('name'); ?>">
                                                <?php echo validation_error('name'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div
                                                class="form-group<?php echo empty(validation_error('email')) ? '' :' has-error'; ?>">
                                                <label for="email">e-mail *</label>
                                                <input id="email" name="email" type="text" class="form-control" placeholder="e-mail"
                                                       value="<?php echo oldValue('email'); ?>">
                                                <?php echo validation_error('email'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <div
                                                class="form-group<?php echo empty(validation_error('telefon')) ? '' :' has-error'; ?>">
                                                <label for="telefon"><?php echo tr_('телефон'); ?> *</label>
                                                <input id="telefon" name="telefon" type="text" class="form-control"
                                                       placeholder="<?php echo tr_('телефон'); ?>"
                                                       value="<?php echo oldValue('telefon'); ?>">
                                                <?php echo validation_error('telefon'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div
                                                class="form-group<?php echo empty(validation_error('text')) ? '' :' has-error'; ?>">
                                                <label for="text"><?php echo tr_('коментар'); ?></label>
                                                <textarea id="textarea" name="text" class="form-control" rows="4"></textarea>
                                                <?php echo validation_error('text'); ?>
                                                <div id="count"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 col-xs-offset-6 text-right">
                                            <input id="submit" type="submit" class="btn btn-primary btn-lg"
                                                   value="<?php echo tr_('резервирай'); ?>">
                                        </div>
                                    </div>
                                </form>
                            </div><!-- -------------- End Form --------------- -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- ------------------------------------- bookig --------------------------------------- -->
        </div>
    </div><!-- end container - panel -->

</div><!-- end container -->

<script>
    $(document).ready(function () {

        $(function () {
            $('html, body').animate({
                scrollTop: $("#scroll").offset().top - 55
            }, 900);
        });

        function check_avail() {
            setInterval(function () {
                $.ajax({
                    url: "<?php echo site_url('ajaxCheck'); ?>",
                    success: function (result) {
                        if (result == 0) {
                            var msg = '<div class="alert alert-danger">' +
                                '<?php echo tr_('Някоя от стаите току що бе резервирана от друг. Моля потърсете отново за свободни стаи!');?>' +
                                '</div>';
                            $('#name, #email, #telefon, #textarea, #submit').prop('disabled', true);
                            $("#ajax").html(msg);
                        } else {
                            var msg = '<div class="alert alert-success">'+ '<?php echo tr_('Избраните от вас стаи все още са свободни');?>'+'</div>';
                            $('#name, #email, #telefon, #textarea, #submit').prop('disabled', false);
                            $("#ajax").html(msg);
                        }
                    }
                });
            }, 3000);
        }

        check_avail();

        $("#textarea").keyup(function () {
            var len = $(this).val().length;
            $("#count").text((250 - len) + " <?php echo tr_('от');?> 250");
        });
    });

    function ShowLoading() {
        $('#load').show().css('cursor', 'wait');
        $('#submit').prop('disabled', true);
        return true;
    }
</script>
<?php //dump($_SESSION); ?>