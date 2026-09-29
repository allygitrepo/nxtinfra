<?php include('common/header.php');
{
  $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
  return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . 'response.php';
}
?>

<div class="breadcrumb-top bg-yellow">
  <div class="container">
    <h2>Checkout</h2>
    <ol class="breadcrumb">
      <li><a href="<?php echo $site_url?>">Home</a></li>
      <li class="active">Checkout</li>
    </ol>
    <!--breadcrumb--> 
  </div>
</div>
<style>
.ordernow {
    margin-top: 20px;
    float: right;
    padding: 10px 10px;
}
.list-group-item {
    height: 50px;
	padding:15px 15px;
}
.active h4 {
    color: #fff !important;
}
.col-lg-9.col-md-9.col-sm-9.col-xs-9.bhoechie-tab.np.nm {
    border: 1px solid #ccc;
}
.method{cursor:pointer;}
.method.active{border:2px solid #ccc;
padding:5px;}
.payment_methods {
    background: #fff;
    padding: 20px;
    height: 110px;
}
#signup_error {
    color: red;
    text-align: center;
}
.address .form-control {
    margin: 5px;
}
#login_error{color:#F00;padding:15px}
.head_inner{font-size:24px;text-align:center;font-weight:800;padding:15px;text-transform:uppercase}
.address {
    background: #F3F7F6;
	padding:20px;
}
.npnm {
    margin: 0;
    padding: 0;
}
.bhoechie-tab-content{display:none}

.bhoechie-tab-content.active {
    display: block !important;
}
div.bhoechie-tab-menu div.list-group > a.active:after {
    content: '';
    position: absolute;
    left: 100%;
    top: 50%;
    margin-top: -13px;
    border-left: 0;
    border-bottom: 13px solid transparent;
    border-top: 13px solid transparent;
    border-left: 10px solid #84C225;
}
.list-group-item.active, .list-group-item.active:focus, .list-group-item.active:hover {
    z-index: 2;
    color: #fff;
    background-color: #84C225;
    border-color: #84C225;
}
.order-summery {
    background-color: #f0f6ef;
    min-height: 224px;
    overflow: hidden;
    padding: 10px;
}
.summ-head {
    padding: 10px 0;
    text-align: center;
}
.summery-box {
    background-color: #fff;
    margin-top: 20px;
    overflow: hidden;
    padding: 10px;
    border: 1px solid #ccc;
}
.totl-rupee {
    border-bottom: 1px solid #ccc;
    overflow: hidden;
    padding-bottom: 10px;
	padding-top:10px
}
.totl-rupee .currency_icon {
    width: 10px !important;
    height: 10px;
}
.off{text-decoration:line-through;}
.table {
    width: 100%;
    margin: 0;
}
.table thead {color:#000;
}
</style>
<div class="container"> 
  <!--================= Cart Inside ====================-->
  <div class="cart-inside">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <?php if(count($_SESSION['cart'])==0){echo ' <p>Cart Is Empty!</p>';}else{
	echo '<div class="tg-minicartbody">';
?>
      <div class="check-bg">
        <div class="col-lg-9 col-md-5 col-sm-8 col-xs-9 bhoechie-tab-container np nm">
          <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 bhoechie-tab-menu npnm">
            <div class="list-group"> <a class="list-group-item active" href="#">
              <h4 class="fa fa-info-circle"></h4>
              Order Detail </a> <a class="list-group-item" href="#">
              <h4 class="fa fa-map-marker"></h4>
              Delivery Address </a> <a class="list-group-item " href="#">
              <h4 class="fa fa-inr"></h4>
              Payment</a> </div>
          </div>
            <form action="/orderprocess.php" method="post" onsubmit="return(valide())" id="payment_form">
          <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 bhoechie-tab np nm"> 
            
      
            <div class="bhoechie-tab-content active">
              <div class="item-dtl-tble">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Item</th>
                      <th>Name</th>
                      <th>Price</th>
                      <th>Quantity</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($_SESSION['cart'] as $id){
		$sum=0;		$row=fetch(query('SELECT * FROM `product`  WHERE productId="'.$id.'"'));
		?>
                    <tr id="row<?php echo $id?>">
                      <td><img width="100px" src="<?php echo $site_url?>images/<?php echo $row['image']?>" align="left"></td>
                      <td class="pro-img"><p class="itm-nm"><?php echo $row['name']?></p>
                        <p><strong>Category:</strong> <?php echo get_cat($row['category_id'])?></p>
                        <a href="<?php echo $site_url?>product/<?php echo $row['slug']?>">View Detail</a> </span></td>
                      <td><img class="currency_icon" src="/assets/images/<?php echo $_SESSION['currency']?>.png"><?php echo $row[$_SESSION['currency']];?></td>
                      <td><?php echo $_SESSION['qty'][$id];?></td>
                      <td><img class="currency_icon" src="/assets/images/<?php echo $_SESSION['currency']?>.png"><?php echo $t=$_SESSION['qty'][$id]*$row[$_SESSION['currency']];
							 $sum=$sum+$t;?></td>
                    </tr>
                    
                    <?php }?>
                  </tbody>
                </table>
              </div>
            </div>
            
            <div class="bhoechie-tab-content">
              <div class="select-ads">
                <div class="col-md-12 address">
                  <div class="">
                    <?php if(!isset($_SESSION['user_id'])){?>
                    <div id="checkout_login">
                      <p align="center" class="head_inner">Login</p>
                      <div class="col-md-5">
                        <input type="text" id="email" class="form-control" placeholder="Your Name" required="required">
                      </div>
                      <div class="col-md-5">
                        <input type="text" id="password" class="form-control" placeholder="Your E-mail" required="required">
                      </div>
                      <div class="col-md-2">
                        <button type="button" onclick="login_checkout()" name="login" class="btn btn-success">Login</button>
                      </div>
                      <div class="col-md-12" id="login_error"></div>

                      <p align="center" class="head_inner">Or Continue Without Login</p>
                      <div class="col-md-6">
                        <input  autocomplete="off" class="form-control"  type="text" placeholder="Your First name" name="fname" value="" required="required">
                      </div>
                      <div class="col-md-6">
                        <input  autocomplete="off" type="text" placeholder="Your Last name" name="lname" required="required" class="form-control" value="">
                      </div>
                      <div class="col-md-6">
                        <input  autocomplete="off" type="text" placeholder="Your Phone Number" name="phone" required="required" onchange="check_phone()" id="phone" class="form-control">
                      </div>
                      <div class="col-md-6">
                        <input  autocomplete="off" type="email" placeholder="Your e-mail" name="email" required="required" onchange="check_mail()" id="email" class="form-control">
                      </div>
                      <div class="col-md-12">
                        <textarea  required="required" name="address" class="form-control"></textarea >
                      </div>
                      <div class="col-md-6">
                        <select name="country" id="country" onchange="get_state()" required="required" class="form-control">
                          <option value="" >Select Country</option>
                          <?php $sql=query('SELECT * FROM countries ');
					while($r=fetch($sql)){					?>
                          <option value="<?php echo $r['id']?>" ><?php echo $r['name']?></option>
                          <?php }?>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <select id="state" name="state" onchange="get_city()" required="required" class="form-control">
                          <option value="" >Select</option>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <select id="city" name="city" required="required" class="form-control">
                          <option value="" >Select</option>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <input  autocomplete="off" type="text" placeholder="Pincode" name="pincode" onchange="check_pin()" required="required" id="pincode" class="form-control" value="">
                      </div>
                      <div id="signup_error"></div>
                    </div>
                    <div id="after_login"></div>
                    <?php }else{
						$lr=fetch(query('SELECT * FROM user WHERE userId="'.$_SESSION['user_id'].'"'));
						?>
                    <div class="col-md-6">
                      <input  autocomplete="off" class="form-control"  type="text" placeholder="Your First name" name="fname" value="<?php echo $lr['fname']?>" required="required">
                    </div>
                    <div class="col-md-6">
                      <input  autocomplete="off" type="text" placeholder="Your Last name" name="lname" required="required" class="form-control" value="<?php echo $lr['lname']?>">
                    </div>
                    <div class="col-md-6">
                      <input  autocomplete="off" type="text" placeholder="Your Phone Number" name="phone" required="required" onchange="check_phone()" id="phone" class="form-control" value="<?php echo $lr['phone']?>">
                    </div>
                    <div class="col-md-6">
                      <input  autocomplete="off" type="email" placeholder="Your e-mail" name="email" required="required" onchange="check_mail()" id="email" class="form-control"value="<?php echo $lr['email']?>">
                    </div>
                    <div class="col-md-12">
                      <textarea  required="required" name="address" class="form-control"><?php echo $lr['address']?></textarea >
                    </div>
                    <div class="col-md-6">
                      <select name="country" id="country" onchange="get_state()" required="required" class="form-control">
                        <option value="<?php echo $lr['country_id']?>"><?php echo get_state_name($lr['country_id'])?></option>
                        <?php $sql=query('SELECT * FROM countries ');
					while($r=fetch($sql)){					?>
                        <option value="<?php echo $r['id']?>" ><?php echo $r['name']?></option>
                        <?php }?>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <select id="state" name="state" onchange="get_city()" required="required" class="form-control">
                        <option value="<?php echo $lr['state_id']?>"><?php echo get_state_name($lr['state_id'])?></option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <select id="city" name="city" required="required" class="form-control">
                        <option value="<?php echo $lr['city_id']?>"><?php echo get_city_name($lr['city_id'])?></option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <input  autocomplete="off" type="text" placeholder="Pincode" name="pincode" onchange="check_pin()" required="required" id="pincode" class="form-control" value="<?php echo $lr['pincode']?>">
                    </div>
                    <div id="signup_error"></div>
                    <?php }?>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="bhoechie-tab-content ">
              <div class="select-ads">
                <div class="col-md-12 address ">
                  <div class="payment_methods">
                    <div class="col-md-4"><img onclick="payment_method('cod')" id="cod" class="method" src="/images/cod.png" /></div>
                    <div class="col-md-4"><img onclick="payment_method('payumoney')" id="payumoney" class="method"  src="/images/payumoney.png" /></div>
                    <div class="col-md-4"><img onclick="payment_method('razorpay')" id="razorpay" class="method"  src="/images/razorpay.png" /></div>
                  </div>
                  <div>
                   <button type="submit" class="btn btn-primary mt-4 float-right" onclick="launchBOLT(); return false;">Pay Now</button>
                    <button type="submit" class="custom-btn green ordernow">Order Now</button>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" value="0" id="allerror" />
            <input type="hidden" value="0" name="payment_methods" id="payment_methods" />
            <input type="hidden" value="<?php echo $sum;?>" name="total_amount" />
            <input type="hidden" value="50" name="delivery_charges" />
            <input type="hidden" value="<?php echo $sum+50;?>" name="total" />
            
            <input type="hidden" id="udf5" name="udf5" value="BOLT_KIT_PHP7" />
            <input type="hidden" id="surl" name="surl" value="<?php echo getCallbackUrl(); ?>" />
            <input type="hidden" id="key" name="key" placeholder="Merchant Key" value="<?php print MERCHANT_KEY;?>" />
            <input type="hidden" id="salt" name="salt" placeholder="Merchant Salt" value="<?php print SALT; ?>" />
            <input type="hidden" id="txnid" name="txnid" placeholder="Transaction ID" value="<?php echo  "Barna-" . uniqid(rand(10000,99999999))?>" />
             <input type="email" class="form-control" id="amount" name="amount" placeholder="Amount" value="<?php echo $sum+50;?>">
          </div>
            <input type="hidden" id="hash" name="hash" placeholder="Hash" value="" />

          </form>
        </div>
      </div>
      <div class="col-md-3 npnm" style="padding:0">
        <div class="order-summery">
          <div class="summ-head">Order Summery</div>
          <div class="summery-box">
            <div class="totl-rupee">
              <div class="col-md-7">Amount</div>
              <div class="col-md-5"><img class="currency_icon" src="/assets/images/<?php echo $_SESSION['currency']?>.png">
                <?php echo $sum; ?>
              </div>
            </div>
            
            <div class="totl-rupee">
            
              <div class="col-md-7">Delivery Charges</div>
              <div class="col-md-5"><img class="currency_icon" src="/assets/images/<?php echo $_SESSION['currency']?>.png">
               50
              </div>
             </div>
             <div class="totl-rupee">
              <div class="col-md-7 net-ammount">Amount to pay</div>
          <div class="col-md-5 net-ammount"><img class="currency_icon" src="/assets/images/<?php echo $_SESSION['currency']?>.png">
           <?php echo $sum+50; ?></div>
            </div>
          </div>
          </div>
          
          </div>
          
       <?php }?>    
        </div>
        
      </div>
       <div class="bottom-table"> <a href="<?php echo $site_url;?>shop" class="custom-btn">Back to shop</a> </div>
    </div>

   
  </div>
 
</div>
</div>
<!--bottom-table-->
</div>
<!--================= End of Cart Inside ====================-->
</div>
           <div id="paybtndiv"></div>

<?php include('common/footer.php')?>
<script>
function valide(){
	var pm=$('#payment_methods').val();
	if(pm=='0'){alert('Please Select payment menthod');return false;}
	}
function payment_method(id){
	$('.method').removeClass('active');
	$('#'+id).addClass('active');
	$('#payment_methods').val(id);
	}
$(document).ready(function() {
    $("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
        e.preventDefault();
        $(this).siblings('a.active').removeClass("active");
        $(this).addClass("active");
        var index = $(this).index();
        $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
        $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
    });
	 });
function login_checkout(){
	var email=$('#email').val();
	var password=$('#password').val();
	if(email==''){$('#login_error').html('Please entre email address');return false;}
	if(password==''){$('#login_error').html('Please entre password');return false;}
	var dataString = 'action=login_checkout&email='+email+'&password='+password;
	  $.ajax({
             type: "post",
			 url: "<?php echo $site_url?>ajax/addtocart.php",
			 data: dataString,
			 success: function(data){
				if(data==0){$('#login_error').html('Your email or password is incorrect. please try again');}
				else{$('#after_login').html(data);$('#checkout_login').remove()}
				 } 	
			});
	}	 	
	
function savedata(){
	var add1 =$('#add1').val();
    if(add1==''){$('#error').hmtl('Please fill address');return false;}
	var add2 =$('#add2').val();
	var add3 =$('#add3').val();
	add=add1+' '+add2+' '+add3 ;
    $('#address').html(add);
	$('#new_adrs').modal('hide');
 	}	
	
function ordernow(type){
	var  end_time=$('#latertime').val();
	var laterdate =$('#laterdate').val();
	var delivery_charges =$('#delivery_charges').val();
	var total_rent_amount =$('#total_rent_amount').val();
	var total_deposit =$('#total_deposit').val();
	var total =$('#total').val();
	if(end_time==''){$('.carterror').html('Please Delivery Slot');return false;}else{$('.carterror').html('');}
	if(laterdate==''){$('.carterror').html('Please Delivery Date');return false;}else{$('.carterror').html('');}
	$('#minterror').html('');
	$('#carterror').html('');
	var dataString = 'action=ordernow&end_time='+end_time+'&dates='+laterdate+'&delivery_charges='+delivery_charges+'&total_rent_amount='+total_rent_amount+'&total_deposit='+total_deposit+'&total='+total+'&type='+type;
	//$('#cod').html('<img src="<?php echo $site_url?>loader.gif" />');
	//return false
	$.ajax({
             type: "post",
			 url: "<?php echo $site_url?>ajax/ordernow.php",
			 data: dataString,
			 success: function(response){
				 if(response==0){
				alert('somthing went wrong');
				window.location.href = 'checkout.php';}
				else{window.location.href = '<?php echo $site_url?>thankyou'}
				 }  	
			});
	}	

function check_pin(){
	var pincode =$('#pincode').val();
	var dataString = 'action=check_pin&pincode='+pincode;
 	$('#signup_error').html('')
		$.ajax({
                type: "POST",
                url : "<?php echo $site_url;?>ajax/logic.php",
                data: dataString,
                cache: false,
                success: function (data) { 
               		$('#signup_error').html(data);
					$('#allerror').val(1);
					$('#pincode').focus();
 				  },
            });
	}		

function get_state()
    {
		var state=$('#country').val();
        var dataString = 'action=get_state&id='+state;
		$('#city_id').html('')
        $.ajax({
                type: "POST",
                url : "<?php echo $site_url;?>ajax/logic.php",
                data: dataString,
                cache: false,
                success: function (data) { 
                     $('#state').html(data); 
                },
                error: function(err) {
                    console.log(err);
                }
            });  
    }
		
function get_city()
    {
		var state=$('#state').val();
        var dataString = 'action=get_city&id='+state;
		$('#city_id').html('')
        $.ajax({
                type: "POST",
                url : "<?php echo $site_url;?>ajax/logic.php",
                data: dataString,
                cache: false,
                success: function (data) { 
                     $('#city').html(data); 
                },
                error: function(err) {
                    console.log(err);
                }
            });  
    }	
	
		
</script>
<script>
function paynowroz(){
	 var amount=$('#amount').val();
	 var fname=$('#fname').val();
	 var dataString = 'checkout=manual&page=facebook'+'&amount='+amount+'&fname='+hit;
	  $.ajax({
                    type: "post",
 					url: "/roz/pay.php?checkout=manual",
					data: dataString,
					success: function(response){
					$('#paybtndiv').html(response);
					$('#rzp-button1').click();
					}                   
			});
	}
</script>
<script id="bolt" src="https://sboxcheckout-static.citruspay.com/bolt/run/bolt.min.js" bolt-
color="e34524" bolt-logo="http://boltiswatching.com/wp-content/uploads/2015/09/Bolt-Logo-e14421724859591.png"></script>
<script type="text/javascript"><!--
  $('#payment_form').bind('keyup blur', function(){
    $.ajax({
      url: '/request.php',
      type: 'post',
      data: JSON.stringify({ 
        key: $('#key').val(),
        salt: $('#salt').val(),
        txnid: $('#txnid').val(),
        amount: $('#amount').val(),
        pinfo: $('#pinfo').val(),
        fname: $('#fname').val(),
        email: $('#email').val(),
        mobile: $('#mobile').val(),
        udf5: $('#udf5').val()
      }),
      contentType: "application/json",
      dataType: 'json',
      success: function(json) {
      if (json['error']) {
        $('#alertinfo').html('<i class="fa fa-info-circle"></i>'+json['error']);
      }
      else if (json['success']) { 
        $('#hash').val(json['success']);
      }
      }
    }); 
});
//-->
</script>
<script type="text/javascript"><!--
  function launchBOLT() {
    bolt.launch({
      key: $('#key').val(),
      salt: $('#salt').val(),
      txnid: $('#txnid').val(), 
      hash: $('#hash').val(),
      amount: $('#amount').val(),
      firstname: $('#fname').val(),
      email: $('#email').val(),
      phone: $('#mobile').val(),
      productinfo: $('#pinfo').val(),
      udf5: $('#udf5').val(),
      surl : $('#surl').val(),
      furl: $('#surl').val(),
      mode: 'dropout' 
    },
    { 
      responseHandler: function(BOLT){
      console.log( BOLT.response.txnStatus );   
    if(BOLT.response.txnStatus != 'CANCEL') {
      //Salt is passd here for demo purpose only. For practical use keep salt at server side only.
      var fr = '<form action=\"'+$('#surl').val()+'\" method=\"post\">' +
      '<input type=\"hidden\" name=\"key\" value=\"'+BOLT.response.key+'\" />' +
      '<input type=\"hidden\" name=\"salt\" value=\"'+$('#salt').val()+'\" />' +
      '<input type=\"hidden\" name=\"txnid\" value=\"'+BOLT.response.txnid+'\" />' +
      '<input type=\"hidden\" name=\"amount\" value=\"'+BOLT.response.amount+'\" />' +
      '<input type=\"hidden\" name=\"productinfo\" value=\"'+BOLT.response.productinfo+'\" />' +
      '<input type=\"hidden\" name=\"firstname\" value=\"'+BOLT.response.firstname+'\" />' +
      '<input type=\"hidden\" name=\"email\" value=\"'+BOLT.response.email+'\" />' +
      '<input type=\"hidden\" name=\"udf5\" value=\"'+BOLT.response.udf5+'\" />' +
      '<input type=\"hidden\" name=\"mihpayid\" value=\"'+BOLT.response.mihpayid+'\" />' +
      '<input type=\"hidden\" name=\"status\" value=\"'+BOLT.response.status+'\" />' +
      '<input type=\"hidden\" name=\"hash\" value=\"'+BOLT.response.hash+'\" />' +
      '</form>';
      var form = jQuery(fr);
      jQuery('body').append(form);                
      form.submit();
      }
    },
      catchException: function(BOLT){
      alert( BOLT.message );
    }
    });
  }
//--
</script> 

