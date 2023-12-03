<?php
include('config.php');
global $cn, $fname, $lname, $email, $phone, $jobTitle;
$date_now = date("Y-m-d");

if (isset($_POST['fname'])) {
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $jobTitle = trim($_POST['jobTitle']);

    // find duplicates first
    $stmt = $cn->prepare("SELECT EmployeeID FROM employees WHERE EmployeeFN = ? AND EmployeeLN = ?");
    $stmt->bind_param("ss", $fname, $lname);
    $stmt->execute();
    $rs = $stmt->get_result();

    if ($rs->num_rows == 0) {
        // no duplicate record has been found! proceed
        $stmt2 = $cn->prepare("INSERT INTO employees (EmployeeFN, EmployeeLN, EmployeeEmail, EmployeePhone, HireDate, ManagerID, JobTitle)
            VALUES (?, ?, ?, ?, ?, 50, ?)");
        $stmt2->bind_param("ssssss", $fname, $lname, $email, $phone, $date_now, $jobTitle);
        $stmt2->execute();

        // set the route
        header("location:index.php?success=true&add=true");

        // indicate to get function that there has been an error
        if ($stmt2->error) {
            header("location:index.php?addError=true");
        }
    } else {
        // return duplicate error as well as the values so that it can be displayed again in index.php page!
        header("location:index.php?duplicate=true&fname=$fname&lname=$lname&email=$email&phone=$phone&jobTitle=$jobTitle");
    }
}
?>