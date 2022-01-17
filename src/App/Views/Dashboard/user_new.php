<?php
$button = "Нов";
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-md-8 col-md-offset-4">
            <h3>Нов потребител</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-4">
            <form id="new_user_form" method="post">
                <div class="row" style="margin-top: 10px">
                    <div class="col-md-6">
                        <div class="form-group<?php echo empty(validation_error('user')) ? '' :' has-error'; ?>">
                            <label for="user">Потребителско име</label>
                            <input name="user" type="text" class="form-control" id="user" placeholder="Потребителско име"
                                   value="<?php echo oldValue('user'); ?>">
                            <?php echo validation_error('user'); ?>
                        </div>
                        <div class="form-group<?php echo empty(validation_error('email')) ? '' :' has-error'; ?>">
                            <label for="email">Email адрес</label>
                            <input name="email" type="email" class="form-control" id="email" placeholder="Email"
                                   value="<?php echo oldValue('email'); ?>">
                            <?php echo validation_error('email'); ?>
                        </div>
                    </div>
                </div>

                <div class="row" id="pwd-container">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group<?php echo empty(validation_error('password')) ? '' :' has-error'; ?>">
                            <label for="password">Password</label>
                            <input name="password" type="text" class="form-control" id="password" placeholder="Парола">
                        </div>
                        <span class="pwstrength_viewport_verdict" style="padding-top: 10px"></span>
                        <?php echo validation_error('password'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <button type="button" class="btn btn-default" onclick="randomPassword(12)">генерирай нова парола</button>
                    </div>
                    <div class="col-md-3">
                        <div class="checkbox" id="show_password">
                            <label>
                                <input type="checkbox"> покажи / скриий
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6" style="margin-top: 15px">
                        <button type="submit" class="btn btn-info"><?php echo $button; ?></button>
                        <div id="result"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo site_url('js/pwstrength.js') ?>"></script>
<script>
    jQuery(document).ready(function () {
        "use strict";
        var options = {};
        options.ui = {
            //showPopover: true,
            showErrors: true,
         //   showProgressBar: true,
            showVerdictsInsideProgressBar: true,

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

    function randomPassword(length) {
        var chars = "q12w3e4r)5t6y7u8i9o0p-[=]azsxdc!@$%^&*fvgbhn>?jmkl./(QWERTYUIOPASDFGHJKLZXCVBNM<";
        var pass = "";
        for (var x = 0; x < length; x++) {
            var i = Math.floor(Math.random() * chars.length);
            pass += chars.charAt(i);
        }
        $("#password").val(pass);
    }

    randomPassword(12);

    $("#show_password").change(function () {
        if ( $("#password").prop('type') == "password"){
            $("#password").prop({type:"text"});

        } else {
            $("#password").prop({type:"password"});
        }
    })
</script>