<?php $this->view('_header.php'); ?>
<form action="/user/login" method="post">
<?php if (isset($values['login_message'])) : ?>
    <div class="error">
        <span>Error</span>
    <?php echo $values['login_message']; ?>
    </div>
<?php endif; ?>
    <div id="border">
        <table cellpadding="2" cellspacing="0" border="0">
            <tr>
                <td>Username:</td>
                <td><input type="text" name="username"/></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" name="password"/></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input type="submit" name="submit" value="Login"/></td>
            </tr>
        </table>
    </div>
    <p>username: test pw: test</p>
</form>
<?php $this->view('_footer.php'); ?>