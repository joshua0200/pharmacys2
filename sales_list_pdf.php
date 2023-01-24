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
$pdf->SetHeaderData('', 0, 'MED-ICT SALES LIST' . '');

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
$header = array('Reference #', 'Tender/Cashier', 'Timestamp', 'Total Amount');


extract($_GET);

if ($tender != 0) {
    $tender = "AND user_id = $tender";
} else {
    $tender = "";
}

$datetime = new DateTime($from);
$orig1 = $from;
$from = $datetime->format('Y-m-d H:i:s');
$datetime = new DateTime($to . " 00:00:00");
$orig = $to;
$datetime->modify('+1 day');
$to = $datetime->format('Y-m-d H:i:s');



// data loading
$qry = "SELECT s.*, u.name FROM sales s INNER JOIN users u ON s.user_id = u.id WHERE (s.date_updated BETWEEN '$from' AND '$to') $tender order by date(s.date_updated) desc";

$query = "SELECT * FROM sales s INNER JOIN users u WHERE s.user_id = u.id";
$result = mysqli_query($conn, $qry);

$data = array();
$total = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = array($row['ref_no'], $row['name'], $row['date_updated'], 'Php ' . number_format($row['total_amount'], 2));
    $total += $row['total_amount'];
}

// column widths
$w = array(50, 50, 50, 30);

$pdf->writeHTML('<h4>From: ' . date('F d, Y', strtotime($orig1)) . '</h4><br><h4>To: ' . date('F d, Y', strtotime($orig)) . '</h4><br>');


// table header
for ($i = 0; $i < count($header); $i++) {
    $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
}
$pdf->Ln();

// data rows
foreach ($data as $row) {
    $pdf->Cell($w[0], 6, $row[0], 1, 0, 'C');
    $pdf->Cell($w[1], 6, $row[1], 1, 0, 'C');
    $pdf->Cell($w[2], 6, $row[2], 1, 0, 'C');
    $pdf->Cell($w[3], 6, $row[3], 1, 0, 'R');
    $pdf->Ln();
}

$pdf->Cell($w[0], 6, '', 1, 0, 'C');
$pdf->Cell($w[1], 6, '', 1, 0, 'C');
$pdf->Cell($w[2], 6, 'TOTAL SALES: ', 1, 0, 'R');
$pdf->Cell($w[3], 6, 'Php ' . number_format($total, 2), 1, 0, 'R');
$pdf->Ln();

//Close and output PDF document
$pdf->Output('sales_list.pdf', 'D');

// mysqli_close($db);
