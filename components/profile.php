<?php global $user, $ADDRESS, $FULLNAME, $PHONE, $ADDRESS, $ROLE; ?>

<div class="col-3">
    <div class="card mb-4">
        <div class="card-body text-center">
            <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp" alt="avatar"
                 class="rounded-circle img-fluid" style="width: 150px;">
            <h5 class="my-3"><?= $user[$FULLNAME] ?></h5>
            <p class="text-muted mb-1">Full Stack Developer</p>
            <p class="text-muted mb-4"><?= $user[$ADDRESS] ?></p>
            <hr>
            <div class="row">
                <div class="col-sm-1">
                    <i class="bi bi-telephone"></i>
                </div>
                <div class="col-sm-11">
                    <p class="text-muted mb-0"><?= $user[$PHONE] ?></p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-1">
                    <i class="bi bi-person"></i>
                </div>
                <div class="col-sm-11">
                    <p class="text-muted mb-0"><?= $user[$ROLE] ?></p>
                </div>
            </div>
        </div>
    </div>
</div>