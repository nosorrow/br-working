<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Dashboard-BookingRooms</title>
    <link rel="shortcut icon" href="<?php echo site_url('favicon.ico'); ?>">
    <link href="<?php echo site_url('DataTables/datatables.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo site_url('css/jquery.filer.css'); ?>" rel="stylesheet">
    <link href="<?php echo site_url('css/jquery.filer-dragdropbox-theme.css'); ?>" rel="stylesheet">
    <link href="<?php echo site_url('css/metisMenu/metisMenu.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo site_url('js/jquery-ui/themes/base/jquery-ui.css'); ?>" rel="stylesheet">
    <link href="<?php echo site_url('css/sb-admin-2.css'); ?>" rel="stylesheet">
    <link href="<?php echo site_url('css/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet"
          type="text/css">
    <link href="<?php echo site_url('css/jquery.comiseo.daterangepicker.css');?>" rel="stylesheet">
    <link href="<?php echo site_url('js/chosen-js/chosen.css');?>" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js" type="text/javascript"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js" type="text/javascript"></script>
    <![endif]-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo site_url('DataTables/datatables.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo site_url('js/jquery.filer.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo site_url('js/js.js'); ?>"></script>
    <script src="<?php echo site_url('js/filer.room.type.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo site_url('js/moment.min.js'); ?>" type="text/javascript"></script>
<!--    fix - (JqueryUI - bootstrap tooltip) collision -->
    <script>jQuery.fn.bstooltip = jQuery.fn.tooltip;</script>
    <script src="<?php echo site_url('js/jquery-ui/jquery-ui.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo site_url('js/jquery.comiseo.daterangepicker.js');?>"></script>
    <script src="<?php echo site_url('js/metisMenu/metisMenu.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo site_url('js/sb-admin-2.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo site_url('js/chosen-js/chosen.jquery.js'); ?>" type="text/javascript"></script>
</head>
<body>
<div id="wrapper">
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo site_url('booking-dashboard'); ?>">BR Контролен панел</a>
        </div>
        <!-- /.navbar-header -->
        <ul class="nav navbar-top-links navbar-right">
            <li>
                <a href="<?php echo site_url('/'); ?>">
                    <i class="fa fa-eye" aria-hidden="true"></i>&nbsp;
                    клиентска част
                </a>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                   aria-expanded="false"><i class="fa fa-user fa-fw"></i> потребител <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="#"><i class="fa fa-user fa-fw"></i> <?php echo sessionData('login.user'); ?></a>
                    </li>
                    <li><a href="<?php echo site_url('booking-dashboard/user-profile'); ?>"><i class="fa fa-gear fa-fw"></i> Настройки</a>
                    </li>
                    <li class="divider"></li>
                    <li><a href="<?php echo site_url('booking-dashboard/logout'); ?>"><i class="fa fa-sign-out fa-fw"></i> Изход</a>
                    </li>
                </ul>
            </li>
            <!-- /.dropdown -->
        </ul>
        <!-- /.navbar-top-links -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
                    <li>
                        <a href="<?php echo site_url('booking-dashboard') ?>"><i class="fa fa-tachometer fa-fw"></i> Табло</a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-files-o fa-fw"></i>
                            Резервации <span id="pending-badge" class="badge badge-pending"></span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="" href="<?php echo site_url(route('reservations_all', [], 'GET')); ?>">Преглед</a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('booking-dashboard/reservation/new'); ?>">Нова резервация</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-sitemap fa-fw"></i> Стаи<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="<?php echo site_url(route('room_types', [], 'get')); ?>">Типове стаи </a>
                            </li>
                            <li>
                                <a href="<?php echo site_url(route('room_names', [], 'get')); ?>">Стаи </a>
                            </li>
                            <li>
                                <a href="<?php echo site_url(route('amenities', [], 'get')); ?>">Удобства </a>
                            </li>
                            <li>
                                <a href="#"> Настройка цени<span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li>
                                        <a class="" href="<?php echo site_url(route('seasons', [], 'GET')); ?>">Сезони</a>
                                    </li>
                                    <li>
                                    </li>
                                </ul>
                                <!-- /.nav-third-level -->
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li>
                        <a href="<?php echo site_url(route('settings', [], 'GET')) ;?>"><i class="fa fa-cog fa-fw"></i> Настройки</a>
                    </li>

                    <li>
                        <a href="#"><i class="fa fa-user fa-fw"></i> Потребители<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="<?php echo site_url(route('users', [], 'get')); ?>">Преглед </a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('booking-dashboard/user-new'); ?>">Нов </a>
                            </li>
                            <li>
                                <a href="<?php echo site_url(route('user_profile', ['user'=>null], 'get')); ?>">Моят профил </a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                </ul>
            </div>
            <!-- /.sidebar-collapse -->
        </div>
        <!-- /.navbar-static-side -->
    </nav>
