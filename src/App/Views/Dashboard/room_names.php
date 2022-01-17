<div id="page-wrapper">
    <div class="row" style="padding-top: 10px">
        <div id="error" style="display: none"></div>
        <form id="room_name">
            <div class="col-md-3">
                <div class="form-group">
                    <input id="room_name" name="room_name" type="text" class="form-control" placeholder="Име">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <select name="room_type_id" class="form-control">
                        <option value="">тип на стаята</option>
                        <?php foreach ($types as $type): ?>
                            <option value="<?php echo $type['id']; ?>"><?php echo $type['room_type']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary" type="submit"><span class="fa fa-floppy-o"></span></button>
            </div>
        </form>
        <div class="col-md-3">

        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div id="fetch-names"></div>
        </div>
    </div>
</div>
<script>
        function fetch_names() {
            $.ajax({
                url: "<?php echo site_url('booking-dashboard/rooms/fetch');?>",
                method: "GET",
                data: "text",
                success: function (result) {
                    $("#fetch-names").html(result);

                }
            });
        }

        fetch_names();

        $("#room_name").on("submit", function (e) {
            e.preventDefault();
            var formData = $(this).serialize();
            var s = $('input[name="room_name"]').val();
            console.log(s);
            $.ajax({
                url: "<?php echo site_url('booking-dashboard/rooms/new');?>",
                method: "POST",
                data: formData,
                success: function (result) {
                    if (result == 1) {
                        $('input[name="room_name"]').val("");
                        fetch_names();
                    } else {
                        $('#error').html(result).fadeIn(200).fadeOut(5000);
                    }
                }
            });
        });

    // w3school
    function sortTable(n, a) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0, icon;
        table = document.getElementById("table");
        switching = true;
        switch (a) {
            case 1:
                icon = $("#name").find("span.sort");
                break;
            case 2:
                icon = $("#type").find("span.sort");
                break;
            case 3:
                icon = $("#room_status").find("span.sort");
                break;
        }
        console.log(a);
        //Set the sorting direction to ascending:
        dir = "asc";
        /*Make a loop that will continue until
         no switching has been done:*/
        while (switching) {
            //start by saying: no switching is done:
            switching = false;
            rows = table.getElementsByTagName("TR");
            /*Loop through all table rows (except the
             first, which contains table headers):*/
            for (i = 1; i < (rows.length - 1); i++) {
                //start by saying there should be no switching:
                shouldSwitch = false;
                /*Get the two elements you want to compare,
                 one from current row and one from the next:*/
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];
                /*check if the two rows should switch place,
                 based on the direction, asc or desc:*/
                if (dir == "asc") {
                    icon.removeClass('fa-sort-amount-desc');
                    icon.addClass('fa-sort-amount-asc');
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        //if so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir == "desc") {
                    icon.removeClass('fa-sort-amount-asc');
                    icon.addClass('fa-sort-amount-desc');
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        //if so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                /*If a switch has been marked, make the switch
                 and mark that a switch has been done:*/
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                //Each time a switch is done, increase this count by 1:
                switchcount++;
            } else {
                /*If no switching has been done AND the direction is "asc",
                 set the direction to "desc" and run the while loop again.*/
                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    }

    $('#statusModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var room = button.data('room');
        // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this);
        modal.find('.modal-title').text('редакция на стая ' + room);
        modal.find('.modal-body #room_name_modal').val(room);
        //  modal.find('.modal-body input[type="hidden"]').val(abteilung_id);
    });

    $("#modal_form").on("submit", function (e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: "<?php echo site_url('booking-dashboard/rooms/edit-status')?>",
            method: "POST",
            data: formData,
            success: function (result) {
                if (result == 0) {
                    console.log(result);

                    var errorMsg = '<div class="alert alert-info">нищо не е променено</div>'
                    $('#error').html(errorMsg).fadeIn(200).fadeOut(5000);
                } else {
                    location.reload();
                }
            }
        });
    });

    function deleteRoom(name){
        $.ajax({
            url:"<?php echo site_url('booking-dashboard/rooms/delete');?>",
            method:"POST",
            data:{room_name:name},
            success:function(result){
                var errorMsg;
                errorMsg = '<div class="alert alert-info">' + result +
                    '</div>';
                $('#error').html(errorMsg).fadeIn(200).fadeOut(5000);
                fetch_names();
            }
        })
    }
</script>