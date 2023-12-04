<?php
global $cn, $AD_EMAIL, $AD_PASSWORD;
?>

<?php if(isset($_GET['invalid_cred'])): ?>
    <div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
        Invalid username or password
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif(isset($_GET['session_expired'])): ?>
    <div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
        Session expired. Please login again.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif(isset($_GET['user_exists'])): ?>
    <div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
        User already exists. Please use a different email.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif(isset($_GET['not_approved'])): ?>
    <div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
        User not approved. Please wait for admin approval.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif (isset($_GET['duplicate'])): ?>
    <div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
        A record already exists!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif (isset($_GET['conflictError'])): ?>
    <div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
        Conflicting record!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif (isset($_GET['missingToken'])): ?>
    <div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
        Token is missing! Are you on the wrong page?
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
        <?php if (isset($_GET['add'])): ?>
            New record has been added!
        <?php elseif (isset($_GET['update'])): ?>
            Record has been successfully updated!
        <?php elseif (isset($_GET['delete'])): ?>
            Record has been successfully deleted!
        <?php endif; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif (isset($_GET['addError']) || isset($_GET['updateError'])): ?>
    <div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
        <h5 class="alert-heading">There has been an error</h5>
        <?= $cn->error ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif (isset($_GET['adminCreated'])): ?>
    <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
        Default admin account has been created! You may not login using these credentials<br><br>Email: <?= $AD_EMAIL ?><br>Password: <?= $AD_PASSWORD ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif (isset($_GET['adminError'])): ?>
    <div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
        There has been an error creating the admin account!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
