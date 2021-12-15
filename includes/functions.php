<?php
define('SALT', '2021_go_leafs_go');
define('FILE_SIZE_LIMIT', 4000000);

define('DB_HOST',     '127.0.0.1');
define('DB_PORT',     '8889');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_DATABASE', 'comp3015');

function connect()
{
    $link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    if (!$link)
    {
        echo mysqli_connect_error();
        exit;
    }

    return $link;
}

/**
 * Look up the user & password pair from the database.
 *
 * Passwords are simple md5 hashed, but salted.
 *
 * Remember, md5() is just for demonstration purposes.
 * Do not do this in production for passwords.
 *
 * @param $user string The username to look up
 * @param $pass string The password to look up
 * @return bool true if found, false if not
 */
function findUser($user, $pass)
{
    $found = false;

    $link = connect();
    $hash = md5($pass . SALT);

    $query   = 'select * from accounts where username = "'.$user.'" and password = "'.$hash.'"';
    $results = mysqli_query($link, $query);

    if (mysqli_fetch_array($results))
    {
        $found = true;
    }

    mysqli_close($link);
    return $found;
}

/**
 * Remember, md5() is just for demonstration purposes.
 * Do not do this in production for passwords.
 *
 * @param $data
 * @return bool
 */
function saveUser($data)
{
    $username   = trim($data['username']);
    $password   = md5($data['password']. SALT);

    $link    = connect();
    $query   = 'insert into accounts(username, password) values("'.$username.'","'.$password.'")';
    $success = mysqli_query($link, $query); // returns true on insert statements

    mysqli_close($link);
    return $success;
}

function checkUsername($username)
{
    return preg_match('/^([a-z]|[0-9]){8,15}$/i', $username);
}

/**
 * @param $data
 * @return bool
 */
function checkSignUp($data)
{
    $valid = true;

    // if any of the fields are missing
    if( trim($data['username'])        == '' ||
        trim($data['password'])        == '' ||
        trim($data['verify_password']) == '')
    {
        $valid = false;
    }
    elseif(!checkUsername(trim($data['username'])))
    {
        $valid = false;
    }
    elseif(!preg_match('/((?=.*[a-z])(?=.*[0-9])(?=.*[!?|@])){8}/', trim($data['password'])))
    {
        $valid = false;
    }
    elseif($data['password'] != $data['verify_password'])
    {
        $valid = false;
    }

    return $valid;
}

function filterUserName($name)
{
    // if it's not alphanumeric, replace it with an empty string
    return preg_replace("/[^a-z0-9]/i", '', $name);
}

/**
 * @param $file
 * @return bool
 */
function checkPost($file)
{
    if($file['picture']['size'] < FILE_SIZE_LIMIT && $file['picture']['type'] == 'image/jpeg')
    {
        return true;
    }

    return 'Unable to upload profile picture!';
}

/**
 * @param $username
 * @param $file
 * @return bool
 */
function saveProfile($username, $file)
{
    $picture = md5($username.time());
    $moved   = move_uploaded_file($file['picture']['tmp_name'], 'profiles/'.$picture);

    if($moved)
    {
        $link   = connect();
        $query  = 'insert into profiles(username, picture) values("'.$username.'","'.$picture.'")';
        $result = mysqli_query($link, $query);

        mysqli_close($link);
        return $result;
    }

    return false;
}

/**
 * @return bool|mysqli_result
 */
function getAllProfiles()
{
    $link     = connect();
    $query    = 'select * from profiles order by username';
    $profiles = mysqli_query($link, $query);

    mysqli_close($link);
    return $profiles;
}

/**
 * Delete a profile based on the ID and username combination
 *
 * @param $id
 * @param $username
 * @return bool returns true on deletion success or false on failure
 */
function deleteProfile($id)
{
    $link    = connect();
    $query   = 'delete from profiles where id = "'.$id.'"';
    $success = mysqli_query($link, $query);

    mysqli_close($link);
    return $success;
}

function updatePassword($user, $old_pass, $new_pass)
{

    $hash_old_pass = md5($old_pass . SALT);
    $hash_new_pass = md5($new_pass . SALT);
    
    $link     = connect();

    $query    = 'update accounts set password = "'. $hash_new_pass .'" where password = "'. $hash_old_pass .'" and username = "'. $user .'"';
    
    $success = mysqli_query($link, $query);
    mysqli_close($link);
    
    return $success;

}

function getProfile($id)
{
    $link    = connect();
    $query   = 'select * from profiles where id = "'.$id.'"';
    $success = mysqli_query($link, $query);
    $data = []; 

    while($row = mysqli_fetch_array($success)) 
    {
        return $row;
    }

    mysqli_close($link);
    // return $row;
}