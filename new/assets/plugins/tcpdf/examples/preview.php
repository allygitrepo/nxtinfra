<?php  
error_reporting(E_ALL);
ini_set('display_errors', 1);
/* include('../../common/conn.php');
 ob_start();
$sql=mysql_query("SELECT * FROM `order_master`  WHERE auto_orderid='".$_GET['id']."'");
 $row=mysql_fetch_array($sql);
 $date=date_create($row['date']);
 $order_date=date_create($row['order_date']);
 $order_total=$row['order_total'];
 
 $sql_total=mysql_query("SELECT  SUM(discount) AS discount, c_form AS c_form, excise_duty AS excise_duty FROM `order`  WHERE auto_orderid='".$_GET['id']."'");
$row_total=mysql_fetch_array($sql_total);
 $discount=$row_total['discount'];
 
 $sub_total=($order_total+$discount);
 $percentage=(($discount/$order_total)*100);

 $sql_user=mysql_query("SELECT * FROM user WHERE userId='".$row['user_id']."'");
 $row_user=mysql_fetch_array($sql_user);*/
//============================================================+
// File name   : example_061.php
// Begin       : 2010-05-24
// Last Update : 2014-01-25
//
// Description : Example 061 for TCPDF class
//               XHTML + CSS
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: XHTML + CSS
 * @author Nicola Asuni
 * @since 2010-05-25
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

//$pdf->SetPrintHeader(false);
//define ('PDF_HEADER_LOGO', 'http://andamantrail.com/img/andaman-trail-logo.jpg');
$pdf->SetPrintFooter(false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Andaman Trail');
$pdf->SetTitle('andamantrail.com');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
//$pdf->AddPage('P','A4'); 

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'andamantrail.co', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

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

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 10);

// add a page


/* NOTE:
 * *********************************************************
 * You can load external XHTML using :
 *
 * $html = file_get_contents('/path/to/your/file.html');
 *
 * External CSS files will be automatically loaded.
 * Sometimes you need to fix the path of the external CSS.
 * *********************************************************
 */

// define some HTML content with style
// Set some content to print

$my_html ='';

// output the HTML content
$pdf->writeHTML($my_html, true, false, true, false, '');

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// add a page
$pdf->AddPage();
$my_htmlss='sssss';
$my_html = '<body style="min-height: 1000px; color: rgb(51, 51, 51); background: rgb(224, 219, 207); font-size: 13px; line-height: 1.4; width: 100% !important;" alink="#114eb1" link="#114eb1" bgcolor="#e0dbcf" text="#333333">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width" />
<title>*|MC:SUBJECT|*</title><!-- Facebook sharing information tags -->
<meta property="og:title" content="*|MC:SUBJECT|*" /><style type="text/css">/****** RESETTING DEFAULTS, IT IS BEST TO OVERWRITE THESE STYLES INLINE ********/        /* Forces Hotmail to display emails at full width. */.ExternalClass {	width: 100%;}/* Forces Hotmail to display normal line spacing. */.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {	line-height: 100%;}/* Prevents WebKit and Windows Mobile platforms from changing default font sizes. Resets body padding. */body {	-webkit-text-size-adjust: 100%;	-ms-text-size-adjust: 100%;	text-size-adjust: 100%;	margin: 0;	padding: 0;}/* Reset padding around tables */table {	border-spacing: 0;	border-collapse: collapse;}/* Resolves the Outlook 2007, 2010, and Gmail padding issue. */table td {	border-collapse: collapse;}/* Clean, responsive images. */img {	-ms-interpolation-mode: bicubic;	display: block;	outline: none;	text-decoration: none;}a img {	border: none;}/* This sets a clean slate for all clients EXCEPT Gmail.           From there it forces you to do all of your spacing inline during the development process.           Be sure to stick to margins because paragraph padding is not supported by Outlook 2007/2010.           Remember: Hotmail does not support "margin" nor the "margin-top" properties.           Stick to "margin-bottom", "margin-left", "margin-right" in order to control spacing.           It also wise to set the inline top-margin to "0" for consistancy in Gmail for every inline instance           of a paragraph tag. */p {	margin: 0;	padding: 0;	margin-bottom: 0;}/* This CSS will overwrite Hotmails default CSS and make your headings appear consistant with Gmail.           From there, you can override with inline CSS if needed. */h1, h2, h3, h4, h5, h6 {	color: #333333;	line-height: 100%;}/****** END RESETTING DEFAULTS ********/        /****** EDITABLE STYLES - FOR YOUR TEMPLATE ********/        /* The "body" is defined here for Yahoo Beta because it does not support your body tag. Instead, it will           create a wrapper div around your email and that div will inherit your embedded body styles.           The "#body_style" is defined for AOL because it does not support your embedded body definition nor           your body tag, we will use this class in our wrapper div. */body, #body_style {	width: 100% !important;	min-height: 1000px;	color: #333333;	background: #e0dbcf;	font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;	font-size: 13px;	line-height: 1.4;}/* This is the embedded CSS link color for Gmail. This will overwrite Hotmail and Yahoo Mails embedded           link colors and make them consistent with Gmail. Also use this rule on inline CSS. */a {	color: #114eb1;	text-decoration: none;}/* There is no way to set these inline so you have the option of adding pseudo class definitions here.           They wont work for Gmail or older Lotus Notes but its a nice addition for all other clients. */a:link {	color: #114eb1;	text-decoration: none;}a:visited {	color: #183082;	text-decoration: none;}a:focus {	color: #0066ff !important;}a:hover {	color: #0066ff !important;}/* A nice and clean way to target phone numbers you want clickable and avoid a mobile phone from           linking other numbers that look like, but are not phone numbers. Use these two blocks of code to           "unstyle" any numbers that may be linked. The second block gives you a class ".mobile_link" to apply           with a span tag to the numbers you would like linked and styled.           More info: https://www.campaignmonitor.com/blog/email-marketing/2011/10/using-phone-numbers-in-html-email/ */a[href^="tel"], a[href^="sms"] {	text-decoration: none;	color: #333333;	pointer-events: none;	cursor: default;}.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {	text-decoration: default;	color: #6e5c4f !important;	pointer-events: auto;	cursor: default;}        /****** MEDIA QUERIES ********/        /* Target mobile devices. */        /* @media only screen and (max-device-width: 639px) { */        @media only screen and (max-width: 639px) {/* Hide elements at smaller screen sizes (!important needed to override inline CSS). */.hide {	display: none !important;}/* Adjust table widths at smaller screen sizes. */.table {	width: 320px !important;}.innertable {	width: 280px !important;}/* Resize hero image at smaller screen sizes. */.heroimage {	width: 280px !important;	height: 100px !important;}/* Resize page shadow at smaller screen sizes. */.shadow {	width: 280px !important;	height: 4px !important;}/* Collapse cells at smaller screen sizes. */.collapse-cell {	width: 320px !important;}/* Range social icons left at smaller screen sizes. */.social-media img {	float: left !important;	margin: 0 1em 0 0 !important;}}        /*** END EDITABLE STYLES ***/</style>
<table cellpadding="0" cellspacing="0" border="0" align="center" style="margin: 0px; padding: 0px; width: 100% !important;">
<tbody>
<tr bgcolor="#f0f0f0">
<td>
<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="table"> 
 
<tbody>
<tr>    
<td><!-- set a value for bgcolor -->            
<table bgcolor="#ffffff" width="100%" cellpadding="0" cellspacing="0" border="0">        
<tbody>
<tr>           <!-- header left: logo and link to homepage -->          
<td width="150"><!-- set an image for header left - must be 320px width (height can be variable) -->             <img src="http://andamantrail.com/img/andaman-trail-logo.jpg" width="125" border="0" alt="Header (left)" /></td>          <!-- /header left -->           <!-- header right: hidden in mobile version -->          
<td width="580" class="hide" align="right" style="text-transform: uppercase; padding-right: 5px; font-size: 9.5px;"> Andaman Trail is Registered trademark of Exotrail Destination Management Pvt.Ltd. </td>          <!-- /header right -->         </tr>        
<tr>          
<td width="320" />          
<td width="280" align="right" style="padding-right: 5px; font-size: 9.5px;"> CIN:ABZXCJ12356</td>        </tr>        
<tr>          
<td width="320" />          
<td width="280" align="right" style="padding-right: 5px; font-size: 9.5px;"> GSTIN:29AA123456AS7DFASD</td>        </tr>      </tbody></table></td>  </tr>  
  
<tr bgcolor="#ffffff"> 
     
<td style="padding-top: 20px;">      
     
<table style="margin-bottom: 1em;" width="560" cellpadding="0" cellspacing="0" border="0" align="center" class="innertable"> 
    
 
<tbody>
<tr>       <!-- hero article textarea -->      
<td>
<table width="100%" cellpadding="10" cellspacing="0" border="0">          
<tbody>
<tr>            
<td><!-- hero article heading text -->                            
<h1 style="color: rgb(102, 102, 102); font-size: 26px; line-height: 1.2; font-weight: normal; margin-top: 0px; margin-bottom: 0.5em;">Hi *|CUSTOMER|*</h1>                            <!-- /hero article heading text -->               <!-- hero article paragraph text -->                            
<p style="margin-top: 0px; margin-bottom: 0px;">Lorem ipsum dolor sit amet, quo epicuri volutpat no. <a style="color: rgb(17, 78, 177);" href="#" target="_blank">Causae option accusamus in est</a>. Mea id ignota meliore facilis, cu vim omnium appareat mediocrem. Eu oblique voluptua electram his. Mei eu movet recteque. Vis nulla graeci praesent ad, mediocrem expetenda pro ad.</p>                            <!-- /hero article paragraph text -->                            
<p>Number of Travellers:X Adult(s), Y Child(ren), Z Infant(s)</p></td>          </tr>        </tbody></table></td>      <!-- /hero article textarea -->     </tr>
 </tbody></table>
     
<table bgcolor="#ffffff" width="560" class="innertable" style="margin-left: 20px; border: 1px solid black;">    
     
<tbody>
<tr>      
<td align="center">
<h4>Total Cost of package (Inclusive of all Taxes) ₹39,999,00</h4></td>    </tr>
     </tbody></table>
     
<table bgcolor="#ffffff" width="560" class="innertable" style="margin-left: 20px; border: 1px solid black;">   
     
<tbody>
<tr>      
<td align="center">
<h5><u>Cost Breadkdown</u></h5></td>    </tr>
     </tbody></table>
     
<table bgcolor="#ffffff" width="560" class="innertable" style="margin-left: 20px; border: 1px solid black;">      
<tbody>
<tr>        
<th width="300" style="border: 1px solid black;"> <b>Particulars</b> </th>        
<th width="300" style="border: 1px solid black;"> <b>Cost</b> </th>      </tr>      
<tr>        
<td align="center" style="border: 1px solid black;"> Hotel </td>        
<td align="center" style="border: 1px solid black;"> ₹11,119 </td>      </tr>      
<tr>        
<td align="center" style="border: 1px solid black;"> Vehicle Fare </td>        
<td align="center" style="border: 1px solid black;"> ₹11,119 </td>      </tr>      
<tr>        
<td align="center" style="border: 1px solid black;"> Activities </td>        
<td align="center" style="border: 1px solid black;"> ₹11,119 </td>      </tr>      
<tr>        
<td align="center" style="border: 1px solid black;"> Ferry </td>        
<td align="center" style="border: 1px solid black;"> ₹11,119 </td>      </tr>      
<tr>        
<td align="center" style="border: 1px solid black;"> Hotel </td>        
<td align="center" style="border: 1px solid black;"> ₹11,119 </td>      </tr>    </tbody></table>    <!-- Hotel Content -->    
     
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td width="580" align="center">
<h4>Hotels</h4></td>      </tr>      
<tr>        
<td>
<h4><u style="margin-left: 10px;">Day 1-13th August,2018 | Venue : Port Blair</u></h4></td>      </tr>    </tbody></table>
     
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td width="160"><img src="http://andamantrail.com/mail/Room.jpg" style="margin-left: 10px;" width="160" height="100" border="0" alt="" class="heroimage" /></td>        
<td width="20" />        
<td width="340" valign="top">
<p> Hotel Lorem Ipsum </p>          
<p>#400, Sunshine Road, Phonix Bay,Port Blair</p>          
<p>3 out of 5</p>          
<p>Amenities</p>          
<p><a href="" style="display: inline-block;"><img src="http://andamantrail.com/mail/phone.jpg" width="10" height="10" alt="" /></a> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/wifi.jpg" width="10" height="10" alt="" /></a></span> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/phone.jpg" width="10" height="10" alt="" /></a> </span> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/wifi.jpg" width="10" height="10" alt="" /></a></span> </p></td>      </tr>      
<tr>        
<td width="160"><img src="http://andamantrail.com/mail/Hotel.jpg" style="margin-left: 10px;" width="160" height="100" border="0" alt="" class="heroimage" /></td>        
<td width="20" />        
<td width="340" valign="top">
<p> Hotel Lorem Ipsum </p>          
<p>#400, Sunshine Road, Phonix Bay,Port Blair</p>          
<p>3 out of 5</p>          
<p>Amenities</p>          
<p><a href="" style="display: inline-block;"><img src="http://andamantrail.com/mail/phone.jpg" width="10" height="10" alt="" /></a> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/wifi.jpg" width="10" height="10" alt="" /></a> </span> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/phone.jpg" width="10" height="10" alt="" /></a> </span> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/wifi.jpg" width="10" height="10" alt="" /></a> </span> </p></td>      </tr>    </tbody></table>
     
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td>
<h4><u style="margin-left: 10px;">Day 1-13th August,2018 | Venue : Port Blair</u></h4></td>      </tr>    </tbody></table>
     
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td width="160"><img src="http://andamantrail.com/mail/Room.jpg" style="margin-left: 10px;" width="160" height="100" border="0" alt="" class="heroimage" /></td>        
<td width="20" />        
<td width="340" valign="top">
<p> Hotel Lorem Ipsum </p>          
<p>#400, Sunshine Road, Phonix Bay,Port Blair</p>          
<p>3 out of 5</p>          
<p>Amenities</p>          
<p><a href="" style="display: inline-block;"><img src="http://andamantrail.com/mail/phone.jpg" width="10" height="10" alt="" /></a> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/wifi.jpg" width="10" height="10" alt="" /></a> </span> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/phone.jpg" width="10" height="10" alt="" /></a> </span> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/wifi.jpg" width="10" height="10" alt="" /></a> </span> </p></td>      </tr>      
<tr>        
<td width="160"><img src="http://andamantrail.com/mail/Hotel.jpg" style="margin-left: 10px;" width="160" height="100" border="0" alt="" class="heroimage" /></td>        
<td width="20" />        
<td width="340" valign="top">
<p> Hotel Lorem Ipsum </p>          
<p>#400, Sunshine Road, Phonix Bay,Port Blair</p>          
<p>3 out of 5</p>          
<p>Amenities</p>          
<p><a href="" style="display: inline-block;"><img src="http://andamantrail.com/mail/phone.jpg" width="10" height="10" alt="" /></a> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/wifi.jpg" width="10" height="10" alt="" /></a> </span> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/phone.jpg" width="10" height="10" alt="" /></a> </span> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/wifi.jpg" width="10" height="10" alt="" /></a> </span> </p></td>      </tr>    </tbody></table>
	 
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td width="580" align="center">
<h4>Vehicles</h4></td>      </tr>    </tbody></table>   
	 
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td width="160"><img src="http://andamantrail.com/mail/car.jpg" style="margin-left: 10px;" width="130" height="100" border="0" alt="" class="heroimage" /></td>        
<td width="20" />        
<td width="340" valign="top">
<p> 4 Seater x 1</p>          
<p> 7 Seater x 1</p>          
<p> Total Travellers: 6</p>          
<p>Adults : 4<span style="padding-left: 5px;">Children : 1</span> <span style="padding-left: 5px;">Infant : 1</span> </p></td>      </tr>    </tbody></table>
	 
<table bgcolor="#ffffff" style="margin-left: 10px;">      
<tbody>
<tr>        
<td>
<h4><u>Day 1-13th August,2018 | Venue : Port Blair</u></h4></td>      </tr>      
<tr>        
<td>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p></td>      </tr>    </tbody></table>    
	 
<table bgcolor="#ffffff" style="margin-left: 10px;">      
<tbody>
<tr>        
<td>
<h4><u>Day 2-14th August,2018 | Venue : Port Blair</u></h4></td>      </tr>      
<tr>        
<td>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p></td>      </tr>    </tbody></table>            
    
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td width="200">      </td>            
<td align="center">
<p> Total Travellers: 6</p>          
<p>Adults : 4<span style="padding-left: 5px;">Children : 1</span> <span style="padding-left: 5px;">Infant : 1</span> </p></td>      </tr>    </tbody></table>
    
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td width="580" align="center">
<h4>Ferry</h4></td>      </tr>    </tbody></table>
    
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td>
<h4><u style="margin-left: 10px;">Route : Port Blair to Havelock Islands</u></h4>          
<h4><u style="margin-left: 10px;">14th August,2018</u></h4></td>      </tr>    </tbody></table>
    
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td width="160"><img src="http://andamantrail.com/mail/mak.jpg" style="margin-left: 10px;" width="260" height="150" border="0" alt="" class="heroimage" /></td>        
<td width="20" />        
<td width="340" valign="top">
<p> Hotel Lorem Ipsum </p>          
<p>#400, Sunshine Road, Phonix Bay,Port Blair</p>          
<p>3 out of 5</p>          
<p>Amenities</p>          
<p><a href="" style="display: inline-block;"><img src="http://andamantrail.com/mail/phone.jpg" width="10" height="10" alt="" /></a> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/wifi.jpg" width="10" height="10" alt="" /></a> </span> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/phone.jpg" width="10" height="10" alt="" /></a> </span> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/wifi.jpg" width="10" height="10" alt="" /></a> </span> </p>          
<p> Total Travellers: 6</p>          
<p>Adults : 4<span style="padding-left: 5px;">Children : 1</span> <span style="padding-left: 5px;">Infant : 1</span></p> </td>      </tr>    </tbody></table>
    
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td>
<h4><u style="margin-left: 10px;">Route : Port Blair to Havelock Islands</u></h4>          
<h4><u style="margin-left: 10px;">15th August,2018</u></h4></td>      </tr>    </tbody></table>
    
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td width="160"><img src="http://andamantrail.com/mail/mak.jpg" style="margin-left: 10px;" width="260" height="150" border="0" alt="" class="heroimage" /></td>        
<td width="20" />        
<td width="340" valign="top">
<p> Hotel Lorem Ipsum </p>          
<p>#400, Sunshine Road, Phonix Bay,Port Blair</p>          
<p>3 out of 5</p>          
<p>Amenities</p>          
<p><a href="" style="display: inline-block;"><img src="http://andamantrail.com/mail/phone.jpg" width="10" height="10" alt="" /></a> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/wifi.jpg" width="10" height="10" alt="" /></a> </span> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/phone.jpg" width="10" height="10" alt="" /></a> </span> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/wifi.jpg" width="10" height="10" alt="" /></a> </span> </p>          
<p> Total Travellers: 6</p>          
<p>Adults : 4<span style="padding-left: 5px;">Children : 1</span> <span style="padding-left: 5px;">Infant : 1</span></p> </td>      </tr>    </tbody></table>
    
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td>
<h4><u style="margin-left: 10px;">Route : Port Blair to Havelock Islands</u></h4>          
<h4><u style="margin-left: 10px;">16th August,2018</u></h4></td>      </tr>    </tbody></table>
    
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td width="160"><img src="http://andamantrail.com/mail/mak.jpg" style="margin-left: 10px;" width="260" height="150" border="0" alt="" class="heroimage" /></td>        
<td width="20" />        
<td width="340" valign="top">
<p> Hotel Lorem Ipsum </p>          
<p>#400, Sunshine Road, Phonix Bay,Port Blair</p>          
<p>3 out of 5</p>          
<p>Amenities</p>          
<p><a href="" style="display: inline-block;"><img src="http://andamantrail.com/mail/phone.jpg" width="10" height="10" alt="" /></a> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/wifi.jpg" width="10" height="10" alt="" /></a> </span> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/phone.jpg" width="10" height="10" alt="" /></a> </span> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/wifi.jpg" width="10" height="10" alt="" /></a> </span> </p>          
<p> Total Travellers: 6</p>          
<p>Adults : 4<span style="padding-left: 5px;">Children : 1</span> <span style="padding-left: 5px;">Infant : 1</span></p> </td>      </tr>    </tbody></table>
    
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td width="580" align="center">
<h4>Activiites</h4></td>      </tr>    </tbody></table>
    
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td width="160"><img src="http://andamantrail.com/mail/scuba.jpg" style="margin-left: 10px;" width="260" height="150" border="0" alt="" class="heroimage" /></td>        
<td width="20" />        
<td width="340" valign="top">
<p> Activity: Scuba Diving</p>          
<p> Provider: Sealink Adventure Pvt. Ltd.</p>          
<p> Venue: Elephant Beach</p>          
<p>Number of Tickets: 2</p>          
<p> Rated 3 out of 5</p>          
<p>Amenities</p>          
<p><a href="" style="display: inline-block;"><img src="http://andamantrail.com/mail/phone.jpg" width="10" height="10" alt="" /></a> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/wifi.jpg" width="10" height="10" alt="" /></a> </span> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/phone.jpg" width="10" height="10" alt="" /></a> </span> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/wifi.jpg" width="10" height="10" alt="" /></a> </span> </p></td>      </tr>      
<tr>        
<td width="160"><img src="http://andamantrail.com/mail/seawalk.jpg" style="margin-left: 10px;" width="260" height="150" border="0" alt="" class="heroimage" /></td>        
<td width="20" />        
<td width="340" valign="top">
<p> Hotel Lorem Ipsum </p>          
<p>#400, Sunshine Road, Phonix Bay,Port Blair</p>          
<p>3 out of 5</p>          
<p>Amenities</p>          
<p><a href="" style="display: inline-block;"><img src="http://andamantrail.com/mail/phone.jpg" width="10" height="10" alt="" /></a> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/wifi.jpg" width="10" height="10" alt="" /></a> </span> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/phone.jpg" width="10" height="10" alt="" /></a> </span> <span style="display: inline-block; padding-left: 5px;"><a href=""><img src="http://andamantrail.com/mail/wifi.jpg" width="10" height="10" alt="" /></a> </span> </p></td>      </tr>    </tbody></table>
    
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td width="580" align="center">
<h4>Payment</h4></td>      </tr>    </tbody></table>
    
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td>
<ul>            
<li>Blocking Amount of 10% Rs.10,000.00 Immediately</li>            
<li>Pay second Amount of 40% on 15th, August 2018 of Rs.25,999.00</li>            
<li>Pay third Amount of 50% on 15th, September 2018 of Rs.45,999.00</li>          </ul></td>      </tr>    </tbody></table>
    
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td width="580" align="center">
<h4>Banking Details</h4></td>      </tr>    </tbody></table>
    
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td>
<ul>            
<li>Bank Name: State Bank of India</li>            
<li>Account Holder: Exotrail Destination Management Pvt. Ltd</li>            
<li>Branch: Lorem Ipsum</li>            
<li>IFSC CODE: SBIN000013</li>          </ul></td>      </tr>    </tbody></table>
    
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td width="580" align="center">
<h4>Terms &amp; Conditions</h4></td>      </tr>    </tbody></table>
    
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td>
<ul>            
<li>Bank Name: State Bank of India</li>            
<li>Account Holder: Exotrail Destination Management Pvt. Ltd</li>            
<li>Branch: Lorem Ipsum</li>            
<li>IFSC CODE: SBIN000013</li>          </ul></td>      </tr>    </tbody></table>
    
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td width="580" align="center">
<h4>Cancellation and Refund Policy</h4></td>      </tr>    </tbody></table>
    
<table bgcolor="#ffffff" style="margin-left: 10px;">      
<tbody>
<tr>        
<td>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p></td>      </tr>    </tbody></table>
    
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td width="580" align="center">
<h4>Inclusions</h4></td>      </tr>    </tbody></table>
    
<table bgcolor="#ffffff" style="margin-left: 10px;">      
<tbody>
<tr>        
<td>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p></td>      </tr>    </tbody></table>    
    
<table bgcolor="#ffffff">      
<tbody>
<tr>        
<td width="580" align="center">
<h4>Exclusions</h4></td>      </tr>    </tbody></table>  
    
<table bgcolor="#ffffff" style="margin-left: 10px;">      
<tbody>
<tr>        
<td>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p></td>      </tr>    </tbody></table>    
    <br />
<table bgcolor="#ffffff" style="margin-left: 10px;">            
<tbody>
<tr>        
<td>
<p>With Regards,</p>          
<p>Agent Name </p>          
<p>Destination </p>          
<p>Email </p>          
<p>Direct Contact No </p></td>      </tr>    </tbody></table>   
       </td>    
           </tr>        
            </tbody></table>
              </td>   
               </tr>  
               </tbody></table>
             

';
//echo $my_html; die();
// output the HTML content
$pdf->writeHTML($my_html, true, false, true, false, '');

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('preview.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+
?>
