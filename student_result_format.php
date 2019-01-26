<?php
class PDF extends FPDF
{
// Page header
function Header()
{
	// Logo
	$this->Image('images/Oduduwa_University.png',20,15,33);
	// Arial bold 15
	$this->SetFont('Arial','B',15);
	// Move to the right
	$this->Cell(100);
	// Title
	$this->Cell(30,16,'ODUDUWA UNIVERSITY IPETUMODU',0,1,'C');
	$this->Cell(100);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,1,'P.M.B, 5533, ILE-IFE',0,1,'C');
	$this->Cell(100);
	$this->SetFont('Arial','I',10);
	$this->Cell(30,10,'Motto: Learning for Human Development',0,1,'C');
	$this->Cell(100);
	$this->SetFont('Arial','B',11);
	$this->Cell(30,10,strtoupper($_SESSION['college']),0,1,'C');
	$this->Cell(100);
	$this->SetFont('Arial','B',11);
	$this->Cell(30,10,strtoupper($_SESSION['dept']),0,1,'C');
	$this->SetFont('Arial','',11);
	$this->SetY(60);
	$this->Cell(8);
	$this->Cell(30,1,strtoupper($_SESSION['session']." session result"),0,1);
	// Line break
	$this->Ln(5);
	$this->Cell(8);
	$this->SetFont('Arial','B',11);
	$this->Cell(30,10,'STUDENT NAME:',0,0);
	$this->Cell(5);
	$this->SetFont('Arial','',11);
	$this->Cell(50,10,strtoupper($_SESSION['name']),0,0);
	$this->Cell(25);
	$this->SetFont('Arial','B',11);
	$this->Cell(31,10,'MATRIC NUMBER:',0,0);
	$this->Cell(5);
	$this->SetFont('Arial','',11);
	$this->Cell(25,10,strtoupper($_SESSION['matric_number']),0,1,'C');
	$this->Cell(8);
	$this->SetFont('Arial','B',11);
	$this->Cell(20,10,'SEMESTER:',0,0);
	$this->Cell(5);
	$this->SetFont('Arial','',11);
	$this->Cell(30,10,strtoupper($_SESSION['semester']),0,0);
	$this->Cell(46);
	$this->SetFont('Arial','B',11);
	$this->Cell(10,10,'LEVEL:',0,0);
	$this->Cell(5);
	$this->SetFont('Arial','',11);
	$this->Cell(15,10,strtoupper($_SESSION['Level']),0,1);
}

// Page footer
function Footer()
{
	$this->Ln(10);

	$this->Cell(10);
	$this->SetFont('Arial','B',8);
	$this->Cell(25,5,'',1,0,'C'); // Second header column
	$this->Cell(12,5,'TP',1,0,'C'); // Third header column 
	$this->Cell(12,5,'TU',1,0,'C');
	$this->Cell(13,5,'GPA',1,1,'C');
	$this->Cell(10);
	$this->SetFont('Arial','B',8);
	$this->Cell(25,5,'CURRENT',1,0,'C'); 
	$this->SetFont('Arial','',8);
	$this->Cell(12,5,$_SESSION['tp'],1,0,'C'); // Third header column 
	$this->Cell(12,5,$_SESSION['tu'],1,0,'C');
	$this->Cell(13,5,$_SESSION['gp'],1,1,'C');
	$this->Cell(10);
	$this->SetFont('Arial','B',8);
	$this->Cell(25,5,'CUMULATIVE',1,0,'C'); // Second header column
	$this->SetFont('Arial','',8);
	$this->Cell(12,5,$_SESSION['ctp'],1,0,'C'); // Third header column 
	$this->Cell(12,5,$_SESSION['ctu'],1,0,'C');
	$this->Cell(13,5,$_SESSION['cgp'],1,1,'C');

	$this->Ln(32);
	//Other footer contents
	$this->Cell(10);
	$this->SetFont('Arial','B',10);
	$this->Cell(50,10,'_______________________________',0,0);
	$this->Cell(60);
	$this->SetFont('Arial','B',10);
	$this->Cell(50,10,'_______________________________',0,1); 
	$this->Cell(10);
	$this->SetTextColor(0,0,0);
	$this->SetFont('Arial','IB',12);
	$this->Cell(50,5,'Head of Department',0,0,'C'); 
	$this->Cell(60);
	$this->SetTextColor(0,0,0);
	$this->SetFont('Arial','IB',12);
	$this->Cell(50,5,'Office of the Provost',0,1,'C');
	
	$this->Ln(7);
	$width_cell=array(54,12,12,12,18);
	$this->SetFont('Arial','B',7);
	$this->Cell(120);
	$this->Cell($width_cell[0],4,'GRADING SYSTEM',0,1,'C'); // First header column 
	$this->Cell(120);
	$this->SetFont('Arial','B',6);
	$this->Cell($width_cell[1],3,'Mark %',0,0,'C'); 
	$this->Cell($width_cell[2],3,'Grade Point',0,0,'C'); // Third header column 
	$this->Cell($width_cell[3],3,'Grade',0,0,'C');
	$this->Cell($width_cell[4],3,'Level of Achievement',0,1,'C');
	
	$this->SetFont('Arial','',7);
	$this->Cell(120);
	$this->Cell($width_cell[1],3,'70-100',0,0,'C'); // Second header column
	$this->Cell($width_cell[2],3,'5',0,0,'C'); // Third header column 
	$this->Cell($width_cell[3],3,'A',0,0,'C');
	$this->Cell($width_cell[4],3,'Excellent',0,1,'C');
	
	$this->Cell(120);
	$this->Cell($width_cell[1],3,'60-69',0,0,'C'); // Second header column
	$this->Cell($width_cell[2],3,'4',0,0,'C'); // Third header column 
	$this->Cell($width_cell[3],3,'B',0,0,'C');
	$this->Cell($width_cell[4],3,'Very Good',0,1,'C');
	
	$this->Cell(120);
	$this->Cell($width_cell[1],3,'50-59',0,0,'C'); // Second header column
	$this->Cell($width_cell[2],3,'3',0,0,'C'); // Third header column 
	$this->Cell($width_cell[3],3,'C',0,0,'C');
	$this->Cell($width_cell[4],3,'Good',0,1,'C');
	
	$this->Cell(120);
	$this->Cell($width_cell[1],3,'45-49',0,0,'C'); // Second header column
	$this->Cell($width_cell[2],3,'2',0,0,'C'); // Third header column 
	$this->Cell($width_cell[3],3,'D',0,0,'C');
	$this->Cell($width_cell[4],3,'Satisfactory',0,1,'C');
	
	$this->Cell(120);
	$this->Cell($width_cell[1],3,'0-44',0,0,'C'); // Second header column
	$this->Cell($width_cell[2],3,'0',0,0,'C'); // Third header column 
	$this->Cell($width_cell[3],3,'F',0,0,'C');
	$this->Cell($width_cell[4],3,'Fail',0,1,'C');
	
	//Result is displayed here
	
	//$this->SetY(-70);
	$this->Cell(10);
	$this->SetY(-20);
	$this->SetFont('Arial','IB',10);
	$this->SetTextColor(255,0,0);
	$this->Cell(100,5,'PLEASE NOTE: Any alteration renders this document invalid',0,0,'C');
	// Position at 1.5 cm from bottom
	$this->SetY(-15);
	// Arial italic 8
	$this->SetFont('Arial','I',8);
	// Page number
	$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$h = $pdf->GetPageHeight();
$w = $pdf->GetPageWidth();
//$pdf->Image('Oduduwa_University.png',0,0,$pdf->w(), $pdf->h());
$pdf->SetFont('Arial','B',10);

$width_cell=array(32,90,15,17,17);
// Header starts /// 
$pdf->Cell(10);
$pdf->Cell($width_cell[0],10,'COURSE CODE',1,0,'C'); // First header column 
$pdf->Cell($width_cell[1],10,'COURSE TITLE',1,0,'C'); // Second header column
$pdf->Cell($width_cell[2],10,'UNIT',1,0,'C'); // Third header column 
$pdf->Cell($width_cell[3],10,'SCORE',1,0,'C');
$pdf->Cell($width_cell[3],10,'GRADE',1,1,'C');
//// header is over ///////
$pdf->SetFont('Arial','',10);
?>