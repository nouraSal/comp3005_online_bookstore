<?php

class DATABASE{

    public $con;

    public function __construct()
    {

        $this->con=mysqli_connect("localhost","root","","hostel_man_sys")or die(mysqli_error($this->con));

        if(!$this->con)
        {
            echo "failed to connect to the database";
        }

    }

}

