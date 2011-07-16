<script type="text/javascript" src="/js/jquery-1.4.4.js"></script>
    <script type="text/javascript" src="/js/jquery.gritter.min.js"></script>
	<?php if (isset($values['css'])) : foreach ($values['css'] as $href) : ?>
	    <link rel="stylesheet" href="<?php echo $href ?>" type="text/css" />
	<?php endforeach; endif; ?>
	<?php if (isset($values['js'])) : foreach ($values['js'] as $js) : ?>
	    <script type="text/javascript" src="<?php echo $js; ?>"></script>
	<?php endforeach; endif; ?>
    <script type="text/javascript">
        $(document).ready(function() {
        <?php if (isset($values['logs'])) : foreach ($values['logs'] as $log) : ?>

        <?php if ($log['type'] == 'error') : ?>
                console.error('<?php echo $log['message'] . ' ' . $log['file'] . ' ' . $log['line']; ?>');
            <?php elseif ($log['type'] == 'alert') : ?>
                        $.gritter.add({
                            title: 'Alert',
                            text: '<?php echo $log['message'] ?>',
                            image: '',
                            sticky: false,
                            time: ''
                        });
                <?php  else : ?>
                console.log('<?php echo $log['message'] . ' ' . $log['file'] . ' ' . $log['line']; ?>');
            <?php endif; ?>
        <?php endforeach; endif; ?>
        });
    </script>
</body>
</html>