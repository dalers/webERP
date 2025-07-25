<?php


/*PDF page header for user defined sales reports */

if ($PageNumber>0){
	$pdf->newPage();
}
$PageNumber++;

$FontSize=12;
$LeftOvers = $pdf->addTextWrap($Left_Margin,$Page_Height-$Top_Margin,250,$FontSize,$ReportSpec['reportheading']);
$LeftOvers = $pdf->addTextWrap($Page_Width/2 -60,$Page_Height-$Top_Margin,150,$FontSize,$_SESSION['CompanyRecord']['coyname']);
$LeftOvers = $pdf->addTextWrap($Page_Width-$Right_Margin-160,$Page_Height-$Top_Margin,150,$FontSize,_('Printed') . ': ' . Date($_SESSION['DefaultDateFormat']) . '   ' . _('Page') . ' ' . $PageNumber);


/*Draw a rectangle to put the headings in     */
$Left_Edge = 220;
$Bottom_Edge = $Page_Height-$Top_Margin-38;
$Right_Edge = $Page_Width-$Right_Margin;
$Top_Edge = 26 + $Bottom_Edge;

$pdf->line($Left_Edge, $Bottom_Edge,$Right_Edge, $Bottom_Edge);  /*Draw the bottom line */
$pdf->line($Right_Edge, $Bottom_Edge,$Right_Edge, $Top_Edge);   /*Draw the right side line */
$pdf->line($Right_Edge, $Top_Edge,$Left_Edge, $Top_Edge); /*Draw the top line */
$pdf->line($Left_Edge, $Bottom_Edge,$Left_Edge, $Top_Edge); /*Draw the left side line */


/*Run through the columns and set up the headings */
$FontSize=8;
$Ypos = $Page_Height - $Top_Margin - 24;

DB_data_seek($ColsResult,0);
while ($Cols=DB_fetch_array($ColsResult)){
    $Xpos = 160 + $Cols['colno']*60;
    $LeftOvers = $pdf->addTextWrap($Xpos,$Ypos,60,$FontSize,$Cols['heading1'], 'centre');
    $LeftOvers = $pdf->addTextWrap($Xpos,$Ypos - $LineHeight,60,$FontSize,$Cols['heading2'], 'centre');
}
$Ypos =$Ypos - (2*$LineHeight);
