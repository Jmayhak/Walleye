<prayer-requests>
<?php if (isset($values['prayer_requests'])) { ?>
<?php foreach ($values['prayer_requests'] as $key => $prayer_request) { ?>
    <prayer-request>
        <name>
        <?php echo $prayer_request->name; ?>
        </name>
        <email>
        <?php echo $prayer_request->getDate(); ?>
        </email>
        <message>
        <?php echo $prayer_request->message; ?>
        </message>
    </prayer-request>
<?php } ?>
<?php } ?>
</prayer-requests>
