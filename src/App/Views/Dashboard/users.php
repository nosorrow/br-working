<div id="page-wrapper">
    <div class="row">
        <div class="col-md-6">
            <h4>потребители:</h4>

            <div id="fetch-users">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Име</th>
                        <th>e-mail</th>
                        <th>статус</th>
                        <th>редакция</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['user']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo ($user['isonline'] == 1) ?
                                    '<span class="label label-danger">online</span>' :
                                    '<span class="label label-default">off</span>'; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo site_url(route('user_profile', ['user' => $user['user']], 'get')); ?>"
                                   type="button" class="btn btn-default">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                    редактира
                                </a>
                                <button type="button" class="btn btn-danger delete_button"
                                data-user_id="<?php echo $user['id']; ?>" data-user="<?php echo $user['user']; ?>">
                                    <i class="fa fa-trash" aria-hidden="true"></i> трие
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="result" class="row"></div>
</div>
<script>
    $(".delete_button").on("click", function (e) {
        e.preventDefault();
        var user_name = $(this).data('user');
        var user_id = $(this).data('user_id');
        if (confirm("Потребител [ " + user_name + " ] ще бъде изтрит")) {
            $.ajax({
                url: "<?php echo site_url('booking-dashboard/user-profile-delete');?>",
                method: "POST",
                data: {user: user_name, id:user_id},
                success: function (result) {
                    if (result == 1) {
                        location.reload();

                    } else {
                        $("#result").html(result).css('color', 'red');
                    }
                }
            })
        }

    })
</script>