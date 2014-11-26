<?php

// get the specific message
function getMessages($arrayLength) {
    $db = null;
    $endtime = time() + 20;

    while(time() <= $endtime){
        try {
            $db = new PDO("sqlite:db.db");
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e) {
            die("Del -> " .$e->getMessage());
        }

        $q = "SELECT * FROM messages ORDER BY time DESC";
        $stm = $db->prepare($q);
        $stm->execute();
        $result = $stm->fetchAll();

        if($arrayLength < count($result)){

            $length = count($result)-$arrayLength;
            $mess = array_splice($result, 0, $length, false);

            return $mess;
        }
    }
}

function getFirstMessages(){
    $db = null;

    try {
        $db = new PDO("sqlite:db.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOEception $e) {
        die("Del -> " .$e->getMessage());
    }

        $q = "SELECT * FROM messages";

        try{
            $stm = $db->prepare($q);
            $stm->execute();
            $result = $stm->fetchAll();
        }
        catch(PDOException $e){
            echo("Error creating query: " .$e->getMessage());
            return false;
        }
        if($result){
            return $result;
        }
        else{
            return false;
        }
}