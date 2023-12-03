<?php
global $cn, $ROLE, $ADMIN, $AD_ADDRESS, $AD_EMAIL, $AD_PASSWORD, $AD_PHONE, $EMAIL, $PASSWORD, $PHONE, $ADDRESS, $APPROVED;

// check if there's already an admin
$stmt = $cn->prepare("SELECT * FROM users WHERE $ROLE = ?");
$stmt->bind_param("s", $ADMIN);
$stmt->execute();
$rs = $stmt->get_result();

if ($rs->num_rows == 0) {
    // clear the session
    session_destroy();

    // no admin found! create one
    // hash the password
    $stmt2 = $cn->prepare("INSERT INTO users ($EMAIL, $PASSWORD, $PHONE, $ADDRESS, $ROLE, $APPROVED) VALUES (?, SHA2(?, 256), ?, ?, ?, ?)");
    $role = $ADMIN;
    $approved = 1;
    $stmt2->bind_param("ssssss", $AD_EMAIL, $AD_PASSWORD, $AD_PHONE, $AD_ADDRESS, $role, $approved);
    $stmt2->execute();

    if ($stmt2->error) {
        header("location:login.php?adminError=true");
    } else {
        header("location:login.php?adminCreated=true");
    }
}
?>