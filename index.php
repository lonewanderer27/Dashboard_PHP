<?php
include 'config.php';
include 'constants.php';
include 'create_admin.php';

// === SESSION RELATED CODE ===

// Start a session
session_start();
//var_dump($_SESSION);

global $cn, $EMAIL, $APPROVED, $USER, $ADMIN, $ADDRESS, $PHONE, $PASSWORD, $ROLE;

// Check if the user is not authenticated using the session
if (!isset($_SESSION[$EMAIL])) {
    // Redirect to login.php
    header("location:login.php?from=home");
    exit();
}

$email = $_SESSION[$EMAIL];

// Fetch the user from the db
$stmt = $cn->prepare("SELECT * FROM users WHERE $EMAIL = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

//var_dump($user);


// === EMPLOYEE RELATED CODE ===

// fetch variables
$id = $_GET['token'] ?? null;
$fname = $_GET['fname'] ?? '';
$lname = $_GET['lname'] ?? '';
$email = $_GET['email'] ?? '';
$phone = $_GET['phone'] ?? '';
$jobTitle = $_GET['jobTitle'] ?? '';
$action = '';

// fetch available job titles
$sqlj = "SELECT DISTINCT(JobTitle) FROM employees";
$jobTitles = $cn->query($sqlj);

// if token is there, we're on update mode!
if (isset($id)) {
    // set the action attribute for form
    $action = "update.php?token=$id";

    $sql2 = "SELECT * FROM employees WHERE EmployeeID=$id";
    $rs2 = $cn->query($sql2);
    if ($rs2->num_rows > 0) {
        $row = $rs2->fetch_assoc();
        $fname = $row['EmployeeFN'];
        $lname = $row['EmployeeLN'];
        $email = $row['EmployeeEmail'];
        $phone = $row['EmployeePhone'];
        $jobTitle = $row['JobTitle'];
    } else {
        echo $cn->error;
    }
} else {
    $action = 'add.php';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Employee Management Software</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="styles.css"/>
    <script defer src="./script.js"></script>
</head>
<body>
<?php include('components/navbar.php') ?>
<div class="container mt-5">
    <div class="row">
        <div class="col-9">
            <?php include('components/table.php') ?>
        </div>
        <?php if ($user[$ROLE] === $ADMIN): ?>
            <div class="col-3">
                <?php if (!isset($id)): ?>
                    <h3>Add Employee</h3>
                <?php else: ?>
                    <h3>Edit Employee</h3>
                <?php endif; ?>
                <form action="<?= $action ?>" method="POST" class="d-flex my-4">
                    <?php include('components/alert.php') ?>
                    <div class="d-flex flex-column">
                        <input type="text" name="fname" placeholder="Enter First Name" value="<?= $fname ?>" required
                               class="form-control mb-2">
                        <input type="text" name="lname" placeholder="Enter Last Name" value="<?= $lname ?>" required
                               class="form-control mb-2">
                        <input type="email" name="email" placeholder="Enter Email" value="<?= $email ?>" required
                               class="form-control mb-2">
                        <select type="text" name="jobTitle" required
                                class="form-select mb-2">
                            <?php while ($rowj = $jobTitles->fetch_assoc()): ?>
                                <option
                                        value="<?= $rowj['JobTitle'] ?>"
                                    <?= ($jobTitle == $rowj['JobTitle']) ? 'selected' : '' ?>>
                                    <?= $rowj['JobTitle'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <input type="text" name="phone" placeholder="Enter Contact No" value="<?= $phone ?>" required
                               class="form-control me-2"><br/>

                        <div class="d-flex justify-content-center">
                            <button class="btn me-2 <?= (isset($id)) ? 'btn-warning' : 'btn-primary' ?>">
                                <?= (isset($id)) ? 'UPDATE' : 'SAVE' ?>
                            </button>
                            <?php if (isset($id)): ?>
                                <a class="btn btn-primary me-2" href="/index.php">
                                    BACK
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
        <?php elseif ($user[$ROLE] === $USER): ?>
            <?php include('components/profile.php') ?>
        <?php endif; ?>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#employeeList').DataTable();
    });
</script>
</body>
</html>