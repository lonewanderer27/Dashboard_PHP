<?php
include 'config.php';
include 'constants.php';

// Start a session
session_start();
//var_dump($_SESSION);

global $cn, $USERNAME, $PASSWORD, $EMAIL, $PHONE, $ADDRESS, $ROLE, $APPROVED, $USER, $ADMIN;

// Check if the user is already authenticated using the session
if (isset($_SESSION[$EMAIL])) {
    // Redirect to index.php
    header("location:index.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // fetch email and password from $_POST
    $email = $_POST[$EMAIL];
    $password = $_POST[$PASSWORD];
    $phone = $_POST[$PHONE];
    $address = $_POST[$ADDRESS];

    // check in the db if such a user exists
    $stmt = $cn->prepare("SELECT * FROM users WHERE $EMAIL = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // if user is null, then we can create a new user!
    if (!$user) {
        // insert the new user into the database
        $stmt = $cn->prepare("INSERT INTO users ($EMAIL, $PASSWORD, $PHONE, $ADDRESS, $ROLE, $APPROVED) VALUES (?, SHA2(?, 256), ?, ?, ?, ?)");
        $role = $USER;
        $approved = 0;
        $stmt->bind_param("sssssi", $email, $password, $phone, $address, $role, $approved);
        $stmt->execute();

        // set the cookie and redirect to index.php
        setcookie($EMAIL, $email, time() + (10 * 365 * 24 * 60 * 60));
        setcookie($PASSWORD, hash('sha256', $password), time() + (10 * 365 * 24 * 60 * 60));

        // Store user information in session variables
        $_SESSION[$EMAIL] = $email;

        // redirect to index.php
        header("location:index.php");
        exit();
    } else {
        // redirect to signup.php with error message
        header("location:signup.php?user_exists=true");
        exit();
    }
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
                <a class="nav-link btl" href="login.php" aria-selected="true">Login</a>
            </li>
            <li class="nav-item text-center">
                <a class="nav-link active btr" href="signup.php" aria-selected="false">Signup</a>
            </li>
        </ul>
        <div>
            <div class="tab-pane fade show active" role="tabpanel">
                <div class="form px-4 pt-5">
                    <?php include('components/alert.php') ?>
                    <input type="email" name="email" class="form-control" placeholder="Email">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                    <input type="phone" name="phone" class="form-control" placeholder="Phone">
                    <input type="text" name="address" class="form-control" placeholder="Address">
                    <div class="d-grid">
                        <button class="btn btn-dark" type="submit">Signup</button>
                    </div>
                    <div class="mt-3 text-center">
                        <p>Already have an account? <a href="login.php">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</body>
</html>
