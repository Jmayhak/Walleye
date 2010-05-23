<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title></title>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <link href="<?php echo DEFAULT_STYLESHEET; ?>" rel="stylesheet" type="text/css"/>
</head>
<body>
<div id="wrapper">
    <div id="header">
        <div id="logo">
            <h3><a href="/">APP NAME</a></h3>
        </div>
        <div id="menu">
            <ul>
            <?php if (Controller::getLoggedUser()->getUid()) { ?>
                <li><a href="<?php echo LOG_OUT_URL_PATH; ?>">Log out</a></li>
            <?php } else { ?>
                <li><a href="<?php echo LOG_IN_URL_PATH; ?>">Log in</a></li>
            <?php } ?>
            </ul>
        </div>
    </div>
</div>