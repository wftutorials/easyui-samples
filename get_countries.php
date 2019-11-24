<?php
include 'db.php';

if(isset($_POST['query']) && !empty($_POST['query'])){
    $countries = fetch_all('select * from countries where country_name LIKE ?',["%".$_POST['query']."%"]);
}else{
    $countries = fetch_all('select * from countries');
}

$data = [];
foreach ($countries as $country){
    $data[] = [
        'value' => $country['country_id'],
        'text' => $country['country_name']
        ];
    //echo $country['country_name'];
}
header('Content-Type: application/json');
echo json_encode($data);