<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FBC - Prayer Wall</title>
    <link href="/css/reset.css" rel="stylesheet" type="text/css"/>
    <link href="/css/layout.css" rel="stylesheet" type="text/css"/>
    <link href="/css/colorbox.css" rel="stylesheet" type="text/css"/>
    <link href="/css/colors/grey.css" rel="stylesheet" type="text/css"/>
    <!-- Change the colour if you want -->
    <script type="text/javascript" src="/js/jquery-1.3.2.js"></script>
    <script type="text/javascript" src="/js/jquery.colorbox.js"></script>
    <script type="text/javascript" src="/js/pr.js"></script>
    <script type="text/javascript">
        $('document').ready(function() {
            $(".portfolio").colorbox();
            $('.head-first').next('.content').hide();
            $('.head-first').click(function() {
                $(this).next('.content').slideToggle(800);
            });
        });
    </script>

    <!--[if lt IE 7]>
    <script src="/js/DD_belatedPNG_0.0.8a.js" type="text/javascript"></script>
    <script type="text/javascript">DD_belatedPNG.fix('img, .seperator, .content, h1');</script>
    <![endif]-->

    <style type="text/css">
        <!--
        #image {
            width: 430px;
            outline: 0;
            margin: 0 0 0 2px;
        }

        -->
    </style>
</head>

<body>
<div id="wrapper">

    <div id="logo-container">
        <h1>FBC - Prayer Wall</h1>
    </div>


    <div>
        <div class="head-first">
            I have a prayer request
        </div>
        <div class="content">
            <div class="seperator">&nbsp;</div>

            <div id="prayer-request-form">
                <form id="contact" method="post" action="index.php">
                    <div>
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" class="input-active"
                               value="Enter your name here" onclick="$(this).val('');"/><br/></div>
                    <div>
                        <label for="email">Email:</label>
                        <input type="text" id="email" name="email" class="input-active"
                               value="Enter your email here" onclick="$(this).val('');"/><br/></div>
                    <div>
                        <label for="message">Prayer Request:</label>
                        <textarea id="message" rows="6" cols="40" onclick="$(this).val('');">Prayer Request...</textarea></div>
                    <div>
                        <input type="button" id="prayer-request-button" value="" class="submit"/>
                    </div>
                </form>
            </div>

            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
    <div id="prayer-requests">
    <?php if (isset($values['prayer_requests'])) { ?>
    <?php foreach ($values['prayer_requests'] as $key => $prayer_request) { ?>
        <div>
            <div class="head">
            <?php echo "$prayer_request->name " . $prayer_request->getDate(); ?>
            </div>

            <div class="content">
            <?php echo $prayer_request->message; ?>
            </div>
        </div>
    <?php }
} ?>
    </div>
    <div class="head-last">
        <span>Developed by Jonathan Mayhak</span>
    </div>
</div>
<!-- End of Contents -->
</body>
</html>
