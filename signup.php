<?php
include 'config.php';
include 'constants.php';

// Start a session
session_start();
//var_dump($_SESSION);

global $cn, $USERNAME, $PASSWORD, $EMAIL, $PHONE, $ADDRESS, $ROLE, $APPROVED, $USER, $ADMIN, $FULLNAME;

// Check if the user is already authenticated using the session
if (isset($_SESSION[$EMAIL])) {
    // Redirect to index.php
    header("location:index.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // fetch email and password from $_POST
    $email = $_POST[$EMAIL];
    $fullname = $_POST[$FULLNAME];
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
        $stmt = $cn->prepare("INSERT INTO users ($EMAIL, $PASSWORD, $FULLNAME, $PHONE, $ADDRESS, $ROLE, $APPROVED) VALUES (?, SHA2(?, 256), ?, ?, ?, ?, ?)");
        $role = $USER;
        $approved = 0;
        $stmt->bind_param("ssssssi", $email, $password, $fullname, $phone, $address, $role, $approved);
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

    <section class="vh-100" style="background-color: #9A616D;">
        <div class="container py-5 h-150">
            <div class="row d-flex justify-content-center align-items-center h-150">
                <div class="col col-xl-10">
                    <div class="card" style="border-radius: 1rem;">
                        <div class="row g-0">
                            <div class="col-md-6 col-lg-5 d-none d-md-block">
                                <img src="assetd/employee.webp"
                                     alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;"/>
                            </div>
                            <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                <div class="card-body p-4 p-lg-5 text-black">
                                    <?php include 'components/alert.php'; ?>
                                    <form>
                                        <h5 class="h2 mb-3 pb-3" style="letter-spacing: 1px;">Create your own account</h5>

                                        <div class="form-outline mb-3">
                                            <input name="email" type="email" id="form2Example17"
                                                   class="form-control form-control-lg" placeholder="Enter Email Address"/>
                                        </div>

                                        <div class="form-outline mb-3">
                                            <input name="password" type="password" id="form2Example27"
                                                   class="form-control form-control-lg"  placeholder="Enter Password"/>
                                        </div>

                                        <div class="form-outline mb-3">
                                            <input name="fullname" type="text" id="form2Example27"
                                                   class="form-control form-control-lg" placeholder="Enter Full Name" required/>
                                        </div>

                                        <div class="form-outline mb-3">
                                            <input name="phone" type="number" id="form2Example27"
                                                   class="form-control form-control-lg" placeholder="Enter Phone Number" required/>
                                        </div>

                                        <div class="form-outline mb-3">
                                            <input name="address" type="text" id="form2Example27"
                                                   class="form-control form-control-lg" placeholder="Enter Address" required/>
                                        </div>

                                        <div class="pt-1 mb-4">
                                            <button class="btn btn-dark btn-lg btn-block" type="submit">Signup</button>
                                        </div>

                                        <p class="mb-5 pb-lg-2" style="color: #393f81;">Already have an account? <a
                                                    href="login.php"
                                                    style="color: #393f81;">Login here</a></p>
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
