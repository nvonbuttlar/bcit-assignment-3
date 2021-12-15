<?php 
  require 'includes/functions.php';

  session_start();

  if($_GET['id']) {
    // echo "HELLO";
    // var_dump($_GET);

    $profile = getProfile($_GET['id']);

    var_dump($profile);

  } else {
    echo "NOOO";
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

<div id="editProfile">
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
                    <input class="form-control" value="<?php echo $profile['username'] ?>" disabled>
                </div>
                <div class="form-group">
                    <label>Current Picture</label>
                    <img class="img-thumbnail" type="file" name="picture" src="<?php echo 'profiles/' . $profile['picture'] ?>">
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
</body>
</html>
