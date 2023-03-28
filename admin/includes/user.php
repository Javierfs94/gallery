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
}
