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
$pdf->SetHeaderData('', 0, 'MED-ICT SALES INVENTORY LIST' . '');

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
$header = array('Barcode', 'Product Name', 'Measurement', 'Prescription', 'Price', 'Quantity');


// column widths
$w = array(30, 30, 30, 30, 30, 30);

$pdf->writeHTML('<h4>As of: ' . date('F d, Y') . '</h4><br>');


// table header
for ($i = 0; $i < count($header); $i++) {
    $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
}
$pdf->Ln();

// data loading
$qry = "SELECT p.id, p.name, p.measurement, p.selling_mode, p.price, p.sku, p.prescription, sum(i.qty) as total from product_list p right join inventory i ON p.id = i.product_id WHERE i.expiry_date > '" . date('Y-m-d') . "' group by i.product_id";

$result = mysqli_query($conn, $qry);

$total = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $inv = $conn->query("SELECT * FROM inventory WHERE expiry_date > '" . date('Y-m-d') . "' AND qty > 0 AND product_id = " . $row['id']);
    $inv_count = $inv->num_rows;

    if ($row['prescription'] == 1) $pres = 'Yes';
    else $pres = 'No';

    $total += $row['total'];
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell($w[0], 6, $row['sku'], 1, 0, 'C');
    $pdf->Cell($w[1], 6, $row['name'], 1, 0, 'L');
    $pdf->Cell($w[2], 6, $row['measurement'], 1, 0, 'C');
    $pdf->Cell($w[3], 6, $pres, 1, 0, 'C');
    $pdf->Cell($w[3], 6, 'Php ' . number_format($row['price'], 2) . '/' . $row['selling_mode'], 1, 0, 'R');
    $pdf->Cell($w[3], 6, $row['total'], 1, 0, 'C');
    $pdf->Ln();
    while ($row2 = $inv->fetch_assoc()) {
        $pdf->SetFont('helvetica', '', 7);
        $pdf->Cell($w[0], 4, 'Batch #:', 1, 0, 'R');
        $pdf->Cell($w[1], 4, $row2['batch_no'], 1, 0, 'L');
        $pdf->Cell($w[2], 4, 'Exp Date:', 1, 0, 'R');
        $pdf->Cell($w[3], 4, date('F d, Y', strtotime($row2['expiry_date'])), 1, 0, 'L');
        $pdf->Cell($w[3], 4, 'Quantity:', 1, 0, 'R');
        $pdf->Cell($w[3], 4, $row2['qty'], 1, 0, 'C');
        $pdf->Ln();
    }
}


// $pdf->Cell($w[0], 6, '', 1, 0, 'C');
// $pdf->Cell($w[1], 6, '', 1, 0, 'C');
// $pdf->Cell($w[2], 6, 'TOTAL SALES: ', 1, 0, 'R');
// $pdf->Cell($w[3], 6, 'Php ' . number_format($total, 2), 1, 0, 'R');
// $pdf->Ln();

//Close and output PDF document
$pdf->Output('inventory_list.pdf', 'D');

// mysqli_close($db);
