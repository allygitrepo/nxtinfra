<?php
require('../fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(50,10,'Hello World2!');
$pdf->Cell(50,10,'Hello World3!');
$pdf->Ln(15);
$pdf->Cell(50,0,'Hello World4!');
$pdf->Output();
?>
