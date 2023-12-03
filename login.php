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
    <link href="https://fonts.googleapis.com/css?family=Kaushan+Script" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link href="styles.css" rel="stylesheet">
</head>
<body class="gradient-custom d-flex justify-content-center align-items-center mt-5">
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <div class="container">
        <div class="row">
            <div class="mx-auto">
                <div id="first">
                    <div class="myform form">
                        <div class="logo mb-3">
                            <div class="col-md-12 text-center">
                                <h1>Login</h1>
                            </div>
                        </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email address</label>
                                <input type="email" name="email" class="form-control mt-2 mb-3" id="email"
                                       aria-describedby="emailHelp" placeholder="Enter email">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Password</label>
                                <input type="password" name="password" id="password" class="form-control mt-2"
                                       aria-describedby="emailHelp" placeholder="Enter Password">
                            </div>
                            <div class="col-md-12 text-center mt-4">
                                <button type="submit" class=" btn btn-block mybtn btn-primary tx-tfm">Login</button>
                            </div>
                            <div class="form-group mt-4">
                                <p class="text-center">Don't have account? <a href="signup.php" id="signup">Sign up here</a></p>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</body>
</html>
