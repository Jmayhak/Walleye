<prayer-requests>
<?php foreach ($values['prayer-requests'] as $key => $prayer_request) { ?>
    <prayer-request>

        <name>
        <?php echo $prayer_request->name; ?>
        </name>
        <email>
        <?php echo $prayer_request->email; ?>
        </email>
        <message>
        <?php echo $prayer_request->message; ?>
        </message>

    </prayer-request>
<?php } ?>
</prayer-requests>
