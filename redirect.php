<?php
require 'includes/functions.php';

if(count($_POST) > 0)
{
    if($_GET['from'] == 'login')
    {
        $found = false; // assume not found

        $user = trim($_POST['username']);
        $pass = trim($_POST['password']);

        // admin login
        $ini_array = parse_ini_file("admin.ini");
        $admin_user = trim($ini_array['user']);
        $admin_password = trim($ini_array['password']);

        if(checkUsername($user))
        {
            $found = findUser($user, $pass);

            if($found)
            {
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $user;
                header('Location: thankyou.php?from=login&username='.filterUserName($user));
                exit();
            }
        } else if ($admin_user == $user && $admin_password == $pass) {
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user;
            $_SESSION['admin'] = true;
            header('Location: thankyou.php?from=login&username='.$admin_user);
            exit();
        }

        setcookie('error_message', 'Login not found! Try again.');
        header('Location: login.php');
        exit();
    }
    elseif($_GET['from'] == 'signup')
    {
        if(checkSignUp($_POST) && saveUser($_POST))
        {
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = trim($_POST['username']);
            header('Location: thankyou.php?from=signup&username='.filterUserName(trim($_POST['username'])));
            exit();
        }

        setcookie('error_message', 'Unable to sign up at this time.');
        header('Location: signup.php');
        exit();
    }
}

header('Location: index.php');
exit();
