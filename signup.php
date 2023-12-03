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
                                <h1>Signup</h1>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email</label>
                            <input type="email" name="email" class="form-control mt-2 mb-3" id="email"
                                   aria-describedby="emailHelp" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Password</label>
                            <input type="password" name="password" id="password" class="form-control mt-2 mb-3"
                                   aria-describedby="emailHelp" placeholder="Enter Password">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Phone</label>
                            <input type="number" name="phone" id="phone" class="form-control mt-2 mb-3"
                                   aria-describedby="phoneHelp" placeholder="Enter Phone">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Address</label>
                            <input type="text" name="address" id="address" class="form-control mt-2 mb-3"
                                   aria-describedby="addressHelp" placeholder="Enter Address">
                        </div>
                        <div class="form-group mt-3">
                            <p class="text-center">By signing up you accept our <a href="#">Terms Of Use</a></p>
                        </div>
                        <div class="col-md-12 text-center ">
                            <button type="submit" class=" btn btn-block mybtn btn-primary tx-tfm">Signup</button>
                        </div>
                        <div class="form-group mt-4">
                            <p class="text-center">Already have an account? <a href="login.php" id="login">Log in here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</body>
</html>
