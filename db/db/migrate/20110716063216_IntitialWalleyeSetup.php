<?php

class IntitialWalleyeSetup extends Ruckusing_BaseMigration
{

    public function up()
    {
        // Keys
        $Keys = $this->create_table("Keys", array("id" => false));

        $Keys->column("id", "integer", array('primary_key' => true, 'auto_increment' => true, 'null' => false, 'unsigned' => true));
        $Keys->column("secret_key", "string");
        $Keys->column("api_key", "string");
        $Keys->column("date_created", "timestamp", array('default' => 'CURRENT_TIMESTAMP'));

        $Keys->finish();

        // Logs
        $Logs = $this->create_table("Logs", array("id" => false));

        $Logs->column("id", "integer", array('primary_key' => true, 'auto_increment' => true, 'null' => false, 'unsigned' => true));
        $Logs->column("user_id", "integer");
        $Logs->column("type", "string");
        $Logs->column("line", "string");
        $Logs->column("file", "text");
        $Logs->column("date_created", "timestamp", array('default' => 'CURRENT_TIMESTAMP'));
        $Logs->column("message", "string");

        $Logs->finish();

        // Sessions
        $Sessions = $this->create_table("Sessions", array("id" => false));

        $Sessions->column("id", "integer", array('primary_key' => true, 'auto_increment' => true, 'null' => false, 'unsigned' => true));
        $Sessions->column("session_key", "string");
        $Sessions->column("date_created", "timestamp", array('default' => 'CURRENT_TIMESTAMP'));

        $Sessions->finish();

        // UserKeys
        $UserKeys = $this->create_table("UserKeys", array("id" => false));

        $UserKeys->column("id", "integer", array('primary_key' => true, 'auto_increment' => true, 'null' => false, 'unsigned' => true));
        $UserKeys->column("user_id", "integer");
        $UserKeys->column("key_id", "integer");
        $UserKeys->column("date_created", "timestamp", array('default' => 'CURRENT_TIMESTAMP'));

        $UserKeys->finish();

        // UserPermissions
        $UserPermissions = $this->create_table("UserPermissions", array("id" => false));

        $UserPermissions->column("id", "integer", array('primary_key' => true, 'auto_increment' => true, 'null' => false, 'unsigned' => true));
        $UserPermissions->column("user_id", "integer");
        $UserPermissions->column("permission_id", "integer");

        $UserPermissions->finish();

        // Users
        $Users = $this->create_table("Users", array("id" => false));

        $Users->column("id", "integer", array('primary_key' => true, 'auto_increment' => true, 'null' => false, 'unsigned' => true));
        $Users->column("first_name", "string");
        $Users->column("last_name", "string");
        $Users->column("username", "string");
        $Users->column("password", "string");
        $Users->column("date_created", "timestamp", array('default' => 'CURRENT_TIMESTAMP'));

        $Users->finish();

        // UserSessions
        $UserSessions = $this->create_table("UserSessions", array("id" => false));

        $UserSessions->column("id", "integer", array('primary_key' => true, 'auto_increment' => true, 'null' => false, 'unsigned' => true));
        $UserSessions->column("user_id", "integer");
        $UserSessions->column("session_id", "integer");
        $UserSessions->column("date_created", "timestamp", array('default' => 'CURRENT_TIMESTAMP'));

        $UserSessions->finish();

    }

    public function down()
    {
        $this->drop_table("Keys");
        $this->drop_table("Logs");
        $this->drop_table("Sessions");
        $this->drop_table("UserKeys");
        $this->drop_table("UserPermissions");
        $this->drop_table("Users");
        $this->drop_table("UserSessions");

    }

}

?>
