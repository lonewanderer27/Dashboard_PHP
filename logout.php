<?php
if (isset($_POST['logout'])) {
    include('config.php');
    global $cn;

    // remove the session
    if (session_destroy()) {
        // set the route
        header("location:login.php");
    } else {
        header("location:index.php?logoutError=true");
    }
}
?>