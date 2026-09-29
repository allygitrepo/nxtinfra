<?php include(APPPATH.'views/admin/layout/Header.php'); ?>

<div class="page-wrapper">
  <div class="page-content"> 
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Configrations</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a> </li>
            <li class="breadcrumb-item active" aria-current="page">Edit Company</li>
          </ol>
        </nav>
      </div>
      <div class="ms-auto"> <a type="button" href="<?= site_url('configrations/company'); ?>" class="btn btn-primary"><i class="fa fa-arrow-right"></i>Go to list</a> </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">Edit Company</h6>
    <div class="error">
      <?php if($this->session->flashdata('this_error')) {?>
      <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
        <div class="text-white">
          <?= $this->session->flashdata('this_error'); ?>
        </div>
      </div>
      <?php }?>
    </div>
    <?php include APPPATH.'views/admin/layout/alerts.php'; ?>
    <hr/>
    <div class="card">
      <div class="card-body">
        <div class="">
          <form class="row g-3" action="<?= site_url('configrations/company_create'); ?>" method="post" enctype="multipart/form-data">
            <div class="col-md-6">
              <label for="inputEmail" class="form-label">Company  Name <span class="rdot">*</span></label>
              <input type="text" name="comp_name" class="form-control" required="required" >
            </div>
            <div class="col-md-6">
              <label for="inputEmail" class="form-label">Company Short Code <span class="rdot">*</span></label>
              <input type="text" name="comp_code" class="form-control" required="required" >
            </div>
            <div class="col-md-4">
              <label for="inputFirstName" class="form-label">Vertical Type</label>
              <select class="form-select" name="comp_vertical" id="master_select" required="true">
                <option value=""> Select </option>
                <?php foreach($vertical as  $v){?>
                <option  value="<?php echo $v['id']?>"><?php echo $v['master_name']?></option>
                <?php }?>
              </select>
            </div>
            <div class="col-md-2">
              <button class="btn btn-info btn-sm add-new-btn" style="color:#FFF"  data-bs-toggle="modal" type="button" data-bs-target="#addnewmaster" onclick="newmaster(4)">Add new</button>
            </div>
            <div class="col-md-6">
              <label for="inputLastName" class="form-label">Budget Control</label>
              <br />
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" checked="checked" name="budget_control_gst" id="inlineRadio1" value="Y">
                <label class="form-check-label" for="inlineRadio1">With GST</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="budget_control_gst" id="inlineRadio1" value="N">
                <label class="form-check-label" for="inlineRadio1">Without GST </label>
              </div>
            </div>
            <h6 class="mb-0 text-uppercase">Corporate / Registered Office Address</h6>
            <div class="col-md-3">
              <label class="form-label">Address Line 1 </label>
              <input type="text" name="comp_addr1" class="form-control" >
            </div>
            <div class="col-3">
              <label for="inputAddress" class="form-label">Address Line 2</label>
              <input type="text" name="comp_addr2" class="form-control" >
            </div>
            <div class="col-md-3">
              <label class="form-label">Address Line 3 </label>
              <input type="text" name="comp_addr3" class="form-control" >
            </div>
            <div class="col-3">
              <label for="inputAddress" class="form-label">Mobile.No.</label>
              <input type="text" name="comp_mobile" class="form-control" >
            </div>
            <div class="col-3">
              <label for="inputAddress" class="form-label">Office No.</label>
              <input type="text" name="comp_office" class="form-control" >
            </div>
            <div class="col-3">
              <label for="inputAddress" class="form-label">Email Id</label>
              <input type="email" name="comp_email" class="form-control" >
            </div>
            <div class="col-3">
              <label for="inputAddress" class="form-label">Pincode</label>
              <input type="text" name="comp_pincode" class="form-control" >
            </div>
            <div class="col-3">
              <label for="inputAddress" class="form-label">City</label>
              <input type="text" name="comp_city" class="form-control" >
            </div>
            <h6 class="mb-0 text-uppercase">Billing Address</h6>
            <div class="col-md-3">
              <label class="form-label">Address Line 1 </label>
              <input type="text" name="comp_register_address1" class="form-control" >
            </div>
            <div class="col-3">
              <label for="inputAddress" class="form-label">Address Line 2</label>
              <input type="text" name="comp_register_address2" class="form-control" >
            </div>
            <div class="col-md-3">
              <label class="form-label">Address Line 3 </label>
              <input type="text" name="comp_register_address3" class="form-control" >
            </div>
            <div class="col-3">
              <label for="inputAddress" class="form-label">Pincode</label>
              <input type="text" name="comp_register_pincode" class="form-control" >
            </div>
            <div class="col-3">
              <label for="inputAddress" class="form-label">State</label>
              <input type="text" name="comp_state" class="form-control" >
            </div>
            <div class="col-3">
              <label for="inputAddress" class="form-label">WebSite URL</label>
              <input type="text" name="comp_website" class="form-control" >
            </div>
            <div class="col-3">
              <label for="inputAddress" class="form-label">CIN No.</label>
              <input type="text" name="comp_cin_no" class="form-control" >
            </div>
            <div class="col-3">
              <label for="inputAddress" class="form-label">GST No.</label>
              <input type="text" name="comp_gst_no" class="form-control" >
            </div>
            <div class="col-3">
              <label for="inputAddress" class="form-label">PAN No.</label>
              <input type="text" name="comp_pan_no" class="form-control" >
            </div>
            <div class="col-3">
              <label for="inputAddress" class="form-label">Account Year Start <span class="rdot">*</span> </label>
              <input type="text" name="comp_start_date" class="form-control date"  required="required">
            </div>
            <div class="col-3">
              <label for="inputAddress" class="form-label">Account Year End <span class="rdot">*</span></label>
              <input type="text" name="comp_end_date" class="form-control date"  required="required">
            </div>
            <div class="col-12">
              <div class="file-drop-area"> <span class="fake-btn">Choose files</span> <span class="file-msg">or drag and drop files here</span>
                <input class="file-input" type="file" name="logo">
              </div>
              <div class="col-12">
                <label for="inputAddress" class="form-label">Slogan <span class="rdot">*</span></label>
                <input type="text" name="comp_slogan" class="form-control" >
              </div>
            </div>
            
            <div class="col-12">
              <label for="inputAddress" class="form-label">Special Terms & Conditions(For PO) <span class="rdot">*</span> </label>
              <textarea id="editor1" name="general_terms"></textarea>
            </div>
            <div class="col-12">
              <label for="inputAddress" class="form-label">General Terms <span class="rdot">*</span> </label>
              <textarea id="editor2" name="header_terms"></textarea>
            </div>
            <div class="col-12">
              <button type="submit" class="btn pull-right btn-primary px-5">Add</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="addnewmaster" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Master</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form class="row g-3" action="<?= site_url('master/create_master'); ?>" method="post">
        <div class="modal-body">
          
          <div class="row mb-4 mt-4">
            <label class="col-md-4 control-label">Master Name</label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="add_master_name" name="master_name" placeholder="" value="" required="required">
              <input type="hidden" value="" name="add_master_id" id="add_master_id" />            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" onclick="save_master()" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include(APPPATH.'views/admin/layout/master-bottom.php'); ?>
<script>
	
function newmaster(id){
	$('#add_master_id').val(id);
	}
function save_master(){
    var name= encodeURIComponent($('#add_master_name').val());
	var master_id= encodeURIComponent($('#add_master_id').val());
	if(name==''){Swal.fire('Oops...','Please entre the name ','error'); return false;}
    var dataString = 'action=save_master&name='+name+'&master_id='+master_id;
		 $.ajax({
              type: "POST",
              url: "<?php echo site_url('master/ajax_save_master')?>",             
			  data: dataString,
              cache: false,
                success: function (data) {
					if(data==0){Swal.fire('Oops...','Duplicate Master not allowed','error');}
					else{ 
					$('#addnewmaster').modal('hide');
					Swal.fire('Good job!','Master Added','success');
					$('#master_select').append('<option value="'+data+'"selected="selected" >'+name+'</option>')
				   }
                },
            });
		}
	
function update(){
    var edit_name= ($('#edit_name').val());
	var edid_id= ($('#edid_id').val());
	var mt=  $("#mt").val();
	
    var dataString = 'action=ajax_update_master&edit_name='+encodeURIComponent(edit_name)+'&edid_id='+edid_id+'&mt='+mt;
		 $.ajax({
              type: "POST",
              url: "<?php echo site_url('master/ajax_update_master')?>",             
			  data: dataString,
              cache: false,
                success: function (data) {
					if(data==0){Swal.fire('Oops...','Duplicate Master not allowed','error');}
					else{table.ajax.reload(); 
					$('#editmodel').modal('hide');
					Swal.fire('Good job!','Master Updated','success');
				   }
                },
            });
		}
	

</script> 
