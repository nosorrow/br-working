<div class="container">
    <?php echo flash('error'); ?>
</div>
<?php $flash = flash('order'); ?>
<?php if(isset($flash)): ?>
    <div class="container">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo tr_('Успешно изпратена резервация'); ?></h3>
            </div>
            <div class="panel-body">
                <div class="row result">
                    <div class="col-md-5 text-left">
                        <?php echo $flash['display']; ?>
                    </div><!-- end ol-md-5 -->
                    <div class="col-md-7"><!-- Start -->
                        <h4><?php echo $flash['name'] . ' ' . tr_('вашата резервация приключи успешно');?></h4>
                        <h4>e-mail: <?php echo $flash['email']; ?></h4>
                        <p><?php echo tr_('Вашият номер на резервация е'); ?> : <span style="font-size: larger;"><?php echo $flash['reservation_id']; ?></span></p>
                        <p><?php echo tr_('Ще получите e-mail с линк за потвърждаване на резервацията, който е валиден 10 мин'); ?> </p>
                        <div class="row">
                            <div class="col-md-3 col-md-offset-3" style="margin-top: 30px">
                                <a class="btn btn-success btn-lg" href="<?php echo site_url(route('search')); ?>">
                                    <?php echo tr_('Начало'); ?>
                                </a>
                            </div>
                        </div>
                    </div><!-- end col-md-7 -->
                </div><!-- End < row result > -->
            </div>
        </div>
    </div><!-- End Container -->
<?php else: ?>
    <div class="container">
        <div class="row" style="margin-top: 50px;">
            <div class="col-md-4 col-md-offset-4">
                <div class="well text-center">
                    <p><?php echo tr_('Нищо не е изпратено'); ?>!</p>
                    <a class="btn btn-success btn-lg" href="<?php echo site_url(route('search')); ?>"><?php echo tr_('Начало'); ?></a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<script>
    $(document).ready(function () {
        $(function () {
            $('html, body').animate({
                scrollTop: 180
            }, 900, 'swing');
        });
    });
</script>
