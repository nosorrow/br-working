<style>
    .amenities, .icon:hover{
        cursor: pointer;
    }
</style>
<div id="page-wrapper">
    <h3>Удобства</h3>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div>Системата използва иконите на
                            <a href="http://fontawesome.io/icons/" target="_blank">Font Awesome 4.7</a>
                            <sup data-toggle="tooltip" title="прочетете документацията">?</sup>
                        </div>
                        <p>Полето {font awesome} трябва да e името от Font Awesome - пример:
                            <code>
                                fa-coffee fa-2x
                            </code>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div id="live_data" class="col-md-12"></div>
        </div>
        <div class="row">
            <div id="result"></div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $(function () {
            $('[data-toggle="tooltip"]').bstooltip()
        });

        // Amenitites CRUD
        function fetch_data()
        {
            $.ajax({
                url: "<?php echo site_url('booking-dashboard/amenities/fetch')?>",
                method: "POST",
                success: function(data){
                    $('#live_data').html(data);
                }
            });
        }

        fetch_data();

        // add
        $(document).on('click', '#btn_add', function(){

            var amenities = $('#amenities').text();
            var icons = $('#icon').text();
            var amenities_en = $('#amenities_en').text();
            if(amenities.trim() == ''){
                $('#amenities').css({'border':'solid 1px red', 'color':'red'});

                return false;

            } else if (amenities.trim() != ''){
                $('#amenities').css({'border':'solid 1px green', 'color':'green'});

            }

            if(icons == ''){
                $('#icon').css({'border':'solid 1px red', 'color':'red'});
                return false;

            } else if (icons.trim() !== ''){

                $('#icon').css({'border':'solid 1px green', 'color':'green'});
            }

            $.ajax({
                url: "<?php echo site_url('booking-dashboard/amenities/new')?>",
                method: "post",
                data: {amenities: amenities, amenities_en: amenities_en, icon: icons},
                dataType:"text",
                success: function(data){
                    $('#result').html(data);
                    fetch_data();
                }
            });
        });

        // --- edit ---
        function edit_data(id, text, column)
        {
            $.ajax({
                url: "<?php echo site_url('booking-dashboard/amenities/edit')?>",
                method: "POST",
                data: {id: id, text: text, column: column},
                dataType:"text",
                success: function(data){
                    $('#result').html(data);
                }
            });
        }

        $(document).on('blur', '.icon', function(){
            var id = $(this).data('icon_id');
            var icon = $(this).text();
            edit_data(id, icon.trim(), 'icon');
        });

        $(document).on('blur', '.amenities', function(){
            var id = $(this).data('amenities_id');
            var amenities = $(this).text();
            edit_data(id, amenities.trim(), 'name');
        });

        $(document).on('blur', '.amenities_en', function(){
            var id = $(this).data('amenities_id_en');
            var amenities = $(this).text();
            edit_data(id, amenities.trim(), 'en_name');
        });

        // ----  delete -----------
        $(document).on('click', '#btn_delete', function(){
            var id=$(this).data("delete_id");
            if(confirm("Ще изтрием ли това?"))
            {
                $.ajax({
                    url:"<?php echo site_url('booking-dashboard/amenities/delete')?>",
                    method:"POST",
                    data:{id:id},
                    dataType:"text",
                    success:function(data){
                        alert(data);
                        fetch_data();
                    }
                });
            }
        });
    })
</script>