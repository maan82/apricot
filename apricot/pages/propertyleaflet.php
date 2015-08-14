<?php require_once('Connections/adestate.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_prop_details_RS = "-1";
if (isset($_GET['PROPERTY_ID'])) {
  $colname_prop_details_RS = $_GET['PROPERTY_ID'];
}
$query_prop_details_RS = sprintf("SELECT * FROM property_details_view WHERE PROPERTY_ID = %s", GetSQLValueString($colname_prop_details_RS, "int"));
$prop_details_RS = mysql_query($query_prop_details_RS) or die(mysql_error());
$row_prop_details_RS = mysql_fetch_assoc($prop_details_RS);
$totalRows_prop_details_RS = mysql_num_rows($prop_details_RS);

$colname_images_RS = "-1";
if (isset($_GET['PROPERTY_ID'])) {
  $colname_images_RS = $_GET['PROPERTY_ID'];
}
$query_images_RS = sprintf("SELECT * FROM pictures WHERE PROPERTY_ID = %s", GetSQLValueString($colname_images_RS, "int"));
$images_RS = mysql_query($query_images_RS) or die(mysql_error());
$row_images_RS = mysql_fetch_assoc($images_RS);
$totalRows_images_RS = mysql_num_rows($images_RS);
?>
<?php
require('fpdf.php');

/************************************/
/* global functions                 */
/************************************/
function hex2dec($color = "#000000"){
	$tbl_color = array();
	$tbl_color['R']=hexdec(substr($color, 1, 2));
	$tbl_color['G']=hexdec(substr($color, 3, 2));
	$tbl_color['B']=hexdec(substr($color, 5, 2));
	return $tbl_color;
}

function px2mm($px){
	return $px*25.4/72;
}

function txtentities($html){
	$trans = get_html_translation_table(HTML_ENTITIES);
	$trans = array_flip($trans);
	return strtr($html, $trans);
}



class PDF extends FPDF
{
//Page header
function Header()
{

	//Logo
	$this->Image('images/pdfbanner.jpg',0,0,210);
	
    //Line break
    $this->Ln(8);



}

//Page footer
function Footer()
{
	//Position at 1.5 cm from bottom
	$this->SetY(-20);
	$this->SetDrawColor(238,171,189);
	$this->Line(0,$this->GetY(),210,$this->GetY());
	$this->SetFillColor(242,239,239);
	$this->Rect(0,$this->GetY()+0.36,210,$this->GetY()-20,F);

	$this->SetY(-20+0.36);
	//Arial italic 8
	$this->SetFont('Arial','',8);
	$this->SetTextColor(128,128,128);
	$this->Cell(0,5,'AEON ESTATES | 000 | ABC Road | ABC | Abcdef | XXXXXX',0,1,'C',true);
	$this->Cell(0,5,'E-mail: info@aeon.co.uk | Call 000 00000000',0,1,'C',true);
	$this->Cell(0,5,'www.xyz.co.uk',0,1,'C',true,'http://www.---.co.uk/');
	//Arial italic 8
	$this->SetFont('Arial','I',8);
	//Page number
	$this->Cell(0,5,'Page '.$this->PageNo().'/{nb}',0,0,'R',true);
}
    function PDF_MemImage($orientation='P', $unit='mm', $format='A4')
    {
        $this->FPDF($orientation, $unit, $format);
        //Register var stream protocol
        stream_wrapper_register('var', 'VariableStream');
    }

    function MemImage($data, $x=null, $y=null, $w=0, $h=0, $link='')
    {
        //Display the image contained in $data
        $v = 'img'.md5($data);
        $GLOBALS[$v] = $data;
        $a = getimagesize('var://'.$v);
        if(!$a)
            $this->Error('Invalid image data');
        $type = substr(strstr($a['mime'],'/'),1);
        $this->Image('var://'.$v, $x, $y, $w, $h, $type, $link);
        unset($GLOBALS[$v]);
    }

    function GDImage($im, $x=null, $y=null, $w=0, $h=0, $link='')
    {
        //Display the GD image associated to $im
        ob_start();
        imagepng($im);
        $data = ob_get_clean();
        $this->MemImage($data, $x, $y, $w, $h, $link);
    }

	var $B;
	var $I;
	var $U;
	var $HREF;
	var $fontList;
	var $issetfont;
	var $issetcolor;


	function WriteHTML($html,$bi)
	{
		//remove all unsupported tags
		$this->bi=$bi;
		if ($bi)
			$html=strip_tags($html,"<a><img><p><br><font><tr><blockquote><h1><h2><h3><h4><pre><red><blue><ul><li><hr><b><i><u><strong><em>"); 
		else
			$html=strip_tags($html,"<a><img><p><br><font><tr><blockquote><h1><h2><h3><h4><pre><red><blue><ul><li><hr>"); 
		$html=str_replace("\n",' ',$html); //replace carriage returns by spaces
		// debug
		if ($this->debug) { echo $html; exit; }

		$html = str_replace('&trade;','�',$html);
		$html = str_replace('&copy;','�',$html);
		$html = str_replace('&euro;','�',$html);

		$a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
		$skip=false;
		foreach($a as $i=>$e)
		{
			if (!$skip) {
				if($this->HREF)
					$e=str_replace("\n","",str_replace("\r","",$e));
				if($i%2==0)
				{
					// new line
					if($this->PRE)
						$e=str_replace("\r","\n",$e);
					else
						$e=str_replace("\r","",$e);
					//Text
					if($this->HREF) {
						$this->PutLink($this->HREF,$e);
						$skip=true;
					} else {
						$this->Write(5,stripslashes(txtentities($e)));
						//$this->Write(3,"\n");
					}
				} else {
					//Tag
					if (substr(trim($e),0,1)=='/')
						$this->CloseTag(strtoupper(substr($e,strpos($e,'/'))));
					else {
						//Extract attributes
						$a2=explode(' ',$e);
						$tag=strtoupper(array_shift($a2));
						$attr=array();
						foreach($a2 as $v) {
							if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
								$attr[strtoupper($a3[1])]=$a3[2];
						}
						$this->OpenTag($tag,$attr);
					}
				}
			} else {
				$this->HREF='';
				$skip=false;
			}
		}
	}

	function OpenTag($tag,$attr)
	{
		//Opening tag
		switch($tag){
			case 'STRONG':
			case 'B':
				if ($this->bi)
					$this->SetStyle('B',true);
				else
					$this->SetStyle('U',true);
				break;
			case 'H1':
				$this->Ln(5);
				$this->SetTextColor(150,0,0);
				$this->SetFontSize(22);
				break;
			case 'H2':
				$this->Ln(5);
				$this->SetFontSize(18);
				$this->SetStyle('U',true);
				break;
			case 'H3':
				$this->Ln(5);
				$this->SetFontSize(16);
				$this->SetStyle('U',true);
				break;
			case 'H4':
				$this->Ln(5);
				$this->SetTextColor(102,0,0);
				$this->SetFontSize(14);
				if ($this->bi)
					$this->SetStyle('B',true);
				break;
			case 'PRE':
				$this->SetFont('Courier','',11);
				$this->SetFontSize(11);
				$this->SetStyle('B',false);
				$this->SetStyle('I',false);
				$this->PRE=true;
				break;
			case 'RED':
				$this->SetTextColor(255,0,0);
				break;
			case 'BLOCKQUOTE':
				$this->mySetTextColor(100,0,45);
				$this->Ln(3);
				break;
			case 'BLUE':
				$this->SetTextColor(0,0,255);
				break;
			case 'I':
			case 'EM':
				if ($this->bi)
					$this->SetStyle('I',true);
				break;
			case 'U':
				$this->SetStyle('U',true);
				break;
			case 'A':
				$this->HREF=$attr['HREF'];
				break;
			case 'IMG':
				if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
					if(!isset($attr['WIDTH']))
						$attr['WIDTH'] = 0;
					if(!isset($attr['HEIGHT']))
						$attr['HEIGHT'] = 0;
					$this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
					$this->Ln(3);
				}
				break;
			case 'LI':
				$this->Ln(2);
				$this->SetTextColor(190,0,0);
				$this->Write(3,"\n");
				$this->Write(5,'     � ');
				$this->mySetTextColor(-1);
				break;
			case 'TR':
				$this->Ln(7);
				$this->PutLine();
				break;
			case 'BR':
				$this->Write(3,"\n");
				$this->Ln(2);
				break;
			case 'P':
    			$this->Write(3,"\n");
				$this->Ln(5);
				break;
			case 'HR':
				$this->PutLine();
				break;
			case 'FONT':
				if (isset($attr['COLOR']) && $attr['COLOR']!='') {
					$coul=hex2dec($attr['COLOR']);
					$this->mySetTextColor($coul['R'],$coul['G'],$coul['B']);
					$this->issetcolor=true;
				}
				if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
					$this->SetFont(strtolower($attr['FACE']));
					$this->issetfont=true;
				}
				break;
		}
	}

	function CloseTag($tag)
	{
		//Closing tag
		if ($tag=='H1' || $tag=='H2' || $tag=='H3' || $tag=='H4'){
			$this->Ln(6);
			$this->SetFont('Times','',12);
			$this->SetFontSize(12);
			$this->SetStyle('U',false);
			$this->SetStyle('B',false);
			$this->mySetTextColor(-1);
		}
		if ($tag=='PRE'){
			$this->SetFont('Times','',12);
			$this->SetFontSize(12);
			$this->PRE=false;
		}
		if ($tag=='RED' || $tag=='BLUE')
			$this->mySetTextColor(-1);
		if ($tag=='BLOCKQUOTE'){
			$this->mySetTextColor(0,0,0);
			$this->Ln(3);
		}
		if($tag=='STRONG')
			$tag='B';
		if($tag=='EM')
			$tag='I';
		if((!$this->bi) && $tag=='B')
			$tag='U';
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,false);
		if($tag=='A')
			$this->HREF='';
		if($tag=='FONT'){
			if ($this->issetcolor==true) {
				$this->SetTextColor(0,0,0);
			}
			if ($this->issetfont) {
				$this->SetFont('Times','',12);
				$this->issetfont=false;
			}
		}
	}

	function SetStyle($tag,$enable)
	{
		$this->$tag+=($enable ? 1 : -1);
		$style='';
		foreach(array('B','I','U') as $s) {
			if($this->$s>0)
				$style.=$s;
		}
		$this->SetFont('',$style);
	}

	function PutLink($URL,$txt)
	{
		//Put a hyperlink
		$this->SetTextColor(0,0,255);
		$this->SetStyle('U',true);
		$this->Write(5,$txt,$URL);
		$this->SetStyle('U',false);
		$this->mySetTextColor(-1);
	}

	function PutLine()
	{
		$this->Ln(2);
		$this->Line($this->GetX(),$this->GetY(),$this->GetX()+187,$this->GetY());
		$this->Ln(3);
	}

	function mySetTextColor($r,$g=0,$b=0){
		static $_r=0, $_g=0, $_b=0;

		if ($r==-1) 
			$this->SetTextColor($_r,$_g,$_b);
		else {
			$this->SetTextColor($r,$g,$b);
			$_r=$r;
			$_g=$g;
			$_b=$b;
		}
	}

	function PutMainTitle($title) {
		if (strlen($title)>55)
			$title=substr($title,0,55)."...";
		$this->SetTextColor(33,32,95);
		$this->SetFontSize(20);
		$this->SetFillColor(255,204,120);
		$this->Cell(0,20,$title,1,1,"C",1);
		$this->SetFillColor(255,255,255);
		$this->SetFontSize(12);
		$this->Ln(5);
	}

	function PutMinorHeading($title) {
		$this->SetFontSize(12);
		$this->Cell(0,5,$title,0,1,"C");
		$this->SetFontSize(12);
	}

	function PutMinorTitle($title,$url='') {
		$title=str_replace('http://','',$title);
		if (strlen($title)>70)
			if (!(strrpos($title,'/')==false))
				$title=substr($title,strrpos($title,'/')+1);
		$title=substr($title,0,70);
		$this->SetFontSize(16);
		if ($url!='') {
			$this->SetStyle('U',false);
			$this->SetTextColor(0,0,180);
			$this->Cell(0,6,$title,0,1,"C",0,$url);
			$this->SetTextColor(0,0,0);
			$this->SetStyle('U',false);
		} else

			$this->Cell(0,6,$title,0,1,"C",0);
		$this->SetFontSize(12);
		$this->Ln(4);
	}



}

function Row($data)
{
        $w=50;
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        //$this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,5,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
}


//Instanciation of inherited class
$pdf=new PDF('P','mm','A4');
$pdf->SetMargins(10, 10);
$pdf->AliasNbPages();
$pdf->AddPage();


$pdf->SetFont('Arial','',10);


$pdf->Image($row_prop_details_RS['FULL_PIC_PATH'],null,null,124.88,93.84,JPG);

//Front Page Box
$cellWidth = 35.5 ;
$bulletWidth = 25.0;
$x=$pdf->GetX();
$y=$pdf->GetY();
$x = 140 + $bulletWidth;


$pdf->SetXY(140,25);
$pdf->SetTextColor(204,51,102);
$pdf->MultiCell($cellWidth,5,$row_prop_details_RS['TYPE_SHORT_DESCRIPTION']." ".$row_prop_details_RS['FOR_SHORT_DESCRIPTION']);
$pdf->MultiCell($cellWidth,2,'');



$pdf->SetTextColor(80,80,80);
$y = $pdf->GetY();;
$pdf->SetX(140);
$pdf->Image('images/pdfpropertyno.jpg',null,null,0,5,JPG);
$pdf->SetXY($x+5,$y);
$pdf->MultiCell($cellWidth,5,$row_prop_details_RS['PROPERTY_ID']);

$pdf->MultiCell($cellWidth,2,'');

$y = $pdf->GetY();;
$pdf->SetX(140);
$pdf->Image('images/pdfprice.jpg',null,null,0,5,JPG);
$pdf->SetXY($x,$y);
$pdf->MultiCell($cellWidth,5,number_format( $row_prop_details_RS['PRICE']));

$pdf->MultiCell($cellWidth,2,'');

$y = $pdf->GetY();;
$pdf->SetX(140);
$pdf->Image('images/pdfcity.jpg',null,null,0,5,JPG);
$pdf->SetXY($x,$y);
$pdf->MultiCell($cellWidth,5,$row_prop_details_RS['CITY']);

$pdf->MultiCell($cellWidth,2,'');

$y = $pdf->GetY();
$pdf->SetX(140);
$pdf->Image('images/pdfbedrooms.jpg',null,null,0,5,JPG);
$pdf->SetXY($x,$y);
$pdf->MultiCell($cellWidth,5,$row_prop_details_RS['BEDROOMS']);

$pdf->MultiCell($cellWidth,2,'');

$y = $pdf->GetY();
$pdf->SetX(140);
$pdf->Image('images/pdfaddress.jpg',null,null,0,5,JPG);
$pdf->SetXY(145,$y+5);
$pdf->MultiCell($cellWidth+20,5,$row_prop_details_RS['COUNTRY']);
$pdf->MultiCell($cellWidth,2,'');

$y = $pdf->GetY();
$pdf->SetX(140);
$pdf->Image('images/pdfactiontop.jpg',null,null,60,0,JPG);
$pdf->SetX(140);
$pdf->Image('images/pdfactionhead.jpg',null,null,60,0,JPG);
$pdf->SetX(140);
$pdf->Image('images/pdfactionAV.jpg',null,null,60,0,JPG,'http://'.$_SERVER['SERVER_NAME'].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],"/")).'/property-details.php?FROMPAGE=BROUCHER&PAGE=AV&PROPERTY_ID='.$row_prop_details_RS['PROPERTY_ID']);
$pdf->SetX(140);
$pdf->Image('images/pdfactionVO.jpg',null,null,60,0,JPG,'http://'.$_SERVER['SERVER_NAME'].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],"/")).'/property-details.php?FROMPAGE=BROUCHER&PROPERTY_ID='.$row_prop_details_RS['PROPERTY_ID']);
$pdf->SetX(140);
$pdf->Image('images/pdfactionTF.jpg',null,null,60,0,JPG,'http://'.$_SERVER['SERVER_NAME'].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],"/")).'/property-details.php?FROMPAGE=BROUCHER&PAGE=TF&PROPERTY_ID='.$row_prop_details_RS['PROPERTY_ID']);
$pdf->SetX(140);
$pdf->Image('images/pdfactionbottom.jpg',null,null,60,0,JPG);



$pdf->SetTextColor(10,10,10);
$pdf->SetFont('Arial','B',11);
if($pdf->GetY() < 120)
$pdf->SetXY(10,120);
else
$pdf->SetX(10);
$pdf->MultiCell(60,5,"Property Details ");

$pdf->SetFont('Arial','',10);
$desc = str_replace('</OL>','</OL><BR />',$row_prop_details_RS['BRIEF_DESCRIPTION']);
$desc = str_replace('</UL>','</UL><BR />',$desc);
$pdf->WriteHTML($desc,true);






$picNo = 1;
$oddEven = 'even';
$y  = $pdf->getY()+6;
$x  = 10;
do 
{
	if($picNo > 4)
		break;
		$pdf->setXY($x,$y);
		$pdf->Image($row_images_RS['SLIDE_PIC_PATH'],null,null,44.98,33.73,JPG);
		$x=$x+46.98;
	$picNo++; 
} while ($row_images_RS = mysql_fetch_assoc($images_RS));

$pdf->SetTextColor(212,41,41);
$pdf->SetFont('Arial','B',54);
$pdf->SetXY(10,$y+40.73);
$pdf->MultiCell(0,5,"CALL 020 88137979");


$pdf->Output();
?>
<?php
mysql_free_result($prop_details_RS);

mysql_free_result($images_RS);
?>
