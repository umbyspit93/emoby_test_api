<?php
include "config/config.php";
$action = $_REQUEST['action'];

if ($action == "getUsersData") {
    $token = $_POST['token'];

    $emobyUsersList = curl_init();
    curl_setopt($emobyUsersList,CURLOPT_URL,"https://prod-api.emoby.it/user?token=".$token);
    curl_setopt($emobyUsersList,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($emobyUsersList,CURLOPT_HEADER, false); 
    curl_setopt($emobyUsersList, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($emobyUsersList, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($emobyUsersList, CURLOPT_POST, false);

    $users_list = curl_exec($emobyUsersList);
    $data_array = json_decode($users_list,true);
    curl_close($emobyUsersList);
    
    $binds = array();
    $set_arr = array();
    foreach ($data_array['user'] as $key => $val) {
        if ($key!="tickets") {
            $key_bind = ":".$key;
            $binds[$key_bind] = $val;
            $set_arr[] = $key."=:".$key;
        }
    }
    $set_string = implode(", ",$set_arr);
    //AVREI VOLUTO BINDARE I VALORI ED ESEGUIRE LA QUERY IN PDO TRAMITE PREPARE
    //PER RENDERLA ANZITUTTO PIU' LEGGIBILE, 

    $sql = "INSERT INTO user SET 
    $set_string";
    $stmt = $dbConn->prepare($sql);
    foreach ($data_array['user'] as $key => $val) {
        if ($key!="tickets") {
            $pdo_param = PDO::PARAM_STR;
            if ($key=="verified" || $key=="can_rent" || $key=="can_rent_free" || $key=="can_book") {
                $pdo_param = PDO::PARAM_INT;
            }
            $stmt->bindValue(":".$key, $pdo_param);
        }
    }
    $stmt->execute();
    if (!$stmt) {
        echo "\nPDO::errorInfo():\n";
        print_r($dbConn->errorInfo());
    } else {
        echo $stmt->debugDumpParams();
    }
}

?>