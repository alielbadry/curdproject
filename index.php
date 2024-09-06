<?php
include_once('db.php');
$action = false;

// Adding or editing a user
if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];

    // Check if the user already exists
    $check_sql = "SELECT * FROM `users` WHERE `email` = '$email' OR `mobile` = '$mobile'";
    $check_res = mysqli_query($conn, $check_sql);

    if ($_POST['save'] == "Save") {
        // Add new user
        $save_sql = "INSERT INTO `users`(`name`, `email`, `password`, `mobile`) VALUES 
              ('$name','$email','$password','$mobile')";
    } else {
        // Edit existing user
        $id = $_POST['id'];
        $save_sql = "UPDATE `users` SET `name`='$name',`email`='$email', `mobile`='$mobile', 
        `password`='$password' WHERE id = $id";
    }

    $res_save = mysqli_query($conn, $save_sql);
    if (!$res_save) {
        die(mysqli_error($conn));
    } else {
        $action = isset($_POST['id']) ? "edit" : "add";
    }
}

// Deleting a user
if (isset($_GET['action']) && $_GET['action'] == 'del') {
    $id = intval($_GET['id']);
    $del_sql = "DELETE FROM `users` WHERE id = $id";
    $res_del = mysqli_query($conn, $del_sql);
    if (!$res_del) {
        die(mysqli_error($conn));
    } else {
        // Redirect with action parameter
        header("Location: index.php");
        exit();
    }
}


// Fetch all users
$users_sql = "SELECT * FROM users";
$all_user = mysqli_query($conn, $users_sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users App</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/toastr.min.css">
</head>
<body>
    <div class="container">
        <div class="wrapper p-5 m-5">
            <div class="d-flex p-2 justify-content-between mb-2">
                <h2>All Users</h2>
                <div><a href="add_user.php"><i data-feather="user-plus"></i></a></div>
            </div>
            <hr>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Mobile</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $all_user->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['name']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['mobile']; ?></td>
                            <td>
                                <div class="d-flex p-2 justify-content-evenly mb-2">
                                <i onclick="confirm_delete(<?php echo $user['id']; ?>);" class="text-danger" data-feather="trash-2"></i>
                                    <i onclick="edit(<?php echo $user['id']; ?>);" class="text-success" data-feather="edit"></i>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="js/jquery.js"></script>
    <script src="js/toastr.js"></script>
    <script src="js/main.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/icons.js"></script>
    <script>
        feather.replace();
    </script>
    <?php 
        if ($action) {
            if ($action == 'add') { ?>
                <script>
                    show_add();
                </script>
            <?php } elseif ($action == 'del') { ?>
                <script>
                    show_del();
                </script>
            <?php } elseif ($action == 'edit') { ?>
                <script>
                    show_update();
                </script>
            <?php }
        }
    ?>
</body>
</html>
