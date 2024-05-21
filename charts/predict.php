<?php
include '../db_connect.php';

//Get historical data from last 3 months
$month = range((date('m') - 2), date('m'));

//select all data
$sales = $conn->query("SELECT * FROM sales");


$count = [];

if ($sales->num_rows > 0) {
    while ($row = $sales->fetch_assoc()) {
        $filter_date = explode('-',    $row['date_updated']);
        $y = $filter_date[0];
        $m = intval($filter_date[1]);
        $d = intval($filter_date[2]);


        //filter this year
        if ($y == date('Y')) {
            //check if record is included
            if (in_array($m, $month)) {
                //fetch data
                $items = $conn->query("SELECT p.id FROM sales_items s INNER JOIN inventory i ON s.inventory_id = i.id INNER JOIN product_list p ON i.product_id = p.id WHERE sales_id = " . $row['id']);
                if ($items->num_rows > 0) {
                    array_push($count, $items->fetch_assoc()['id']);
                }
            }
        }
    }
}

$counts = array_count_values($count);

//consolidate
$result = array_map(function ($value, $count) use ($conn) {
    $sql = "SELECT name FROM product_list WHERE id = " . $value;
    $res = $conn->query($sql)->fetch_assoc()['name'];
    return [$res, $count];
}, array_keys($counts), $counts);

usort($result, function ($a, $b) {
    return $b[1] <=> $a[1];
});

//separate products
$products = array_map(function ($value) {
    return $value[0];
}, $result);

//separate values
$num = array_map(function ($value) {
    return $value[1];
}, $result);

$data = ['products' => array_slice($products, 0, 3), "data" => array_slice($num, 0, 3)];

echo json_encode($data);
