<?php
    $host="localhost";
    $user="root";
    $db="db";
    $password="";

    try{
        $conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);

        // $username="BardhM";
        // $password=password_hash("12345678",PASSWORD_DEFAULT);
        // $sql="INSERT INTO shaip(username,password) VALUES('$username','$password')";

        // $conn->exec($sql);
        //  echo "INSERTED";

        // $sql="ALTER TABLE shaip ADD email VARCHAR(255)";
        // $sql="ALTER TABLE shaip DROP COLUMN email";
        $sql="DROP TABLE user";

        $conn->exec($sql);
        echo "altered";
    }catch (Exception $e){
        echo "Not connected" . $e->getMessage();
    }
?>