<?php
require 'includes/functions.php';
$message = '';
session_start();


if(!isset($_SESSION['loggedin']))
{
    header('Location: index.php');
    exit();
}

if(count($_FILES) > 0)
{
    $check = checkPost($_FILES);
    if($check !== true)
    {
        $message = '
        <div class="alert alert-danger text-center">
            '. $check .'
        </div>
        ';
    }
    else
    {
        if ($_GET['update_pp']) {
            updateProfile($_GET['update_pp'], $_FILES);
        } else {
            saveProfile($_SESSION['username'], $_FILES);
        }
        
    }
}

$profiles = getAllProfiles();


if (isset($_GET['edit_password'])) {
    $user = $_SESSION['username'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];   
    
    if ($new_password == $confirm_password && preg_match('/((?=.*[a-z])(?=.*[0-9])(?=.*[!?|@])){8}/', trim($new_password))) {
        updatePassword($user, $old_password, $new_password);
    } else {
        echo "invalid password, please try again";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>COMP 3015</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<div id="wrapper">

    <div class="container">

        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <h1 class="login-panel text-center text-muted">
                    COMP 3015 Assignment 2
                </h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <hr/>
                <?php echo $message; ?>
                <button class="btn btn-default" data-toggle="modal" data-target="#newPost"><i class="fa fa-comment"></i> New Profile</button>
                <a href="logout.php" class="btn btn-default pull-right"><i class="fa fa-sign-out"> </i> Logout</a>
                <hr/>
            </div>
        </div>

        <div class="row">
            <?php
            foreach($profiles as $profile)
            {
                echo '
                    <div class="col-md-4">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <span>
                                    '.$profile['username'].'
                                </span>
                ';

                if($_SESSION['admin'])
                {
                    echo '
                                <span class="pull-right text-muted">
                                    <a class="" href="edit.php?id='.$profile['id'].'">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                </span>
                                
                            ';
                }

                if($profile['username'] == $_SESSION['username'] || $_SESSION['admin'])
                {
                    echo '
                                <span class="pull-right text-muted" style="margin-right: 10px">
                                    <a class="" href="delete.php?id='.$profile['id'].'">
                                        <i class="fa fa-trash"></i> Delete
                                    </a>
                                </span>
                                
                            ';
                }
                if($profile['username'] == $_SESSION['username'])
                {
                    echo '
                        <span class="pull-right text-muted" style="margin-right: 10px">
                            <a class="" href="null" data-toggle="modal" data-target="#newPassword">
                                <i class="fa fa-gear"></i> Password
                            </a>
                        </span>
                        ';
                }

                    echo '
                            </div>
                            <div class="panel-body">
                                <p class="text-muted">
                                </p>
                                <img class="img-thumbnail" src="profiles/'.$profile['picture'].'"/>
                            </div>
                            <div class="panel-footer">
                                <p></p>
                            </div>
                        </div>
                    </div>
                ';
            }
            ?>

        </div>

    </div>
</div>

<div id="newPost" class="modal fade" tabindex="-1" role="dialog">
<div class="modal-dialog" role="document">
    <form role="form" method="post" action="profiles.php" enctype="multipart/form-data">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">New Profile</h4>
        </div>
        <div class="modal-body">
                <div class="form-group">
                    <label>Username</label>
                    <input class="form-control" value="<?php echo $_SESSION['username'];?>" disabled>
                </div>
                <div class="form-group">
                    <label>Profile Picture</label>
                    <input class="form-control" type="file" name="picture">
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-primary" value="Submit!"/>
        </div>
    </div><!-- /.modal-content -->
    </form>
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="newPassword" class="modal fade" tabindex="-1" role="dialog">
<div class="modal-dialog" role="document">
    <form role="form" method="post" action="profiles.php?edit_password=true" enctype="multipart/form-data">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">New Password</h4>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Old Password</label>
                <input class="form-control" name="old_password" value="">
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input class="form-control" name="new_password" value="">
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input class="form-control" name="confirm_password" value="">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-primary" value="Submit!"/>
        </div>
    </div><!-- /.modal-content -->
    </form>
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>
