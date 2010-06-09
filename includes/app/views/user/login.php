<?php require('../_header.php'); ?>
<div id="container">
    <h2>Log in</h2>

    <form action="/user/login" method="post">
        Username: <input type="text" name="userName"/><br/>
        Password: <input type="password" name="password"/>
        <input type="submit" value="Submit">
    </form>
</div>
<?php require('../_footer.php'); ?>