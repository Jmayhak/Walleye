<?php require(BASE_HEADER); ?>
<div id="container">
    <h2>Log in</h2>
    <p>username: test<br />
        password: test</p>
    <?php if(isset($this->values["login_error"])) {
        foreach($this->values["login_error"] as $key=>$value) { ?>
        <p><?php echo $key . " : " . $value; ?></p>
    <?php }
    if(isset($this->values["return_url"])) echo "return_url : " . $this->values["return_url"];
    } ?>
        <form action="/user/login/" method="post">
    Username: <input type="text" name="userName" /><br />
    Password: <input type="password" name="password" />
    <input type="hidden" value="<?php if(isset($this->data["return_url"])) echo $this->data["return_url"]; ?>" name="return_url" />
    <input type="submit" value="Submit">
        </form>
</div>
<?php require(BASE_FOOTER); ?>