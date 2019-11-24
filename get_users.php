<?php
include 'db.php';

$limit = 10;
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$totalRows = isset($_POST['rows']) ? intval($_POST['rows']) : $limit;
$offset = ($page-1) * $totalRows;
$query = isset($_POST['query']) ? $_POST['query'] : null;
if($query){
    $users = fetch_all("select * from users WHERE first_name LIKE ? LIMIT $totalRows OFFSET $offset",['%'.$query.'%']);
    $total = fetch_all('Select count(*) as total from users WHERE first_name LIKE ? ',['%'.$query.'%'])[0]['total'];
}else{
    $users = fetch_all("select * from users LIMIT $totalRows OFFSET $offset");
    $total = fetch_all('Select count(*) as total from users')[0]['total'];
}
$response = [];
$data = [];
foreach ($users as $user){
    $data[] = [
        'firstname' => $user['first_name'],
        'lastname' => $user['last_name'],
        'gender' => $user['gender'],
        'age' => $user['age'],
        'email' => $user['email'],
        'phone' => $user['phone']
        ];
    //echo $country['country_name'];
}
$response['total'] = $total;
$response['page'] = $page;
$response['rows'] = $data;
header('Content-Type: application/json');
echo json_encode($response);