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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            crossorigin="anonymous"></script>
    <script defer src="script.js"></script>
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card border-0 shadow rounded-3 my-5">
                <div class="card-body p-4 p-sm-5">
                    <?php include('components/alert.php') ?>
                    <h3 class="card-title text-center mb-5 fw-light">Sign Up</h3>
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
                        <div class="form-floating mb-3">
                            <input name="full_name" type="text" class="form-control" id="full_name"
                                   placeholder="Full Name" required>
                            <label for="full_name">Full Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input name="address" type="text" class="form-control" id="address"
                                   placeholder="Address" required>
                            <label for="address">Address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input name="phone" type="text" class="form-control" id="phone"
                                   placeholder="phone" required>
                            <label for="phone">Phone</label>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-lg btn-primary btn-login text-uppercase fw-bold" type="submit">Sign Up
                            </button>
                        </div>
                        <div class="mt-3 text-center">
                            <p>Already have an account? <a href="login.php">Login</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</form>
</body>
</html>
