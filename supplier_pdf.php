<?php
include 'db_connect.php';
require 'tcpdf/tcpdf.php';

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('MED-ICT');
$pdf->SetTitle('SUPPLIER LIST');
$pdf->SetSubject('List of suppliers');

// set default header data
$pdf->SetHeaderData('', 0, 'MED-ICT SUPPLIER LIST' . '');

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
$header = array('No.', 'Supplier Name', 'contact', 'email', 'address');


// column widths
$w = array(10, 50, 30, 40, 40);

$pdf->writeHTML('<h4>As of: ' . date('F d, Y') . '</h4><br>');

$tender = "NOEME C. GAVINA, Rph";

// table header
for ($i = 0; $i < count($header); $i++) {
    $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
}
$pdf->Ln();

// data loading
$qry = "SELECT * FROM supplier_list order by id asc";

$result = mysqli_query($conn, $qry);

$total = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $inv = $conn->query("SELECT * FROM supplier_list order by id asc");
    $inv_count = $inv->num_rows;

    // if ($row['prescription'] == 1) $pres = 'Yes';
    // else $pres = 'No';

    // $rightcol = "$row"['supplier_name'].", $row"['contact'].", $row"['address'].", $row"['email']."";

    $total += 1;
    $i++;
    $pdf->SetFont('helvetica', '', 9);
    $pdf->MultiCell($w[0],20, $row['id'], 1, 'L', 0, 0, '', '', true);
    $pdf->Cell($w[1],20, $row['supplier_name'], 1, 0, 'L');
    $pdf->MultiCell($w[2], 20, $row['contact'], 1, 'L', 0, 0, '', '', true);
    $pdf->MultiCell($w[4], 20, $row['email'], 1, 'L', 0, 0, '', '', true); 
    $pdf->MultiCell($w[3], 20, $row['address'], 1, 'L', 0, 0, '', '', true);
       
    $pdf->Ln();

    // $pdf->SetFont('helvetica', '', 9);
    // $pdf->Cell($w[0], 6, $row['id'], 1, 0, 'C');
    // $pdf->Cell($w[1], 6, $row['supplier_name'], 1, 0, 'L');
    // $pdf->Cell($w[1], 6, $row['contact'], 1, 0, 'L');
    // $pdf->Cell($w[1], 6, $row['address'], 1, 0, 'L');
    // $pdf->Cell($w[1], 6, $row['email'], 1, 0, 'L');
    // $pdf->Ln();

    // while ($row2 = $inv->fetch_assoc()) {
    //     $pdf->SetFont('helvetica', '', 7);
    //     $pdf->Cell($w[0], 4, '', 1, 0, 'R');
    //     $pdf->Cell($w[1], 4, $row2['contact'], 1, 0, 'L');
    //     $pdf->Ln();
    // }
}

//Notation
$pdf->writeHTML('<h4><br><br><br>Prepared By:<br></h4>',true, false, false, false, 'R');
$pdf->writeHTML('<h4>'.$tender.'</h4><br><h4>Pharmacy Head<br>Urdaneta District Hospital</h>', true, false, false, false, 'R');

// $pdf->Cell($w[0], 6, '', 1, 0, 'C');
// $pdf->Cell($w[1], 6, '', 1, 0, 'C');
// $pdf->Cell($w[2], 6, 'TOTAL SALES: ', 1, 0, 'R');
// $pdf->Cell($w[3], 6, 'Php ' . number_format($total, 2), 1, 0, 'R');
// $pdf->Ln();

//Close and output PDF document
$pdf->Output('inventory_list.pdf', 'D');

// mysqli_close($db);
