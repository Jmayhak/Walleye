<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title></title>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <?php if (isset($values['css'])) : foreach ($values['css'] as $src) : ?>
            <script type="text/javascript" src="<?php echo $src; ?>"></script>
    <?php endforeach; endif; ?>
    <?php if (isset($values['js'])) : foreach ($values['js'] as $js) : ?>
            <script rel="stylesheet" href="<?php echo $js; ?>"></script>
    <?php endforeach; endif; ?>
    <script type="text/javascript">
        <?php if (isset($values['logs'])) : foreach ($values['logs'] as $log) : ?>
            <?php if ($log['type'] == 'error') : ?>
                console.error('<?php echo $log['message'] . ' ' . $log['file'] . ' ' . $log['line']; ?>');
            <?php else : ?>
                console.log('<?php echo $log['message'] . ' ' . $log['file'] . ' ' . $log['line']; ?>');
            <?php endif; ?>
        <?php endforeach; endif; ?>
    </script>
</head>
<body>
<div id="wrapper">
    