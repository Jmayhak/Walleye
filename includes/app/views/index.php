<?php $this->view('_header.php'); ?>
<h2>Walleye - An MVC style framework for PHP 5.1.6</h2>
<p>Fork it <a href="http://github.com/Jmayhak/Walleye">here</a></p>
<?php if (Walleye_user::getLoggedUser()) : ?>
<p>Looks like everything is working</p>
<a href="/user/logout">Logout</a>
<?php else : ?>
<p>Try logging in</p>
<a href="/user/login">Login</a>
<?php endif; ?>
<?php $this->view('_footer.php'); ?>