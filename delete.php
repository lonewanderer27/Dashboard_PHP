<?php
include('config.php');
global $cn;

if (isset($_GET['token'])) {
    $id = $_GET['token'];

    // prepare the SQL statement
    $stmt = $cn->prepare("DELETE FROM employees WHERE EmployeeID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // set the route
    if ($stmt->affected_rows > 0) {
        header("location:index.php?success=true&delete=true");
    } else {
        // indicate to get function that there has been an error
        header("location:index.php?deleteError=true");
    }
} else {
    header("location:index.php?missingToken=true");
}
?>