<?php
include '../db_connect.php';

$month = $_POST['month'];
$year = $_POST['year'];
$sales = $conn->query("SELECT * FROM sales WHERE date_updated LIKE '$year%'");


$monthname = date("F", mktime(0, 0, 0, $month, 10));
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, date('Y'));

$count = [];
$total = 0;
for ($x = 1; $x <= $daysInMonth; $x++) {
    array_push($count, 0);
}
if ($sales->num_rows > 0) {
    while ($row = $sales->fetch_assoc()) {
        $filter_date = explode('-',    $row['date_updated']);
        $y = $filter_date[0];
        $m = intval($filter_date[1]);
        $d = intval($filter_date[2]);

        if ($y == date('Y')) {
            if ($m == $month) {
                $count[$d - 1] = $row['total_amount'];
                $total += $row['total_amount'];
            }
        }
    }
}


$data = ['monthName' => $monthname, 'days' => range(1, $daysInMonth), "data" => $count, "total" => number_format($total)];

echo json_encode($data);
