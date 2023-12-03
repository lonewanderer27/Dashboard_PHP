<?php
include('config.php');
global $cn;

if (isset($_GET['token'])) {
    $id = $_GET['token'];
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $jobTitle = trim($_POST['jobTitle']);

    // check first if the updated values conflict with someone else's record
    $stmt = $cn->prepare("SELECT * FROM employees WHERE EmployeeFN = ? AND EmployeeLN = ? AND EmployeeID != ?");
    $stmt->bind_param("ssi", $fname, $lname, $id);
    $stmt->execute();
    $rs = $stmt->get_result();

    if ($rs->num_rows != 0) {
        // a duplicate record has been found! do not proceed
        header("location:index.php?conflictError=true");
        exit();
    }

    $stmt2 = $cn->prepare("UPDATE employees SET EmployeeFN = ?, EmployeeLN = ?, Employeeemail = ?, Employeephone = ?, JobTitle = ? WHERE EmployeeID = ?");
    $stmt2->bind_param("sssssi", $fname, $lname, $email, $phone, $jobTitle, $id);
    $stmt2->execute();

    // set the route
    if ($stmt2->affected_rows > 0) {
        header("location:index.php?success=true&update=true");
    } else {
        // indicate to get function that there has been an error
        header("location:index.php?updateError=true");
    }
} else {
    header("location:index.php?missingToken=true");
}
?>