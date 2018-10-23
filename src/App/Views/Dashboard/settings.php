<div id="page-wrapper" class="">
    <h3>Настройки</h3>

    <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Основни</a>
            </li>
            <li role="presentation"><a href="#email" aria-controls="email" role="tab" data-toggle="tab">E-mail</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!--  ---------------------------------- Tabpanel Основни -------------------------------------------- -->
            <div role="tabpanel" class="tab-pane active" id="profile" style="margin: 10px 0;">
                <form id="basic_form">

                <div class="alert alert-info alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    Тук трябва да попълните вашите фирмени данни !
                </div>
                    <div class="row">
                        <div id="profile-result"></div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title"><strong>Профил</strong> -  <span id="hotelname"></span></h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-2 text-right text-right">Име на хотела</div>
                                <div class="col-md-9">
                                    <div id="profile_hotel_name_group" class="form-group">
                                        <input name="profile_hotel_name" type="text" class="form-control"
                                               id="profile_hotel_name" placeholder="hotel name">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 text-right">Адрес</div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input name="profile_address" type="text" class="form-control"
                                               id="profile_address"
                                               placeholder="address">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 text-right">Град</div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input name="profile_city" type="text" class="form-control" id="profile_city"
                                               placeholder="city">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 text-right">Държава</div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input name="profile_country" type="text" class="form-control"
                                               id="profile_country"
                                               placeholder="country">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 text-right">e-mail</div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input name="profile_email" type="text" class="form-control" id="profile_email"
                                               placeholder="e-mail">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 text-right">Телефон</div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input name="profile_telefon" type="text" class="form-control"
                                               id="profile_telefon"
                                               placeholder="Telefon">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 text-right">WWW</div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input name="profile_web" type="text" class="form-control" id="profile_web"
                                               placeholder="Web site">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title"><strong>Регионални</strong></h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-2 text-right text-right"> Валута</div>
                                <div class="col-md-9">
                                    <div id="currency" class="form-group">
                                        <select multiple name="currency" class="form-control" id="currency">
                                            <option id="lv" value="лв.">Лева</option>
                                            <option id="eur" value="eur">Евро</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 text-right text-right"> Уикенд</div>
                                <div class="col-md-9">
                                    <div id="weekend_group" class="form-group">
                                        <select name="weekend" class="form-control" id="weekend">
                                            <option value="5-6-7">петък - неделя</option>
                                            <option value="5-6">петък - събота</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <button type="submit" id="basic_save" class="btn btn-default btn-block">запази</button>
                        </div>
                        <div class="col-md-5">
                            <p id="basic_success_msg" style="margin:5px 0; display: none"></p>
                        </div>
                    </div>
                </form>
            </div>
            <!--  ---------------------------------- Tabpanel Email-------------------------------------------- -->
            <div role="tabpanel" class="tab-pane fade" id="email">
                <h4>Настройка e-mail</h4>

                <div class="alert alert-info alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>Info!</strong> Не правете промени на променливите
                    oградени с тиранти { }
                </div>
                <div>
                    <div class="form-group">
                            <textarea id="edit_email_text" name="edit_email_text">
                            <?php echo $email_view; ?>
                        </textarea>
                    </div>
                    <input type="submit" id="submit-email" name="submit-email" value="запази"
                           class="btn btn-default">
                </div>
                <div id="result-email"></div>
            </div>
            <!--  ---------------------------------- Tabpanel Основни-------------------------------------------- -->
            <div role="tabpanel" class="tab-pane" id="regional"></div>
            <!--  ---------------------------------- Tabpanel -------------------------------------------- -->
            <div role="tabpanel" class="tab-pane" id="settings">Настройки</div>
        </div>
    </div>
</div>
<script src="<?php echo site_url('ckeditor/ckeditor.js'); ?>" type="application/javascript"></script>
<script>
    $(document).ready(function () {

        var config = {
            codeSnippet_theme: 'Monokai',
            language: 'bg',
            height: 400,
            filebrowserBrowseUrl: '<?php site_url('Filemanager/index.html'); ?>',
            toolbarGroups: [
                {name: 'styles', groups: ['styles']},
                {name: 'colors', groups: ['colors']},
                {name: 'links'},
                {name: 'document', groups: ['mode', 'document', 'doctools']},
                {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']},
                {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},

            ],
            // Remove the redundant buttons from toolbar groups defined above.
            removeButtons: 'Save,NewPage,Print,Templates,Cut,Copy,' +
            'Paste,PasteText,PasteFromWord,Undo,Redo,Find,Replace,' +
            'SelectAll,Scayt,Form,Radio,TextField,Textarea,Select,' +
            'Button,ImageButton,HiddenField,Checkbox,About,' +
            'Image,Flash,Table,HorizontalRule,Smiley,SpecialChar,' +
            'PageBreak,Iframe,Anchor,RemoveFormat,BidiLtr,BidiRtl,Language'
        };

        CKEDITOR.replace('edit_email_text', config);

        $('#submit-email').click(function () {
            //  event.preventDefault();
            var email_text = CKEDITOR.instances.edit_email_text.getData();
            $.ajax({
                url: "<?php echo site_url(route('template_email'));?>",
                type: "POST",
                data: {edit_email_text: email_text},
                success: function (result) {
                    $('#result-email').html(result);
                }
            });
        });

//------ Ajax Basic Settings --------

        function fetch_profile() {
            $.get('<?php echo site_url(route('fetch_basic')); ?>', function (result) {
                if(result){
                    $('#hotelname').html(result.hotelname);
                    $('#profile_hotel_name').val(result.hotelname);
                    $('#profile_address').val(result.address);
                    $('#profile_city').val(result.city);
                    $('#profile_country').val(result.country);
                    $('#profile_telefon').val(result.phone);
                    $('#profile_email').val(result.email);
                    $('#profile_web').val(result.web);

                    if (result.currency == "eur"){
                        $('#eur').prop('selected', true);
                    } else if(result.currency == "лв."){
                        $('#lv').prop('selected', true);
                    }
                }
            });
        }

        fetch_profile();

        $('#basic_form').on('submit', function (e) {
            e.preventDefault();

            $('#basic_form input').focus(function () {
                $('#basic_save').prop('disabled', false).html('Запази');
                $('#basic_success_msg').fadeOut(500);
            });

            $('#basic_form select').change(function () {
                $('#basic_save').prop('disabled', false).html('Запази');
                $('#basic_success_msg').fadeOut(500);
            });

            var form_data = $('#basic_form').serialize();
            $.ajax({
                url: "<?php echo site_url(route('update_basic_settings', [], 'POST')); ?>",
                type: "POST",
                data: form_data,
                success: function (result) {
                    if (result == 1) {
                        $('#basic_success_msg').removeClass('alert-danger alert-info').addClass('alert alert-success').html('Промените са направени').fadeIn(500);
                        $('#basic_save').prop('disabled', true).html('Запазени');
                    } else if (result == 0) {
                        $('#basic_success_msg').removeClass('alert-danger alert-success').addClass('alert alert-info').html('Нищо не е променено').fadeIn(500);

                    } else {
                        $('#basic_success_msg').removeClass('alert-info alert-success').addClass('alert alert-danger').html('Няма направени промени').fadeIn(500);
                    }
                }

            });
        });

    });
</script>