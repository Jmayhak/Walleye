<?php include('_header.php'); ?>

<div id="container">
    <?php if (Walleye_user::getLoggedUser()) : ?>
        <p>Looks like everything is working</p>
        <a href="/user/logout">Logout</a>
    <?php else : ?>
        <p>Try logging in</p>
        <a href="/user/login">Login</a>
    <?php endif; ?>
</div>

<?php include('_footer.php'); ?>