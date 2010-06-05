<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title></title>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
<?php foreach ($values['css'] as $css) { ?>
    <link href="<?php echo $css['href']; ?>" rel="stylesheet" type="text/css"/>
<?php } ?>
<?php foreach ($values['js'] as $js) { ?>
    <link href="<?php echo $js['href']; ?>" rel="stylesheet" type="text/css"/>
<?php } ?>
</head>
<body>
<div id="wrapper">
    <div id="header">
        <div id="logo">
            <h3><a href="/"></a></h3>
        </div>
        <div id="menu">
            <ul>
            <?php if (mUser::getLoggedUser()->getUid()) { ?>
                <li><a href="<?php echo mUser::getLogoutUrl(); ?>">Log out</a></li>
            <?php } else { ?>
                <li><a href="<?php echo mUser::getLoginUrl(); ?>">Log in</a></li>
            <?php } ?>
            </ul>
        </div>
    </div>
</div>