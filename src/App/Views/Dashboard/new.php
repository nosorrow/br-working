<?php
if (empty($type)) {
    $button = '<button type="submit" class="btn btn-primary btn-lg" style="width: 80%;">Въведи</button>';
    $title = 'Нов тип стая';
    $checked = 'checked';
} else {
    $button = '<button type="submit" class="btn btn-primary btn-lg" style="width: 80%;">Обнови</button>';
    $title = 'Редакция тип стая';
    list($id, $room_type, $room_type_en, $room_type_slug, $description, $full_description,
        $description_en, $full_description_en,
        $beds, $beds_en, $adults, $child, $max_guests, $price_weekday, $price_weekend,
        $quantity, $img_type_url) = array_values($type);
}
?>
<style>
    sup[data-toggle="tooltip"] {
        cursor: help;
    }
</style>

<!-- / #page-wrapper /  -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-header"><?php echo $title; ?></h4>
            </div>
        </div>
        <div class="row" style="padding: 5px">
            <div class="col-md-12">
                <?php
                $msg = flash('msg');
                if ($msg) {
                    echo alert('danger', $msg);
                }
                ?>
            </div>
        </div>
    </div>
    <form method="post" action="" enctype="multipart/form-data"><! -- Form -->
        <div class="container-fluid">
            <!-- I -->
            <div class="row">
                <div class="col-md-9">
                    <div>
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#bg" aria-controls="bg" role="tab" data-toggle="tab">Български</a></li>
                            <li role="presentation"><a href="#english" aria-controls="english" role="tab" data-toggle="tab">English</a></li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="bg">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div
                                            class="form-group<?php echo empty(validation_error('room_type')) ? '' :' has-error'; ?>">
                                            <label for="room_type">Тип стая</label>
                                            <input name="room_type" type="text" class="form-control" id="room_type"
                                                   value="<?php echo oldValue('room_type') ? oldValue('room_type') :$room_type; ?>"
                                                   placeholder="стая от тип">
                                            <?php echo validation_error('room_type'); ?>
                                        </div>
                                        <div class="form-group<?php echo empty(validation_error('slug')) ? '' :' has-error'; ?>">
                                            <label for="slug" class="control-label">Permalink :</label>
                                            <?php echo site_url('/room/'); ?>
                                            <input id="slug" name="slug" type="text"
                                                   value="<?php echo oldValue('slug') ? oldValue('slug') :$room_type_slug; ?>">
                                            <?php echo validation_error('slug'); ?>
                                        </div>
                                        <div class="form-group<?php echo empty(validation_error('beds')) ? '' :' has-error'; ?>">
                                            <label for="beds">Легла</label>
                                            <input name="beds" type="text" class="form-control" id="beds"
                                                   value="<?php echo oldValue('beds') ? oldValue('beds') :$beds; ?>"
                                                   placeholder="легла">
                                            <?php echo validation_error('beds'); ?>
                                        </div>
                                        <!-- row 1 -->
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <div
                                                    class="form-group<?php echo empty(validation_error('guests')) ? '' :' has-error'; ?>">
                                                    <label for="guests">Брой гости<span><sup data-toggle="tooltip"
                                                                                             data-placement="top"
                                                                                             title="Максимален брой гости в стая">(?)</sup></span></label>
                                                    <input name="guests" type="number" class="form-control" id="guests"
                                                           value="<?php echo oldValue('guests') ? oldValue('guests') :$max_guests; ?>"
                                                           placeholder="Брой гости">
                                                    <?php echo validation_error('guests'); ?>
                                                </div>
                                            </div>
                                            <div class="col-xs-4">
                                                <div
                                                    class="form-group<?php echo empty(validation_error('adults')) ? '' :' has-error'; ?>">
                                                    <label for="guests">възрастни<span><sup data-toggle="tooltip"
                                                                                            data-placement="top"
                                                                                            title="Брой възрастни в стая">(?)</sup></span></label>
                                                    <input name="adults" type="number" class="form-control" id="adults"
                                                           value="<?php echo oldValue('adults') ? oldValue('adults') :$adults; ?>"
                                                           placeholder="възрастни">
                                                    <?php echo validation_error('adults'); ?>
                                                </div>
                                            </div>
                                            <div class="col-xs-4">
                                                <div
                                                    class="form-group<?php echo empty(validation_error('child')) ? '' :' has-error'; ?>">
                                                    <label for="guests">деца<span><sup data-toggle="tooltip" data-placement="top"
                                                                                       title="Брой деца в стая">(?)</sup></span></label>
                                                    <input name="child" type="number" class="form-control" id="child"
                                                           value="<?php echo oldValue('child') ? oldValue('child') :$child; ?>"
                                                           placeholder="деца">
                                                    <?php echo validation_error('child'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <div class="form-group">
                                                    <label for="units">Брой стаи от типа</label>
                                                    <input name="units" type="number" class="form-control" id="units"
                                                           value="<?php echo $quantity ? $quantity :1 ?>" placeholder="брой">
                                                </div>
                                            </div>
                                            <div class="col-xs-4">
                                                <div
                                                    class="form-group<?php echo empty(validation_error('price_weekday')) ? '' :' has-error'; ?>">
                                                    <label for="price_weekday">Цена-делник</label>
                                                    <input name="price_weekday" type="text" class="form-control" id="price_weekday"
                                                           value="<?php echo oldValue('price_weekday') ? oldValue('price_weekday') :$price_weekday; ?>"
                                                           placeholder="Цена">
                                                    <?php echo validation_error('price_weekday'); ?>
                                                </div>
                                            </div>
                                            <div class="col-xs-4">
                                                <div
                                                    class="form-group<?php echo empty(validation_error('price_weekend')) ? '' :' has-error'; ?>">
                                                    <label for="price_weekend">Цена-уикенд</label>
                                                    <input name="price_weekend" type="text" class="form-control" id="price_weekend"
                                                           value="<?php echo oldValue('price_weekend') ? oldValue('price_weekend') :$price_weekend; ?>"
                                                           placeholder="Цена">
                                                    <?php echo validation_error('price_weekend'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end row 2 -->
                                        <!-- ---------- ADD Amenities -------- -->
                                        <div class="row">
                                            <div class="panel panel-default">
                                                <div class="panel-body">
                                                    <?php foreach (amenities() as $amenities): ?>
                                                        <?php $checked = in_array($amenities['id'], $amenitie_id)? 'checked':''; ?>
                                                        <div class="col-md-3">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input name="amenities[]" value="<?php echo $amenities['id']; ?>" type="checkbox" <?php echo $checked; ?> />
                                                                    <?php echo $amenities['name']; ?>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End amenities -->
                                        <div
                                            class="form-group<?php echo empty(validation_error('description')) ? '' :' has-error'; ?>">
                                            <label class="control-label">Кратко описание</label>
                                <textarea id="description" name="description" class="form-control" rows="20">
                                    <?php echo oldValue('description') ? oldValue('description') :$description; ?>
                                </textarea>
                                            <?php echo validation_error('description'); ?>
                                        </div>
                                        <div
                                            class="form-group<?php echo empty(validation_error('full_description')) ? '' :' has-error'; ?>">
                                            <label class="control-label">Подробно описание</label>
                                <textarea id="full_description" name="full_description" class="form-control" rows="20">
                                    <?php echo oldValue('full_description') ? oldValue('full_description') :$full_description; ?></textarea>
                                            <?php echo validation_error('full_description'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
<!-- ---------------------   TAB ENGLISH ----->
                            <div role="tabpanel" class="tab-pane" id="english">
                                <div class="col-md-12">
                                    <div
                                        class="form-group<?php echo empty(validation_error('room_type_en')) ? '' :' has-error'; ?>">
                                        <label for="room_type">Room type</label>
                                        <input name="room_type_en" type="text" class="form-control" id="room_type_en"
                                               value="<?php echo oldValue('room_type_en') ? oldValue('room_type_en') :$room_type_en; ?>"
                                               placeholder="room type">
                                        <?php echo validation_error('room_type_en'); ?>
                                    </div>

                                    <div class="form-group<?php echo empty(validation_error('beds_en')) ? '' :' has-error'; ?>">
                                        <label for="beds_en">Beds</label>
                                        <input name="beds_en" type="text" class="form-control" id="beds_en"
                                               value="<?php echo oldValue('beds_en') ? oldValue('beds_en') :$beds_en; ?>"
                                               placeholder="beds">
                                        <?php echo validation_error('beds_en'); ?>
                                    </div>

                                    <div class="form-group<?php echo empty(validation_error('description_en')) ? '' :' has-error'; ?>">
                                        <label class="control-label">Description</label>
                                <textarea id="description_en" name="description_en" class="form-control" rows="20">
                                    <?php echo oldValue('description_en') ? oldValue('description_en') :$description_en; ?>
                                </textarea>
                                        <?php echo validation_error('description_en'); ?>
                                    </div>
                                    <div
                                        class="form-group<?php echo empty(validation_error('full_description_en')) ? '' :' has-error'; ?>">
                                        <label class="control-label"Full Description</label>
                                <textarea id="full_description_en" name="full_description_en" class="form-control" rows="20">
                                    <?php echo oldValue('full_description_en') ? oldValue('full_description_en') :$full_description_en; ?></textarea>
                                        <?php echo validation_error('full_description_en'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
<!--  ------------------ start Form -------------------------------------- -->
                    <!-- /.row -->
                    <div class="row">

                    </div>
                    <div class="row text-center">
                        <?php echo $button; ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <h3>Снимка:</h3>
                    </div>
                    <div class="row">
                        <div id="content">
                            <input type="file" name="files[]" id="filer_input2" multiple="multiple">
                        </div>
                    </div>
                </div>
            </div><!-- End I -->
        </div>
        <!-- /.container-fluid -->
    </form>
    <! -- End Form -->
</div>
<!-- /#page-wrapper -->
<script src="<?php echo site_url('ckeditor/ckeditor.js'); ?>"></script>

<script>
    $(document).ready(function () {
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

    var config = {
        codeSnippet_theme: 'Monokai',
        language: 'bg',
        height: 200,
//        filebrowserBrowseUrl: 'http://booking-room.manu/Filemanager/index.html',
        toolbarGroups: [
            {name: 'styles', groups: ['styles']},
            {name: 'colors', groups: ['colors']},
            {name: 'links'},
            {name:'insert', groups:['insert']},
            {name: 'document', groups: ['mode', 'document', 'doctools']},
            {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']},
            {name: 'basicstyles', groups: ['basicstyles', 'cleanup']}

        ],
//        allowedContent: 'img[alt, src]{width,height,float}'

        // Remove the redundant buttons from toolbar groups defined above.
        removeButtons: 'Save,NewPage,Print,Templates,Cut,Copy,' +
        'Paste,PasteText,PasteFromWord,Undo,Redo,Find,Replace,' +
        'SelectAll,Scayt,Form,Radio,TextField,Textarea,Select,' +
        'Button,ImageButton,HiddenField,Checkbox,About,' +
        'Image,Flash,Table,HorizontalRule,Smiley,SpecialChar,' +
        'PageBreak,Iframe,Anchor,RemoveFormat,BidiLtr,BidiRtl,Language'
    };

    CKEDITOR.replace('description', config);
    CKEDITOR.replace('full_description', config);
    CKEDITOR.replace('description_en', config);
    CKEDITOR.replace('full_description_en', config);

    $("#room_type").keyup(function () {
        var str = url_slug($(this).val());
        $("#slug").val(str);
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
