<?php require($views['BASE_HEADER_VIEW']); ?>
<div id="container">
    <h2>Log in</h2>
<?php if (isset($values['login_error'])) {
    foreach ($this->values['login_error'] as $key => $value) {
        ?>
            <p><?php echo $key . " : " . $value; ?></p>
        <?php
    }
} ?>
    <form action="<?php echo \models\User::getLoginUrl(); ?>" method="post">
        Username: <input type="text" name="userName"/><br/>
        Password: <input type="password" name="password"/>
        <input type="hidden" value="<?php if (isset($values["return_url"])) echo $values["return_url"]; ?>"
               name="return_url"/>
        <input type="submit" value="Submit">
    </form>
</div>
<?php require($views['BASE_FOOTER_VIEW']); ?>