<?php 
session_start(); 
//die('dddd');;
echo '<p class="test"><img src="https://thumbs.gfycat.com/TameObedientBarnowl-size_restricted.gif"></p>';
include('common/conn.php');
include('common/function.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
$dis=0;
  	$step_1_id=$id=$_GET['id'];
	$new_url = get_tiny_url('http://andamantrail.com/admin/pdf/ET-'.$id.'.pdf');
	estimate_pdf($id);	
$step1=fetch(query('SELECT * FROM `estimate_step1` WHERE estimateId="'.$id.'"'));

	  	$id=$_GET['id'];
		//estimate_pdf($id);	
		$sf=fetch(query('SELECT * FROM  estimate_step1 WHERE estimateId="'.$id.'"'));
		$p=fetch(query('SELECT * FROM `estimate_payment_breakdown` WHERE step_1_id="'.$step_1_id.'"'));
		$doj=$sf['doj'];
		$it_id=$sf['itinerary_id'];
		$gt=0; if($p['grand_hotel_total']!=0){ $hp= $gt=$gt+$p['grand_hotel_total']+$p['m_hotel'];}else{$hp= 0;} ;
if($p['grand_logi_total']!=0){ $lp =$p['grand_logi_total']+$p['m_logistic'];$gt=$gt+$p['grand_logi_total']+$p['m_logistic'];}else{ $lp = 0;};
if($p['grand_tkt_total']!=0){ $tp= $p['grand_tkt_total']+$p['m_ticket'];$gt=$gt+$p['grand_tkt_total']+$p['m_ticket'];}else{$tp= 0;}
if($p['grand_ferry_total']!=0){ $fp= $p['grand_ferry_total']+$p['m_ferry'];$gt=$gt+$p['grand_ferry_total']+$p['m_ferry'];}else{$fp=  0;};
if($p['grand_act_total']!=0){ $ap= $p['grand_act_total']+$p['m_activity'];$gt=$gt+$p['grand_act_total']+$p['m_activity'];}else{$ap= 0;};
 $tex=($gt*5)/100;
 $dt=fetch(query('SELECT * FROM detail  WHERE id=1'));

	
	$sms='Dear '.$sf['name'].', Thank you for showing interest in Andaman Trail, as requested your Andaman tour package for '.$sf['duration'].' Nights &amp; '.($sf['duration']+1) .' Days for '.$step1['adults'].' Adult(s), '.$step1['childern'].' Child(ren), '.$step1['infant'].' Infant(s). The total cost inclusive of all taxes is %E2%82%B9 '.($gt+$tex).'. For detailed inclusions kindly refer to Email and Whatsapp message. Please find your estimate at '.$new_url;
	$wa='Dear '.$sf['name'].', Thank you for showing interest in Andaman Trail, as requested your Andaman tour package for '.$sf['duration'].' Nights &amp; '.($sf['duration']+1) .' Days for '.$step1['adults'].' Adult(s), '.$step1['childern'].' Child(ren), '.$step1['infant'].' Infant(s). The total cost inclusive of all taxes is %E2%82%B9 '.($gt+$tex).'. For detailed inclusions kindly refer to Email and Whatsapp message. Please find your estimate at '.$new_url;
	  $output = preg_replace( '/(0|\+?\d{2})(\d{9,10})/', '$2', $sf['mobile']);

//echo '<pre>';print_r($sf);echo '</pre>';
 require_once('mail/class.phpmailer.php');
$message = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
    <head>
    <!--[if gte mso 9]><xml>
     <o:OfficeDocumentSettings>
      <o:AllowPNG/>
      <o:PixelsPerInch>96</o:PixelsPerInch>
     </o:OfficeDocumentSettings>
    </xml><![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width">
    <!--[if !mso]><!-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--<![endif]-->
    <title></title>
    <style type="text/css" id="media-query">
body {
	margin: 0;
	padding: 0;
}
table, tr, td {
	vertical-align: top;
	border-collapse: collapse;
}
.ie-browser table, .mso-container table {
	table-layout: fixed;
}
* {
	line-height: inherit;
}
a[x-apple-data-detectors=true] {
	color: inherit !important;
	text-decoration: none !important;
}
[owa] .img-container div, [owa] .img-container button {
	display: block !important;
}
[owa] .fullwidth button {
	width: 100% !important;
}
[owa] .block-grid .col {
	display: table-cell;
	float: none !important;
	vertical-align: top;
}
.ie-browser .num12, .ie-browser .block-grid, [owa] .num12, [owa] .block-grid {
	width: 500px !important;
}
.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {
	line-height: 100%;
}
.ie-browser .mixed-two-up .num4, [owa] .mixed-two-up .num4 {
	width: 164px !important;
}
.ie-browser .mixed-two-up .num8, [owa] .mixed-two-up .num8 {
	width: 328px !important;
}
.ie-browser .block-grid.two-up .col, [owa] .block-grid.two-up .col {
	width: 250px !important;
}
.ie-browser .block-grid.three-up .col, [owa] .block-grid.three-up .col {
	width: 166px !important;
}
.ie-browser .block-grid.four-up .col, [owa] .block-grid.four-up .col {
	width: 125px !important;
}
.ie-browser .block-grid.five-up .col, [owa] .block-grid.five-up .col {
	width: 100px !important;
}
.ie-browser .block-grid.six-up .col, [owa] .block-grid.six-up .col {
	width: 83px !important;
}
.ie-browser .block-grid.seven-up .col, [owa] .block-grid.seven-up .col {
	width: 71px !important;
}
.ie-browser .block-grid.eight-up .col, [owa] .block-grid.eight-up .col {
	width: 62px !important;
}
.ie-browser .block-grid.nine-up .col, [owa] .block-grid.nine-up .col {
	width: 55px !important;
}
.ie-browser .block-grid.ten-up .col, [owa] .block-grid.ten-up .col {
	width: 50px !important;
}
.ie-browser .block-grid.eleven-up .col, [owa] .block-grid.eleven-up .col {
	width: 45px !important;
}
.ie-browser .block-grid.twelve-up .col, [owa] .block-grid.twelve-up .col {
	width: 41px !important;
}
 @media only screen and (min-width: 520px) {
.block-grid {
	width: 500px !important;
}
.block-grid .col {
	vertical-align: top;
}
.block-grid .col.num12 {
	width: 500px !important;
}
.block-grid.mixed-two-up .col.num4 {
	width: 164px !important;
}
.block-grid.mixed-two-up .col.num8 {
	width: 328px !important;
}
.block-grid.two-up .col {
	width: 250px !important;
}
.block-grid.three-up .col {
	width: 166px !important;
}
.block-grid.four-up .col {
	width: 125px !important;
}
.block-grid.five-up .col {
	width: 100px !important;
}
.block-grid.six-up .col {
	width: 83px !important;
}
.block-grid.seven-up .col {
	width: 71px !important;
}
.block-grid.eight-up .col {
	width: 62px !important;
}
.block-grid.nine-up .col {
	width: 55px !important;
}
.block-grid.ten-up .col {
	width: 50px !important;
}
.block-grid.eleven-up .col {
	width: 45px !important;
}
.block-grid.twelve-up .col {
	width: 41px !important;
}
}
 @media (max-width: 520px) {
.block-grid, .col {
	min-width: 320px !important;
	max-width: 100% !important;
	display: block !important;
}
.block-grid {
	width: calc(100% - 40px) !important;
}
.col {
	width: 100% !important;
}

.col > div {
	margin: 0 auto;
}
img.fullwidth, img.fullwidthOnMobile {
	max-width: 100% !important;
}
.no-stack .col {
	min-width: 0 !important;
	display: table-cell !important;
}
.no-stack.two-up .col {
	width: 50% !important;
}
.no-stack.mixed-two-up .col.num4 {
	width: 33% !important;
}
.no-stack.mixed-two-up .col.num8 {
	width: 66% !important;
}
.no-stack.three-up .col.num4 {
	width: 33% !important;
}
.no-stack.four-up .col.num3 {
	width: 25% !important;
}
.mobile_hide {
	min-height: 0px;
	max-height: 0px;
	max-width: 0px;
	display: none;
	overflow: hidden;
	font-size: 0px;
}
}
</style>
    </head>
    <body class="clean-body" style="margin: 0;padding: 0;-webkit-text-size-adjust: 100%;background-color: #FFFFFF">
<style type="text/css" id="media-query-bodytag">
    @media (max-width: 520px) {
      .block-grid {
        min-width: 320px!important;
        max-width: 100%!important;
        width: 100%!important;
        display: block!important;
      }

      .col {
        min-width: 320px!important;
        max-width: 100%!important;
        width: 100%!important;
        display: block!important;
      }

        .col > div {
          margin: 0 auto;
        }

      img.fullwidth {
        max-width: 100%!important;
      }
			img.fullwidthOnMobile {
        max-width: 100%!important;
      }
      .no-stack .col {
				min-width: 0!important;
				display: table-cell!important;
			}
			.no-stack.two-up .col {
				width: 50%!important;
			}
			.no-stack.mixed-two-up .col.num4 {
				width: 33%!important;
			}
			.no-stack.mixed-two-up .col.num8 {
				width: 66%!important;
			}
			.no-stack.three-up .col.num4 {
				width: 33%!important;
			}
			.no-stack.four-up .col.num3 {
				width: 25%!important;
			}
      .mobile_hide {
        min-height: 0px!important;
        max-height: 0px!important;
        max-width: 0px!important;
        display: none!important;
        overflow: hidden!important;
        font-size: 0px!important;
      }
    }
  </style>
<!--[if IE]><div class="ie-browser"><![endif]--> 
<!--[if mso]><div class="mso-container"><![endif]-->
<table class="nl-container" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 320px;Margin: 0 auto;background-color: #FFFFFF;width: 100%" cellpadding="0" cellspacing="0">
      <tbody>
    <tr style="vertical-align: top">
          <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top"><!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color: #FFFFFF;"><![endif]-->
        
        <div style="background-color:transparent;">
              <div style="Margin: 0 auto;min-width: 320px;max-width: 500px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;" class="block-grid ">
            <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;"> 
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="background-color:transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width: 500px;"><tr class="layout-full-width" style="background-color:transparent;"><![endif]--> 
                  
                  <!--[if (mso)|(IE)]><td align="center" width="500" style=" width:500px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num12" style="min-width: 320px;max-width: 500px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div align="right" class="img-container right fixedwidth " style="padding-right: 0px;  padding-left: 0px;"> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px;line-height:0px;"><td style="padding-right: 0px; padding-left: 0px;" align="right"><![endif]--> 
                          <img class="right fixedwidth" align="right" border="0" src="http://andamantrail.com/images/logo.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;height: auto;float: none;width: 100%;max-width: 125px" width="125"> 
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]--> 
                </div>
          </div>
            </div>
        <div style="background-color:transparent;">
              <div style="Margin: 0 auto;min-width: 320px;max-width: 500px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;" class="block-grid ">
            <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;"> 
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="background-color:transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width: 500px;"><tr class="layout-full-width" style="background-color:transparent;"><![endif]--> 
                  
                  <!--[if (mso)|(IE)]><td align="center" width="500" style=" width:500px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num12" style="min-width: 320px;max-width: 500px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="divider " style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 100%;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                          <tbody>
                        <tr style="vertical-align: top">
                              <td class="divider_inner" style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding-right: 10px;padding-left: 10px;padding-top: 10px;padding-bottom: 10px;min-width: 100%;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%"><table class="divider_content" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #BBBBBB;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                  <tbody>
                                  <tr style="vertical-align: top">
                                      <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%"><span></span></td>
                                    </tr>
                                </tbody>
                                </table></td>
                            </tr>
                      </tbody>
                        </table>
                    <div class=""> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                          <div style="color:#555555;font-family:Verdana, Geneva, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                        <div style="font-size:12px;line-height:14px;font-family:Verdana, Geneva, sans-serif;color:#555555;text-align:left;">
                              <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: center"><strong>Estimate ID : EST-'.$id.'</strong></p>
                            </div>
                      </div>
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="divider " style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 100%;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                          <tbody>
                        <tr style="vertical-align: top">
                              <td class="divider_inner" style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding-right: 10px;padding-left: 10px;padding-top: 10px;padding-bottom: 10px;min-width: 100%;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%"><table class="divider_content" height="0px" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #BBBBBB;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                  <tbody>
                                  <tr style="vertical-align: top">
                                      <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;font-size: 0px;line-height: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%"><span>&#160;</span></td>
                                    </tr>
                                </tbody>
                                </table></td>
                            </tr>
                      </tbody>
                        </table>
                    <div class=""> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                          <div style="color:#555555;font-family:Verdana, Geneva, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                        <div style="font-size:12px;line-height:14px;font-family:Verdana, Geneva, sans-serif;color:#555555;text-align:left;">
                              <p style="margin: 0;font-size: 14px;line-height: 17px"><span style="font-size: 14px; line-height: 16px;">Dear <em><strong>'.$sf['name'].'</strong></em>,</span></p>
                              <p style="margin: 0;font-size: 14px;line-height: 17px">&#160;</p>
                              <p style="margin: 0;font-size: 14px;line-height: 17px">Your <em><strong>'.$sf['duration'].' Nights &amp; '.($sf['duration']+1).' Days</strong></em> itinerary to Andaman looks perfect. Here is the costing for the inclusions we have selected.</p>
                              <p style="margin: 0;font-size: 14px;line-height: 17px">&#160;</p>
                              <p style="margin: 0;font-size: 14px;line-height: 17px">If you feel like giving a read on the destination you are heading to, make use of&#160;<strong>Andaman Trail guides!</strong></p>
                              <p style="margin: 0;font-size: 14px;line-height: 17px"><br>
                            Also, giving you a heads-up! The prices of airlines and hotels fluctuate a lot. Book your trip before the rates change!</p>
                            </div>
                      </div>
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]--> 
                </div>
          </div>
            </div>
        <div style="background-color:transparent;">
              <div style="Margin: 0 auto;min-width: 320px;max-width: 500px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;" class="block-grid ">
            <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;"> 
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="background-color:transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width: 500px;"><tr class="layout-full-width" style="background-color:transparent;"><![endif]--> 
                  
                  <!--[if (mso)|(IE)]><td align="center" width="500" style=" width:500px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num12" style="min-width: 320px;max-width: 500px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="divider " style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 100%;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                          <tbody>
                        <tr style="vertical-align: top">
                              <td class="divider_inner" style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding-right: 10px;padding-left: 10px;padding-top: 10px;padding-bottom: 10px;min-width: 100%;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%"><table class="divider_content" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #BBBBBB;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                  <tbody>
                                  <tr style="vertical-align: top">
                                      <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%"><span></span></td>
                                    </tr>
                                </tbody>
                                </table></td>
                            </tr>
                      </tbody>
                        </table>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]--> 
                </div>
          </div>
            </div>
        <div style="background-color:transparent;">
              <div style="Margin: 0 auto;min-width: 320px;max-width: 500px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;" class="block-grid three-up no-stack">
            <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;"> 
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="background-color:transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width: 500px;"><tr class="layout-full-width" style="background-color:transparent;"><![endif]--> 
                  
                  <!--[if (mso)|(IE)]><td align="center" width="167" style=" width:167px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num4" style="max-width: 320px;min-width: 166px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div align="center" class="img-container center fixedwidth " style="padding-right: 0px;  padding-left: 0px;"> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px;line-height:0px;"><td style="padding-right: 0px; padding-left: 0px;" align="center"><![endif]--> 
                          <img class="center fixedwidth" align="center" border="0" src="http://andamantrail.com/images/bedroom.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;height: auto;float: none;width: 100%;max-width: 33.4px" width="33.4"> 
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td><td align="center" width="167" style=" width:167px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num4" style="max-width: 320px;min-width: 166px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div class=""> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                          <div style="color:#555555;font-family:Verdana, Geneva, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                        <div style="font-size:12px;line-height:14px;font-family:Verdana, Geneva, sans-serif;color:#555555;text-align:left;">
                              <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: left"><strong>Hotels</strong></p>
                            </div>
                      </div>
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td><td align="center" width="167" style=" width:167px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num4" style="max-width: 320px;min-width: 166px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div class=""> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                          <div style="color:#555555;font-family:Verdana, Geneva, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                        <div style="font-size:12px;line-height:14px;font-family:Verdana, Geneva, sans-serif;color:#555555;text-align:left;">
                              <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: left"><strong>₹ '.$hp.'</strong></p>
                            </div>
                      </div>
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]--> 
                </div>
          </div>
            </div>
        <div style="background-color:transparent;">
              <div style="Margin: 0 auto;min-width: 320px;max-width: 500px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;" class="block-grid three-up no-stack">
            <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;"> 
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="background-color:transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width: 500px;"><tr class="layout-full-width" style="background-color:transparent;"><![endif]--> 
                  
                  <!--[if (mso)|(IE)]><td align="center" width="167" style=" width:167px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num4" style="max-width: 320px;min-width: 166px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div align="center" class="img-container center  autowidth  " style="padding-right: 0px;  padding-left: 0px;"> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px;line-height:0px;"><td style="padding-right: 0px; padding-left: 0px;" align="center"><![endif]--> 
                          <img class="center  autowidth " align="center" border="0" src="http://andamantrail.com/images/taxi.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;height: auto;float: none;width: 100%;max-width: 32px" width="32"> 
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td><td align="center" width="167" style=" width:167px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num4" style="max-width: 320px;min-width: 166px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div class=""> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                          <div style="color:#555555;font-family:Verdana, Geneva, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                        <div style="font-size:12px;line-height:14px;font-family:Verdana, Geneva, sans-serif;color:#555555;text-align:left;">
                              <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: left"><strong>Vehicles / Transfers</strong></p>
                            </div>
                      </div>
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td><td align="center" width="167" style=" width:167px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num4" style="max-width: 320px;min-width: 166px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div class=""> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                          <div style="color:#555555;font-family:Verdana, Geneva, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                        <div style="font-size:12px;line-height:14px;font-family:Verdana, Geneva, sans-serif;color:#555555;text-align:left;">
                              <p style="margin: 0;font-size: 14px;line-height: 17px"><strong>₹ '.$lp.'</strong></p>
                            </div>
                      </div>
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]--> 
                </div>
          </div>
            </div>
        <div style="background-color:transparent;">
              <div style="Margin: 0 auto;min-width: 320px;max-width: 500px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;" class="block-grid three-up no-stack">
            <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;"> 
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="background-color:transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width: 500px;"><tr class="layout-full-width" style="background-color:transparent;"><![endif]--> 
                  
                  <!--[if (mso)|(IE)]><td align="center" width="167" style=" width:167px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num4" style="max-width: 320px;min-width: 166px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div align="center" class="img-container center  autowidth  " style="padding-right: 0px;  padding-left: 0px;"> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px;line-height:0px;"><td style="padding-right: 0px; padding-left: 0px;" align="center"><![endif]--> 
                          <img class="center  autowidth " align="center" border="0" src="http://andamantrail.com/images/movie-tickets.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;height: auto;float: none;width: 100%;max-width: 32px" width="32"> 
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td><td align="center" width="167" style=" width:167px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num4" style="max-width: 320px;min-width: 166px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div class=""> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                          <div style="color:#555555;font-family:Verdana, Geneva, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                        <div style="font-size:12px;line-height:14px;font-family:Verdana, Geneva, sans-serif;color:#555555;text-align:left;">
                              <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: left"><strong>Tickets / Permit / Fares</strong></p>
                            </div>
                      </div>
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td><td align="center" width="167" style=" width:167px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num4" style="max-width: 320px;min-width: 166px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div class=""> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                          <div style="color:#555555;font-family:Verdana, Geneva, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                        <div style="font-size:12px;line-height:14px;font-family:Verdana, Geneva, sans-serif;color:#555555;text-align:left;">
                              <p style="margin: 0;font-size: 14px;line-height: 17px"><strong>₹ '.$tp.'</strong></p>
                            </div>
                      </div>
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]--> 
                </div>
          </div>
            </div>
        <div style="background-color:transparent;">
              <div style="Margin: 0 auto;min-width: 320px;max-width: 500px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;" class="block-grid three-up no-stack">
            <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;"> 
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="background-color:transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width: 500px;"><tr class="layout-full-width" style="background-color:transparent;"><![endif]--> 
                  
                  <!--[if (mso)|(IE)]><td align="center" width="167" style=" width:167px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num4" style="max-width: 320px;min-width: 166px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div align="center" class="img-container center  autowidth  " style="padding-right: 0px;  padding-left: 0px;"> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px;line-height:0px;"><td style="padding-right: 0px; padding-left: 0px;" align="center"><![endif]--> 
                          <img class="center  autowidth " align="center" border="0" src="http://andamantrail.com/images/ferry-facing-right.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;height: auto;float: none;width: 100%;max-width: 32px" width="32"> 
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td><td align="center" width="167" style=" width:167px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num4" style="max-width: 320px;min-width: 166px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div class=""> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                          <div style="color:#555555;font-family:Verdana, Geneva, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                        <div style="font-size:12px;line-height:14px;font-family:Verdana, Geneva, sans-serif;color:#555555;text-align:left;">
                              <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: left"><strong>Ferry / Transfers</strong></p>
                            </div>
                      </div>
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td><td align="center" width="167" style=" width:167px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num4" style="max-width: 320px;min-width: 166px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div class=""> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                          <div style="color:#555555;font-family:Verdana, Geneva, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                        <div style="font-size:12px;line-height:14px;font-family:Verdana, Geneva, sans-serif;color:#555555;text-align:left;">
                              <p style="margin: 0;font-size: 14px;line-height: 17px"><strong>₹ '.$fp.'</strong></p>
                            </div>
                      </div>
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]--> 
                </div>
          </div>
            </div>
        <div style="background-color:transparent;">
              <div style="Margin: 0 auto;min-width: 320px;max-width: 500px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;" class="block-grid three-up no-stack">
            <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;"> 
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="background-color:transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width: 500px;"><tr class="layout-full-width" style="background-color:transparent;"><![endif]--> 
                  
                  <!--[if (mso)|(IE)]><td align="center" width="167" style=" width:167px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num4" style="max-width: 320px;min-width: 166px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div align="center" class="img-container center  autowidth  " style="padding-right: 0px;  padding-left: 0px;"> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px;line-height:0px;"><td style="padding-right: 0px; padding-left: 0px;" align="center"><![endif]--> 
                          <img class="center  autowidth " align="center" border="0" src="http://andamantrail.com/images/diving.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;height: auto;float: none;width: 100%;max-width: 32px" width="32"> 
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td><td align="center" width="167" style=" width:167px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num4" style="max-width: 320px;min-width: 166px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div class=""> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                          <div style="color:#555555;font-family:Verdana, Geneva, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                        <div style="font-size:12px;line-height:14px;font-family:Verdana, Geneva, sans-serif;color:#555555;text-align:left;">
                              <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: left"><strong>Activities</strong></p>
                            </div>
                      </div>
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td><td align="center" width="167" style=" width:167px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num4" style="max-width: 320px;min-width: 166px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div class=""> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                          <div style="color:#555555;font-family:Verdana, Geneva, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                        <div style="font-size:12px;line-height:14px;font-family:Verdana, Geneva, sans-serif;color:#555555;text-align:left;">
                              <p style="margin: 0;font-size: 14px;line-height: 17px"><strong>₹ '.$ap.'</strong></p>
                            </div>
                      </div>
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]--> 
                </div>
          </div>
            </div>
        <div style="background-color:transparent;">
              <div style="Margin: 0 auto;min-width: 320px;max-width: 500px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;" class="block-grid three-up no-stack">
            <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;"> 
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="background-color:transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width: 500px;"><tr class="layout-full-width" style="background-color:transparent;"><![endif]--> 
                  
                  <!--[if (mso)|(IE)]><td align="center" width="167" style=" width:167px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num4" style="max-width: 320px;min-width: 166px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div align="center" class="img-container center  autowidth  " style="padding-right: 0px;  padding-left: 0px;"> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px;line-height:0px;"><td style="padding-right: 0px; padding-left: 0px;" align="center"><![endif]--> 
                          <img class="center  autowidth " align="center" border="0" src="http://andamantrail.com/images/tax.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;height: auto;float: none;width: 100%;max-width: 32px" width="32"> 
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td><td align="center" width="167" style=" width:167px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num4" style="max-width: 320px;min-width: 166px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div class=""> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                          <div style="color:#555555;font-family:Verdana, Geneva, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                        <div style="font-size:12px;line-height:14px;font-family:Verdana, Geneva, sans-serif;color:#555555;text-align:left;">
                              <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: left"><strong>GST / Taxes</strong></p>
                            </div>
                      </div>
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td><td align="center" width="167" style=" width:167px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num4" style="max-width: 320px;min-width: 166px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div class=""> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                          <div style="color:#555555;font-family:Verdana, Geneva, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                        <div style="font-size:12px;line-height:14px;font-family:Verdana, Geneva, sans-serif;color:#555555;text-align:left;">
                              <p style="margin: 0;font-size: 14px;line-height: 17px"><strong>₹ '.$tex.'</strong></p>
                            </div>
                      </div>
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]--> 
                </div>
          </div>
            </div>
        <div style="background-color:transparent;">
              <div style="Margin: 0 auto;min-width: 320px;max-width: 500px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;" class="block-grid three-up no-stack">
            <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;"> 
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="background-color:transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width: 500px;"><tr class="layout-full-width" style="background-color:transparent;"><![endif]--> 
                  
                  <!--[if (mso)|(IE)]><td align="center" width="167" style=" width:167px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num4" style="max-width: 320px;min-width: 166px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div align="center" class="img-container center  autowidth  " style="padding-right: 0px;  padding-left: 0px;"> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px;line-height:0px;"><td style="padding-right: 0px; padding-left: 0px;" align="center"><![endif]--> 
                          <img class="center  autowidth " align="center" border="0" src="http://andamantrail.com/images/tag.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;height: auto;float: none;width: 100%;max-width: 32px" width="32"> 
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td><td align="center" width="167" style=" width:167px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num4" style="max-width: 320px;min-width: 166px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div class=""> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                          <div style="color:#555555;font-family:Verdana, Geneva, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                        <div style="font-size:12px;line-height:14px;font-family:Verdana, Geneva, sans-serif;color:#555555;text-align:left;">
                              <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: left"><strong>Discount / Offers / Coupons</strong></p>
                            </div>
                      </div>
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td><td align="center" width="167" style=" width:167px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num4" style="max-width: 320px;min-width: 166px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div class=""> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                          <div style="color:#555555;font-family:Verdana, Geneva, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                        <div style="font-size:12px;line-height:14px;font-family:Verdana, Geneva, sans-serif;color:#555555;text-align:left;">
                              <p style="margin: 0;font-size: 14px;line-height: 17px"><strong>₹ '.$dis.'</strong></p>
                            </div>
                      </div>
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]--> 
                </div>
          </div>
            </div>
        <div style="background-color:transparent;">
              <div style="Margin: 0 auto;min-width: 320px;max-width: 500px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;" class="block-grid ">
            <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;"> 
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="background-color:transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width: 500px;"><tr class="layout-full-width" style="background-color:transparent;"><![endif]--> 
                  
                  <!--[if (mso)|(IE)]><td align="center" width="500" style=" width:500px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num12" style="min-width: 320px;max-width: 500px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="divider " style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 100%;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                          <tbody>
                        <tr style="vertical-align: top">
                              <td class="divider_inner" style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding-right: 10px;padding-left: 10px;padding-top: 10px;padding-bottom: 10px;min-width: 100%;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%"><table class="divider_content" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #BBBBBB;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                  <tbody>
                                  <tr style="vertical-align: top">
                                      <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%"><span></span></td>
                                    </tr>
                                </tbody>
                                </table></td>
                            </tr>
                      </tbody>
                        </table>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]--> 
                </div>
          </div>
            </div>
        <div style="background-color:transparent;">
              <div style="Margin: 0 auto;min-width: 320px;max-width: 500px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;" class="block-grid ">
            <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;"> 
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="background-color:transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width: 500px;"><tr class="layout-full-width" style="background-color:transparent;"><![endif]--> 
                  
                  <!--[if (mso)|(IE)]><td align="center" width="500" style=" width:500px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num12" style="min-width: 320px;max-width: 500px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div class=""> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                          <div style="color:#555555;font-family:Verdana, Geneva, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                        <div style="line-height:14px;font-size:12px;font-family:Verdana, Geneva, sans-serif;color:#555555;text-align:left;">
                              <p style="margin: 0;line-height: 14px;text-align: center;font-size: 12px"><span style="color: rgb(128, 128, 128); font-size: 12px; line-height: 14px;"><strong><span style="font-size: 20px; line-height: 24px;">Total Cost</span></strong></span></p>
                              <p style="margin: 0;line-height: 14px;text-align: center;font-size: 12px"><span style="color: rgb(128, 128, 128); font-size: 12px; line-height: 14px;">&#160;</span></p>
                              <p style="margin: 0;line-height: 14px;text-align: center;font-size: 12px"><span style="color: rgb(128, 128, 128); font-size: 12px; line-height: 14px;"><strong><span style="font-size: 14px; line-height: 16px;">* Inclusive of all Taxes</span></strong></span></p>
                              <p style="margin: 0;line-height: 14px;text-align: center;font-size: 12px"><span style="color: rgb(128, 128, 128); font-size: 12px; line-height: 14px;">&#160;</span></p>
                              <p style="margin: 0;line-height: 14px;text-align: center;font-size: 12px"><span style="color: rgb(128, 128, 128); font-size: 12px; line-height: 14px;"><em><span style="font-size: 14px; line-height: 16px;">For '.$step1['adults'].' Adult(s), '.$step1['childern'].' Child(ren), '.$step1['infant'].'Infant(s)</span></em></span></p>
                              <p style="margin: 0;line-height: 14px;text-align: center;font-size: 12px">&#160;</p>
                              <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: center"><span style="font-size: 20px; line-height: 24px;"><strong><span style="line-height: 24px; color: rgb(153, 204, 0); font-size: 20px;">₹ '.($gt+$tex).'</span></strong></span></p>
                            </div>
                      </div>
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="divider " style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 100%;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                          <tbody>
                        <tr style="vertical-align: top">
                              <td class="divider_inner" style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding-right: 10px;padding-left: 10px;padding-top: 10px;padding-bottom: 10px;min-width: 100%;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%"><table class="divider_content" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #BBBBBB;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                  <tbody>
                                  <tr style="vertical-align: top">
                                      <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%"><span></span></td>
                                    </tr>
                                </tbody>
                                </table></td>
                            </tr>
                      </tbody>
                        </table>
                    
                    <!--[if (!mso)&(!IE)]><!--></div>
                      <!--<![endif]--> 
                    </div>
              </div>
                  <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]--> 
                </div>
          </div>
            </div>
        <div style="background-color:transparent;">
              <div style="Margin: 0 auto;min-width: 320px;max-width: 500px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;" class="block-grid ">
            <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;"> 
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="background-color:transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width: 500px;"><tr class="layout-full-width" style="background-color:transparent;"><![endif]--> 
                  
                  <!--[if (mso)|(IE)]><td align="center" width="500" style=" width:500px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                  <div class="col num12" style="min-width: 320px;max-width: 500px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;"> 
                      <!--[if (!mso)&(!IE)]><!-->
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->
                    
                    <div class=""> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                          <div style="color:#555555;font-family:Verdana, Geneva, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                        <div style="font-size:12px;line-height:14px;font-family:Verdana, Geneva, sans-serif;color:#555555;text-align:left;">
                              <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: center"><strong><span style="font-size: 20px; line-height: 24px;"><span style="color: rgb(128, 128, 128); font-size: 20px; line-height: 24px;"></span>PAYMENT TERMS</span></strong></p>
                            </div>
                      </div>
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="divider " style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 100%;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                          <tbody>
                        <tr style="vertical-align: top">
                              <td class="divider_inner" style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding-right: 10px;padding-left: 10px;padding-top: 10px;padding-bottom: 10px;min-width: 100%;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%"><table class="divider_content" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #BBBBBB;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                  <tbody>
                                  <tr style="vertical-align: top">
                                      <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%"><span></span></td>
                                    </tr>
                                </tbody>
                                </table></td>
                            </tr>
                      </tbody>
                        </table>
                    <div class=""> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                          <div style="color:#555555;font-family:Verdana, Geneva, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                        <div style="font-size:12px;line-height:14px;font-family:Verdana, Geneva, sans-serif;color:#555555;text-align:left;">
                              <ul>';
							 $pay=query('SELECT * FROM `estimate_payment` WHERE step_1_id="'.$id.'"');
$i=1;  
while($pr=fetch($pay)){
	if($pr['immediate']==0){$imm='';}else{$imm='immediately to block';}
	$message=$message.'
                            <li style="font-size: 14px; line-height: 16px;"><span style="font-size: 12px; line-height: 14px; color: rgb(128, 128, 128);">'.$pr['payment_details'].' amount of&#160;₹ '.(($gt+$tex)*$pr['percentage'])/100 .' ON '.$pr['due_date'].' '. $imm.'</span></li>
                            ';}
							$message=$message.'</ul>
                            </div>
                      </div>
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="divider " style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 100%;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                          <tbody>
                        <tr style="vertical-align: top">
                              <td class="divider_inner" style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding-right: 10px;padding-left: 10px;padding-top: 10px;padding-bottom: 10px;min-width: 100%;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%"><table class="divider_content" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #BBBBBB;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                  <tbody>
                                  <tr style="vertical-align: top">
                                      <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%"><span></span></td>
                                    </tr>
                                </tbody>
                                </table></td>
                            </tr>
                      </tbody>
                        </table>
                    <div class=""> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                          <div style="color:#555555;font-family:Verdana, Geneva, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                        <div style="font-size:12px;line-height:14px;font-family:Verdana, Geneva, sans-serif;color:#555555;text-align:left;">
                              <p style="margin: 0;font-size: 12px;line-height: 14px;text-align: right"><span style="font-size: 10px; line-height: 12px; color: rgb(128, 128, 128);">Happy to help!</span></p>
                              <p style="margin: 0;font-size: 12px;line-height: 14px;text-align: right"><span style="color: rgb(128, 128, 128); font-size: 12px; line-height: 14px;">&#160;</span></p>
                              <p style="margin: 0;font-size: 12px;line-height: 14px;text-align: right"><span style="font-size: 10px; line-height: 12px; color: rgb(128, 128, 128);">Ram Kumar</span></p>
                              <p style="margin: 0;font-size: 12px;line-height: 14px;text-align: right"><span style="font-size: 10px; line-height: 12px; color: rgb(128, 128, 128);">Customer Relationship Officer</span><br>
                            <span style="font-size: 10px; line-height: 12px; color: rgb(128, 128, 128);">Andaman Trail</span><br>
                            <span style="font-size: 10px; line-height: 12px; color: rgb(128, 128, 128);">ramk@andamantrail.com</span></p>
                              <p style="margin: 0;font-size: 12px;line-height: 14px;text-align: right"><span style="font-size: 10px; line-height: 12px; color: rgb(128, 128, 128);">+91-8762711000 - Direct Line</span></p>
                            </div>
                      </div>
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="divider " style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 100%;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                          <tbody>
                        <tr style="vertical-align: top">
                              <td class="divider_inner" style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding-right: 10px;padding-left: 10px;padding-top: 10px;padding-bottom: 10px;min-width: 100%;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%"><table class="divider_content" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #BBBBBB;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                  <tbody>
                                  <tr style="vertical-align: top">
                                      <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%"><span></span></td>
                                    </tr>
                                </tbody>
                                </table></td>
                            </tr>
                      </tbody>
                        </table>
                    <div class=""> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                          <div style="color:#555555;font-family:Verdana, Geneva, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                        <div style="font-size:12px;line-height:14px;font-family:Verdana, Geneva, sans-serif;color:#555555;text-align:left;">
                              <p style="margin: 0;font-size: 12px;line-height: 14px;text-align: center"><span style="font-size: 10px; line-height: 12px; color: rgb(128, 128, 128);">Need clarifications?</span></p>
                              <p style="margin: 0;font-size: 12px;line-height: 14px;text-align: center"><span style="font-size: 10px; line-height: 12px; color: rgb(128, 128, 128);">Contact us:</span></p>
                              <p style="margin: 0;font-size: 12px;line-height: 14px;text-align: center"><span style="font-size: 10px; line-height: 12px; color: rgb(128, 128, 128);">1800-200-5100</span></p>
                              <p style="margin: 0;font-size: 12px;line-height: 14px;text-align: center"><span style="font-size: 10px; line-height: 12px; color: rgb(128, 128, 128);">Do mention your unique booking ID when you call us : IA-EST-9145</span></p>
                              <p style="margin: 0;font-size: 12px;line-height: 14px;text-align: center"><span style="font-size: 10px; line-height: 12px; color: rgb(128, 128, 128);">bookings@andamantrail.com</span></p>
                            </div>
                      </div>
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="divider " style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 100%;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                          <tbody>
                        <tr style="vertical-align: top">
                              <td class="divider_inner" style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding-right: 10px;padding-left: 10px;padding-top: 10px;padding-bottom: 10px;min-width: 100%;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%"><table class="divider_content" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #BBBBBB;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                  <tbody>
                                  <tr style="vertical-align: top">
                                      <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%"><span></span></td>
                                    </tr>
                                </tbody>
                                </table></td>
                            </tr>
                      </tbody>
                        </table>
                    <div align="center" style="padding-right: 10px; padding-left: 10px; padding-bottom: 10px;" class="">
                          <div style="line-height:10px;font-size:1px">&#160;</div>
                          <div style="display: table; max-width:225px;"> 
                        <!--[if (mso)|(IE)]><table width="205" cellpadding="0" cellspacing="0" border="0"><tr><td style="border-collapse:collapse; padding-right: 10px; padding-left: 10px; padding-bottom: 10px;"  align="center"><table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse; mso-table-lspace: 0pt;mso-table-rspace: 0pt; width:205px;"><tr><td width="32" style="width:32px; padding-right: 5px;" valign="top"><![endif]-->
                        <table align="left" border="0" cellspacing="0" cellpadding="0" width="32" height="32" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;Margin-right: 5px">
                              <tbody>
                            <tr style="vertical-align: top">
                                  <td align="left" valign="middle" style="word-break: break-word;border-collapse: collapse !important;vertical-align: top"><a href="https://www.facebook.com/" title="Facebook" target="_blank"> <img src="http://andamantrail.com/images/facebook@2x.png" alt="Facebook" title="Facebook" width="32" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: none;height: auto;float: none;max-width: 32px !important"> </a>
                                <div style="line-height:5px;font-size:1px">&#160;</div></td>
                                </tr>
                          </tbody>
                            </table>
                        <!--[if (mso)|(IE)]></td><td width="32" style="width:32px; padding-right: 5px;" valign="top"><![endif]-->
                        <table align="left" border="0" cellspacing="0" cellpadding="0" width="32" height="32" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;Margin-right: 5px">
                              <tbody>
                            <tr style="vertical-align: top">
                                  <td align="left" valign="middle" style="word-break: break-word;border-collapse: collapse !important;vertical-align: top"><a href="https://twitter.com/" title="Twitter" target="_blank"> <img src="http://andamantrail.com/images/twitter@2x.png" alt="Twitter" title="Twitter" width="32" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: none;height: auto;float: none;max-width: 32px !important"> </a>
                                <div style="line-height:5px;font-size:1px">&#160;</div></td>
                                </tr>
                          </tbody>
                            </table>
                        <!--[if (mso)|(IE)]></td><td width="32" style="width:32px; padding-right: 5px;" valign="top"><![endif]-->
                        <table align="left" border="0" cellspacing="0" cellpadding="0" width="32" height="32" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;Margin-right: 5px">
                              <tbody>
                            <tr style="vertical-align: top">
                                  <td align="left" valign="middle" style="word-break: break-word;border-collapse: collapse !important;vertical-align: top"><a href="https://www.linkedin.com/" title="LinkedIn" target="_blank"> <img src="http://andamantrail.com/images/linkedin@2x.png" alt="LinkedIn" title="LinkedIn" width="32" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: none;height: auto;float: none;max-width: 32px !important"> </a>
                                <div style="line-height:5px;font-size:1px">&#160;</div></td>
                                </tr>
                          </tbody>
                            </table>
                        <!--[if (mso)|(IE)]></td><td width="32" style="width:32px; padding-right: 5px;" valign="top"><![endif]-->
                        <table align="left" border="0" cellspacing="0" cellpadding="0" width="32" height="32" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;Margin-right: 5px">
                              <tbody>
                            <tr style="vertical-align: top">
                                  <td align="left" valign="middle" style="word-break: break-word;border-collapse: collapse !important;vertical-align: top"><a href="https://instagram.com/" title="Instagram" target="_blank"> <img src="http://andamantrail.com/images/instagram@2x.png" alt="Instagram" title="Instagram" width="32" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: none;height: auto;float: none;max-width: 32px !important"> </a>
                                <div style="line-height:5px;font-size:1px">&#160;</div></td>
                                </tr>
                          </tbody>
                            </table>
                        <!--[if (mso)|(IE)]></td><td width="32" style="width:32px; padding-right: 0;" valign="top"><![endif]-->
                        <table align="left" border="0" cellspacing="0" cellpadding="0" width="32" height="32" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;Margin-right: 0">
                              <tbody>
                            <tr style="vertical-align: top">
                                  <td align="left" valign="middle" style="word-break: break-word;border-collapse: collapse !important;vertical-align: top"><a href="https://www.blogger.com/follow-blog.g?blogID=" title="Blogger" target="_blank"> <img src="http://andamantrail.com/images/blogger@2x.png" alt="Blogger" title="Blogger" width="32" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: none;height: auto;float: none;max-width: 32px !important"> </a>
                                <div style="line-height:5px;font-size:1px">&#160;</div></td>
                                </tr>
                          </tbody>
                            </table>
                        <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]--> 
                      </div>
                        </div>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="divider " style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 100%;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                          <tbody>
                        <tr style="vertical-align: top">
                              <td class="divider_inner" style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding-right: 10px;padding-left: 10px;padding-top: 10px;padding-bottom: 10px;min-width: 100%;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%"><table class="divider_content" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #BBBBBB;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                  <tbody>
                                  <tr style="vertical-align: top">
                                      <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%"><span></span></td>
                                    </tr>
                                </tbody>
                                </table></td>
                            </tr>
                      </tbody>
                        </table>
                    <div class=""> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                          <div style="color:#555555;font-family:Verdana, Geneva, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                        <div style="font-size:12px;line-height:14px;font-family:Verdana, Geneva, sans-serif;color:#555555;text-align:left;">
                              <p style="margin: 0;font-size: 12px;line-height: 14px;text-align: center"><span style="line-height: 9px; font-size: 8px; color: rgb(128, 128, 128);">Sent by Andaman Trail ™, Plot No. 343/3, Behind Laxmi Motors, A&amp;N Islands - 744105</span></p>
                              <p style="margin: 0;font-size: 12px;line-height: 14px;text-align: center">&#160;</p>
                              <p style="margin: 0;font-size: 12px;line-height: 14px;text-align: center"><span style="line-height: 9px; font-size: 8px; color: rgb(128, 128, 128);">Andaman Trail ™ is a brand owned&#160; by Exotrail Destination Management Private Limited.</span></p>
                              <p style="margin: 0;font-size: 12px;line-height: 14px;text-align: center"><span style="line-height: 9px; font-size: 8px; color: rgb(128, 128, 128);"><br>
                                CIN:&#160;'.$dt['t0'].'</span></p>
                              <p style="margin: 0;font-size: 12px;line-height: 14px;text-align: center"><span style="line-height: 9px; font-size: 8px; color: rgb(128, 128, 128);">PAN: '.$dt['t5'].'</span></p>
                              <p style="margin: 0;font-size: 12px;line-height: 14px;text-align: center"><span style="line-height: 9px; font-size: 8px; color: rgb(128, 128, 128);">TAN: '.$dt['t6'].'</span></p>
                              <p style="margin: 0;font-size: 12px;line-height: 14px;text-align: center"><span style="color: rgb(128, 128, 128); font-size: 12px; line-height: 14px;">&#160;</span></p>
                              <p style="margin: 0;font-size: 12px;line-height: 14px;text-align: center"><span style="line-height: 9px; font-size: 8px; color: rgb(128, 128, 128);">All Rights Reserved&#160;® 2018</span></p>
                            </div>
                      </div>
                          <!--[if mso]></td></tr></table><![endif]--> 
                        </div>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="divider " style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 100%;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                          <tbody>
                        <tr style="vertical-align: top">
                              <td class="divider_inner" style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding-right: 10px;padding-left: 10px;padding-top: 10px;padding-bottom: 10px;min-width: 100%;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%"><table class="divider_content" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #BBBBBB;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                  <tbody>
                                  <tr style="vertical-align: top">
                                      <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%"><span></span></td>
                                    </tr>
                                </tbody>
                                </table></td>
                            </tr>
                      </tbody>
                        </table>
                    <div class=""> 
                          <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                          <div style="color:#555555;font-family:Arial, "Helvetica Neue", Helvetica, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                          <div style="font-size:12px;line-height:14px;color:#555555;font-family:Arial, "Helvetica Neue", Helvetica, sans-serif;text-align:left;">
                        <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: center"><span style="font-size: 10px; line-height: 12px;"><a style="color:#0068A5;text-decoration: underline;" href="http://andamantrail.com/privacy.php" target="_blank" rel="noopener">Privacy Policy</a></span> | <a style="color:#0068A5;text-decoration: underline;" href="http://andamantrail.com/tc.php" target="_blank" rel="noopener"><span style="font-size: 10px; line-height: 12px;">T &amp; Cs</span></a>&#160;| <span style="font-size: 10px; line-height: 12px;"><a style="color:#0068A5;text-decoration: underline;" href="http://andamantrail.com/refund.php" target="_blank" rel="noopener">Refund Policy</a></span></p>
                      </div>
                        </div>
                    <!--[if mso]></td></tr></table><![endif]--> 
                  </div>
                      
                      <!--[if (!mso)&(!IE)]><!--></div>
                <!--<![endif]--> 
              </div>
                </div>
            <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]--> 
          </div>
            </div>
        </div>
        
        <!--[if (mso)|(IE)]></td></tr></table><![endif]--></td>
        </tr>
  </tbody>
    </table>
<!--[if (mso)|(IE)]></div><![endif]-->

</body>
</html>
';
///echo $message;
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->Host = "mail.andamantrail.com";
$mail->Port = 587;
$mail->Username = "info@andamantrail.com";
$mail->Password = "andamantrail!@#";
$mail->SetFrom('info@andamantrail.com', 'Andamantrail');
$mail->Subject ='Andaman Trail - Estimate EST-'.$id;
$mail->addAttachment("pdf/ET-".$id.".pdf");
$mail->MsgHTML($message);
$mail->AddAddress($sf['email'], $sf['name']);

if($mail->Send()) {
  //echo "Message sent!";
} else {
 // echo "Mailer Error: " . $mail->ErrorInfo;
}
(sms($output,$sms));
(wa($output,$wa,$new_url));
 echo "<script>window.location.href = '".$admin_url."step3.php?send=true=&id=".$id."';</script>";
?>

<style>.test {
    text-align: center;
    vertical-align: middle;
    padding-top: 150px;
}</style>