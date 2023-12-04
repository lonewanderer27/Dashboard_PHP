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

    <section class="vh-100" style="background-color: #9A616D;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-xl-10">

                    <div class="card" style="border-radius: 1rem;">
                        <div class="row g-0">
                            <div class="col-md-6 col-lg-5 d-none d-md-block">
                                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/img1.webp"
                                     alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;"/>
                            </div>
                            <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                <div class="card-body p-4 p-lg-5 text-black">
                                    <?php include 'components/alert.php'; ?>
                                    <form>
                                        <div class="d-flex align-items-center mb-3 pb-1">
                                            <i class="fas fa-cubes fa-2x" style="color: #ff6219;"></i>
                                            <span class="h1 fw-bold mb-0">Employee Management</span>
                                        </div>

                                        <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign into your
                                            account</h5>

                                        <div class="form-outline mb-4">
                                            <input name="email" type="email" id="form2Example17"
                                                   class="form-control form-control-lg" required/>
                                            <label class="form-label mt-2" for="form2Example17">Email address</label>
                                        </div>

                                        <div class="form-outline mb-4">
                                            <input name="password" type="password" id="form2Example27"
                                                   class="form-control form-control-lg" required/>
                                            <label class="form-label mt-2" for="form2Example27">Password</label>
                                        </div>

                                        <div class="pt-1 mb-4">
                                            <button class="btn btn-dark btn-lg btn-block" type="submit">Login</button>
                                        </div>

                                        <p class="mb-5 pb-lg-2" style="color: #393f81;">Don't have an account? <a
                                                    href="signup.php"
                                                    style="color: #393f81;">Register here</a></p>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
</body>
</html>
