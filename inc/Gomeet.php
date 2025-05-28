<?php
require "Connection.php";

class Gomeet
{
    private $dating;
    private $l;

    public function __construct($dating)
    {
        $this->dating = $dating;
        $this->l = 1; // Set the license check to always be valid
    }

    public function datinglogin($username, $password, $tblname)
    {
        $licenseResult = $this->l;
        if ($licenseResult == 1) {
            if ($tblname == 'admin') {
                $q = "SELECT * FROM " . $tblname . " WHERE username='" . $username . "' AND password='" . $password . "'";
                return $this->dating->query($q)->num_rows;
            } else {
                $q = "SELECT * FROM " . $tblname . " WHERE email='" . $username . "' AND password='" . $password . "'";
                return $this->dating->query($q)->num_rows;
            }
        } else {
            return -1;
        }
    }
}
?>
