<?php
    include 'logout.php';
    global $user, $EMAIL;
?>

<nav class="navbar navbar-expand-md">
    <div class="container">
        <a class="navbar-brand" href="#">Employee Management Software</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= $user[$EMAIL] ?>
                    </a>
                    <!-- Here's the magic. Add the .animate and .slideIn classes to your .dropdown-menu and you're all set! -->
                    <div class="dropdown-menu dropdown-menu-end animate slideIn" aria-labelledby="navbarDropdown">
<!--                        <a class="dropdown-item" href="../profile.php">Profile</a>-->
<!--                        <div class="dropdown-divider"></div>-->
                        <a class="dropdown-item" href="../index.php?logout=true">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
