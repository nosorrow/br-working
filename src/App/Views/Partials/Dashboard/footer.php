</div>
<!-- /#wrapper -->

<div class="text-center footer" style="color: #777">
    <p>&copy; 2017 Simple Booking Room Php Application</p>
    <p id="time"></p>
</div>
<script>


    function pending_rooms(){
        $.ajax({
            url:"<?php echo site_url('booking-dashboard/reservations/pending')?>",
            method:"GET",
            success:function(result){
                if(result !=0){
                    $("#pending-badge").html(result);
                    document.title = "("+result+" нова) Dashboard-BookingRooms";
                }
            }
        })
    }

    $(function(){
        setInterval(function () {
            pending_rooms();

        }, 30000)
    })

</script>
</body>
</html>