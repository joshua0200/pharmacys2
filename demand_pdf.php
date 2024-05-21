<?php

include 'db_connect.php';
require 'tcpdf/tcpdf.php';

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('MED-ICT');
$pdf->SetTitle('SALES LIST');
$pdf->SetSubject('List of sales');

// set default header data
$pdf->SetHeaderData('', 0, 'MED-ICT SALES - IN DEMAND PRODUCTS' . '');

// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set font
$pdf->SetFont('helvetica', '', 9);

// add a page
$pdf->AddPage();

// column titles
$header = array('Product Name', 'Total Sold', 'Sales');


// column widths
$w = array(100, 40, 40);

$month = $_GET['id'];

$sales = $conn->query("SELECT * FROM sales");


$monthname = date("F", mktime(0, 0, 0, $month, 10));
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, date('Y'));

$count = [];

// for ($x = 1; $x <= $daysInMonth; $x++) {
//     array_push($count, 0);
// }


$pdf->writeHTML('<h4>Month of: ' . $monthname . ' ' .  date('Y') . '</h4><br>');


// table header
for ($i = 0; $i < count($header); $i++) {
    $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
}
$pdf->Ln();


if ($sales->num_rows > 0) {
    while ($row = $sales->fetch_assoc()) {
        $filter_date = explode('-',    $row['date_updated']);
        $y = $filter_date[0];
        $m = intval($filter_date[1]);
        // $d = intval($filter_date[2]);

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
// print_r($count);
$counts = array_count_values($count);


$result = array_map(function ($value, $count) use ($conn) {
    $sql = "SELECT name, price FROM product_list WHERE id = " . $value;
    $r = $conn->query($sql)->fetch_assoc();
    $res = $r['name'];
    $pr = $r['price'];
    return [$res, $count, $count * $pr];
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

// echo json_encode($data);

$sales = 0;
foreach ($result as $row) {
    $sales += intval($row[2]);
    $pdf->Cell($w[0], 6, $row[0], 1, 0, 'L');
    $pdf->Cell($w[1], 6, $row[1], 1, 0, 'C');
    $pdf->Cell($w[2], 6, 'Php '  . $row[2], 1, 0, 'R');
    $pdf->Ln();
}
$pdf->Cell($w[0], 6, '', 1, 0, 'C');
$pdf->Cell($w[1], 6, 'Total Sales: ', 1, 0, 'R');
$pdf->Cell($w[2], 6, 'Php '  . number_format($sales, 2), 1, 0, 'R');
$pdf->Ln();



$pdf->Output('in_demand_' . $monthname . '.pdf', 'D');
