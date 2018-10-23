<?php
$button = "Запази";
?>
<style>
    .result {
        margin: 5px;
        padding: 5px
    }
</style>
<div id="page-wrapper">
    <div class="row" style="margin-top: 10px">
        <div class="col-md-6">
            <form id="update_form" method="post">
                <div class="form-group<?php echo empty(validation_error('user')) ? '' :' has-error'; ?>">
                    <label for="user">Потребителско име</label>
                    <input name="user" type="text" class="form-control" id="user" placeholder="Потребителско име"
                           value="<?php echo $profile->user; ?>" readonly>
                    <?php echo validation_error('user'); ?>
                </div>
                <div class="form-group<?php echo empty(validation_error('email')) ? '' :' has-error'; ?>">
                    <label for="email">Email адрес</label>
                    <input name="email" type="email" class="form-control" id="email" placeholder="Email"
                           value="<?php echo $profile->email; ?>">
                    <?php echo validation_error('email'); ?>
                </div>
                <div id="password-group" class="input-group" style="display: none">
                    <input name="password" type="text" class="form-control" id="password" placeholder="нова парола">
                    <span class="input-group-btn">
                        <button id="show_password" class="btn btn-default" type="button">
                            <i class="fa fa-eye-slash" aria-hidden="true"></i>
                        </button>
                    </span>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="show_pass"> нова парола
                    </label>
                </div>
                <button type="submit" class="btn btn-default"><?php echo $button; ?></button>
            </form>
            <div id="result" class="result"></div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo site_url('js/pwstrength.js') ?>"></script>
<script>
    jQuery(document).ready(function () {
        "use strict";
        var options = {};
        options.ui = {
            showPopover: true,
            showErrors: true,
            showProgressBar: false,
            errorMessages: {
                password_too_short: "Паролата е твърде кратка",
                email_as_password: "Не използвайте имейла си като парола",
                same_as_username: "Паролата ви не може да съдържа потребителското ви име",
                two_character_classes: "Използвайте различни класове знаци",
                repeated_character: "Твърде много повторения",
                sequence_found: "Паролата ви съдържа последователности"
            },
            verdicts: ["Слаба", "Нормална", "Средна", "Силна", "Много силна"]
        };
        options.rules = {
            activated: {
                wordTwoCharacterClasses: true,
                wordRepetitions: true
            }
        };
        $('#password').pwstrength(options);
    });

    $('#show_pass').click(function () {
        if ($(this).is(':checked')) {
            $('#password-group').show(500);
        } else {
            $('#password-group').hide(500);
        }
    });

    $('#update_form').on('submit', function (e) {
        e.preventDefault();

        var form_data = $(this).serialize();

        $.ajax({
            url: "<?php echo site_url('booking-dashboard/user-profile-update');?>",
            method: "POST",
            data: form_data,
            success: function (result) {
                $('#result').html(result);
            }
        });
    });

    $("#show_password").click(function () {
        if ( $("#password").prop('type') == "password"){
            $("#password").prop({type:"text"});

        } else {
            $("#password").prop({type:"password"});
        }
    })
</script>