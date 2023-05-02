<?php

class User
{

    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;


    public static function find_all_users()
    {
        return self::find_this_query("SELECT * FROM users");
    }



    public static function find_user_by_id($user_id)
    {
        $result = self::find_this_query("SELECT * FROM users WHERE id =" . $user_id . " LIMIT 1");

        return !empty($result) ? array_shift($result) : false;
    }


    public static function find_this_query($sql)
    {
        global $database;

        $result = $database->query($sql);

        $the_object_array = array();

        while ($row  = mysqli_fetch_array($result)) {
            $the_object_array[] = self::insertation($row);
        }

        return $the_object_array;
    }

    public static function insertation($the_record)
    {
        $user = new self;

        foreach ($the_record as $the_attribute => $value) {

            if ($user->has_the_attribute($the_attribute)) {
                $user->$the_attribute = $value;
            }
        }

        return $user;
    }

    private function has_the_attribute($the_attribute)
    {
        $object_properties = get_object_vars($this);

        return array_key_exists($the_attribute, $object_properties);
    }

    public static function verify_user($username, $password)
    {
        global $database;

        $username = $database->escape_string($username);
        $password = $database->escape_string($password);

        $sql = "SELECT * FROM users WHERE username = '" . $username . "' AND password = '" . $password . "' LIMIT 1";
        $result = self::find_this_query($sql);

        return !empty($result) ? array_shift($result) : false;
    }

    public function create()
    {
        global $database;

        $sql = "INSERT INTO users (username, password, first_name, last_name)";
        $sql .= "VALUES ('";
        $sql .= $database->escape_string($this->username) . "', '";
        $sql .= $database->escape_string($this->password) . "', '";
        $sql .= $database->escape_string($this->first_name) . "', '";
        $sql .= $database->escape_string($this->last_name) . "')";

        if ($database->query($sql)) {
            $this->id = $database->the_insert_id();

            return true;
        } else {
            return false;
        }
    }


    public function update()
    {
        global $database;

        $sql = "UPDATE users SET ";
        $sql .= "username= '" . $database->escape_string($this->username) . "', ";
        $sql .= "password= '" . $database->escape_string($this->password) . "', ";
        $sql .= "first_name= '" . $database->escape_string($this->first_name) . "', ";
        $sql .= "last_name= '" . $database->escape_string($this->last_name) . "' ";
        $sql .= "WHERE id= " . $database->escape_string($this->id);

        $database->query($sql);

        return (mysqli_affected_rows($database->connection) == 1) ? true : false;
    }


    public function delete()
    {
        global $database;

        $sql = "DELETE FROM users ";
        $sql .= "WHERE id= " . $database->escape_string($this->id);
        $sql .= " LIMIT 1";

        $database->query($sql);

        return (mysqli_affected_rows($database->connection) == 1) ? true : false;
    }
}
