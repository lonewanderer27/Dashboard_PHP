<?php
include 'config.php';
include 'constants.php';

// Start a session
session_start();
//var_dump($_SESSION);

global $cn, $EMAIL, $PASSWORD;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch email and password from $_POST
    $email = $_POST[$EMAIL];
    $password = $_POST[$PASSWORD];

    // Check in the db if such a user exists
    $stmt = $cn->prepare("SELECT * FROM users WHERE $EMAIL = ? AND $PASSWORD = SHA2(?, 256)");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // If user is not null, then we have a match!
    if ($user) {
        // Store user information in session variables
        $_SESSION[$EMAIL] = $email;

        // Redirect to index.php
        header("location:index.php");
    } else {
        // Redirect to login.php with error message
        header("location:login.php?invalid_cred=true");
        exit();
    }
}

// Check if there's already session expired error
if (!isset($_GET['session_expired']) && isset($_SESSION[$EMAIL])) {
    // Redirect to index.php
    header("location:index.php");
    exit();
} elseif (isset($_GET['session_expired']) && isset($_SESSION[$EMAIL])) {
    // Clear the session
    session_destroy();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management Software</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            crossorigin="anonymous"></script>
    <link href="styles.css" rel="stylesheet">
</head>
<body class="gradient-custom d-flex justify-content-center align-items-center mt-5">
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <div class="card">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item text-center">
                <a class="nav-link active btl" href="login.php" aria-selected="true">Login</a>
            </li>
            <li class="nav-item text-center">
                <a class="nav-link btr" href="signup.php" aria-selected="false">Signup</a>
            </li>
        </ul>
        <div>

            <div class="tab-pane fade show active" role="tabpanel">
                <div class="form px-4 pt-5">
                    <?php include('components/alert.php') ?>
                    <input type="text" name="email" class="form-control" placeholder="Email">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                    <div class="d-grid">
                        <button class="btn btn-dark" type="submit">Login</button>
                    </div>
                    <div class="mt-3 text-center">
                        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</body>
</html>
