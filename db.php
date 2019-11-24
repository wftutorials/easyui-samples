<?php
function db_connect(){
    $host = "127.0.0.1";
    $user = "root";
    $password = "";
    $db = "wf_api";
    try {
        $conn = new PDO("mysql:host=".$host.";dbname=".$db.';charset=utf8', $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
    catch(PDOException $e) {
        die($e->getMessage());
    }
}
function query( $sql, $params=array()){
    $conn = db_connect();
    $query = $conn->prepare($sql);
    if(!empty($params)){
        $query->execute($params) or die ('Internal error');
    }else{
        $query->execute() or die ('Internal error');
    }
    return $query;
}
function fetch_all( $sql, $params=array() ){
    $query = query($sql, $params);
    try{
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
    }catch (Exception $e){
        return $e->getMessage();
    }
    return $results;
}
?>