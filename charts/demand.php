<?php
include '../db_connect.php';

$month = $_POST['month'];
$year = $_POST['year'];
$sales = $conn->query("SELECT * FROM sales WHERE date_updated LIKE '$year%'");


$monthname = date("F", mktime(0, 0, 0, $month, 10));
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, date('Y'));

$count = [];

// for ($x = 1; $x <= $daysInMonth; $x++) {
//     array_push($count, 0);
// }
if ($sales->num_rows > 0) {
    while ($row = $sales->fetch_assoc()) {
        $filter_date = explode('-',    $row['date_updated']);
        $y = $filter_date[0];
        $m = intval($filter_date[1]);
        $d = intval($filter_date[2]);

        if ($y == date('Y')) {
            if ($m == $month) {
                $items = $conn->query("SELECT p.id, s.qty FROM sales_items s INNER JOIN inventory i ON s.inventory_id = i.id INNER JOIN product_list p ON i.product_id = p.id WHERE sales_id = " . $row['id']);
                if ($items->num_rows > 0) {
                    while ($d = $items->fetch_assoc()) {
                        for ($x = 0; $x < $d['qty']; $x++) {
                            array_push($count, $d['id']);
                        }
                    }
                }
            }
        }
    }
}

$counts = array_count_values($count);

$result = array_map(function ($value, $count) use ($conn) {
    $sql = "SELECT name FROM product_list WHERE id = " . $value;
    $res = $conn->query($sql)->fetch_assoc()['name'];
    return [$res, $count];
}, array_keys($counts), $counts);

usort($result, function ($a, $b) {
    return $b[1] <=> $a[1];
});

$products = array_map(function ($value) {
    return $value[0];
}, $result);

$num = array_map(function ($value) {
    return $value[1];
}, $result);

$data = ['monthName' => $monthname, 'products' => array_slice($products, 0, 5), "data" => array_slice($num, 0, 5)];

echo json_encode($data);
