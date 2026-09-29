<?php  $where['username'] = 'admin';
$admin = $this->MainModel->get_row('admin',$where);     
?>

<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="<?= site_url('assets/images/favicon-32x32.png'); ?>" type="image/png" />
	<!--plugins-->
	<link href="<?= site_url('assets/plugins/simplebar/css/simplebar.css'); ?>" rel="stylesheet" />
	<link href="<?= site_url('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css'); ?>" rel="stylesheet" />
	<link href="<?= site_url('assets/plugins/metismenu/css/metisMenu.min.css'); ?>" rel="stylesheet" />
	<!-- loader-->
	<link href="<?= site_url('assets/css/pace.min.css'); ?>" rel="stylesheet" />
	<script src="<?= site_url('assets/js/pace.min.js'); ?>"></script>
	<!-- Bootstrap CSS -->
	<link href="<?= site_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="<?= site_url('assets/css/app.css'); ?>" rel="stylesheet">
	<link href="<?= site_url('assets/css/icons.css'); ?>" rel="stylesheet">
	<title>P2P– Login</title>
</head>

<body>
	<!--wrapper-->
	<div class="wrapper">
		<div class="authentication-header"></div>
		<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
			<div class="container-fluid">
				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
					<div class="col mx-auto">
						<div class="mb-4 text-center">
							
						</div>
						<div class="card">
							<div class="card-body">
								<div class="p-4 rounded">
									<div class="text-center">
										<img src="https://www.sekura.in/images/sekura-logo.png" width="180" alt="" />
									</div>
									
									<div class="login-separater text-center mb-4"> <span>SIGN IN WITH EMAIL</span>
										<hr/>
									</div>
									<div class="form-body">
										 <?php if($this->session->flashdata('login_failed')): ?>
      <div class="alert alert-danger">
        <?= $this->session->flashdata('login_failed'); ?>
      </div>
      <?php endif; ?>
      <?= form_error('username'); ?>
      <?= form_error('password'); ?>
                                         <?= form_open('Authentication',['class'=>'form-horizontal m-t-20 row g-3','id'=>'loginform']); ?>
											<div class="col-12">
												<label for="inputEmailAddress" class="form-label">Email Address</label>
												
                                                <?= form_input(['name'=>'username','class'=>'form-control','value'=>set_value('username'),'placeholder'=>'Enter Username','readonly'=>'true','required'=>'required','onfocus'=>"this.removeAttribute('readonly')"]); ?>
                                                
											</div>
											<div class="col-12">
												<label for="inputChoosePassword" class="form-label">Enter Password</label>
												<div class="input-group" id="show_hide_password">
													
                                                       <?= form_password(['name'=>'password','class'=>'form-control border-end-0','value'=>set_value('password'),'placeholder'=>'Enter Password','readonly'=>'true','onfocus'=>"this.removeAttribute('readonly')"]); ?>
                                                       
                                                     <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-check form-switch">
													<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
													<label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>
												</div>
											</div>
											<!--<div class="col-md-6 text-end">	<a href="authentication-forgot-password.html">Forgot Password ?</a>
											</div>-->
											<div class="col-12">
												<div class="d-grid">
													
                                                      <?= form_submit(['class'=>'btn btn-primary','value'=>'Login','name'=>'login']); ?>
                                                      
												</div>
											</div>
										 <?= form_close(); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
			</div>
		</div>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="<?= site_url('assets/css/icons.css'); ?>assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="<?= site_url('assets/js/jquery.min.js'); ?>"></script>
	<script src="<?= site_url('assets/plugins/simplebar/js/simplebar.min.js'); ?>"></script>
	<script src="<?= site_url('assets/plugins/metismenu/js/metisMenu.min.js'); ?>"></script>
	<script src="<?= site_url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js'); ?>"></script>
	<!--Password show & hide js -->
	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});
	</script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>
</body>

</html>