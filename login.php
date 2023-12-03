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
</head>
<body>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card border-0 shadow rounded-3 my-5">
                <div class="card-body p-4 p-sm-5">
                    <?php include('components/alert.php') ?>
                    <h3 class="card-title text-center mb-5 fw-light">Log In</h3>
                    <form>
                        <div class="form-floating mb-3">
                            <input name="email" type="email" class="form-control" id="floatingInput"
                                   placeholder="name@example.com" required>
                            <label for="floatingInput">Email address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input name="password" type="password" class="form-control" id="floatingPassword"
                                   placeholder="Password" required>
                            <label for="floatingPassword">Password</label>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-lg btn-primary btn-login text-uppercase fw-bold" type="submit">Log
                                in
                            </button>
                        </div>
                        <div class="mt-3 text-center">
                            <p>Don't have an account? <a href="signup.php">Sign up</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</form>
</body>
</html>
