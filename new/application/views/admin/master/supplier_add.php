<?php include(APPPATH.'views/admin/layout/Header.php'); ?>

<div class="page-wrapper">
  <div class="page-content"> 
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Master</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a> </li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $page_name?></li>
          </ol>
        </nav>
      </div>
      <div class="ms-auto"> <a type="button" href="<?= site_url('master/supplier'); ?>" class="btn btn-primary"><i class="fa fa-arrow-right"></i>Go to list</a> </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase"><?php echo $page_name?></h6>
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
          <form class="row g-3" action="<?php if(isset($supplier)){echo  site_url('master/supplier_update');}else{echo site_url('master/supplier_create');}; ?>" method="post" enctype="multipart/form-data">
            <div class="col-md-4">
              <label for="inputFirstName" class="form-label">Type *</label>
              <select class="form-select" name="party_type" id="party_type" required="true">
                <option value=""> Select </option>
                <?php foreach($type as $t){?>
                <option <?php if(isset($supplier) && $supplier[0]['party_type']==$t['id']){echo 'selected="selected"';}?> value="<?php echo $t['id']?>"> <?php echo $t['master_name']?> </option>
                <?php  }?>
              </select>
              <?php if(isset($supplier)){?>
              <input type="hidden" name="id" value="<?php echo $supplier[0]['id']?>" />
              <?php }?>
            </div>
            <div class="col-md-2">
              <button class="btn btn-info btn-sm add-new-btn" style="color:#FFF;margin-top:30px"  data-bs-toggle="modal" type="button" data-bs-target="#addnewmaster" onclick="newmaster(8,'party_type')">Add new</button>
            </div>
            <div class="col-md-4">
              <label for="inputLastName" class="form-label">Supplier Category</label>
              <select class="form-select" name="party_category" id="party_category" required="true">
                <option value=""> Select </option>
                <?php foreach($category as $c){?>
                <option <?php if(isset($supplier) && $supplier[0]['party_category']==$c['id']){echo 'selected="selected"';}?> value="<?php echo $c['id']?>"> <?php echo $c['master_name']?> </option>
                <?php  }?>
              </select>
            </div>
            <div class="col-md-2">
              <button class="btn btn-info btn-sm add-new-btn" style="color:#FFF;margin-top:30px"  data-bs-toggle="modal" type="button" data-bs-target="#addnewmaster" onclick="newmaster(10,'party_category')">Add new</button>
            </div>
            <div class="col-md-6">
              <label for="inputEmail" class="form-label">Supplier Name</label>
              <input type="text" name="party_name" <?php if(isset($supplier)){echo 'value="'.$supplier[0]['party_name'].'"';}?> class="form-control" required="required" >
            </div>
            <div class="col-md-6">
              <label for="inputPassword" class="form-label">Tax Category </label>
              <br />
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tax_category" id="inlineRadio1" value="R" <?php if(isset($supplier) && $supplier
				[0]['tax_category']=='R'){echo 'checked="checked';}?><?php if(!isset($supplier)){echo 'checked="checked"';}?>>
                <label class="form-check-label" for="inlineRadio1">Registered </label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tax_category" id="inlineRadio1" value="U" <?php if(isset($supplier) && $supplier[0]['tax_category']=='U'){echo 'checked="checked';}?>>
                <label class="form-check-label" for="inlineRadio1">Unregistered </label>
              </div>
            </div>
            <div class="col-md-6">
              <label for="inputPassword" class="form-label">Tally Account Name </label>
              <input type="text" name="tally_account_name" <?php if(isset($supplier)){echo 'value="'.$supplier[0]['tally_account_name'].'"';}?> class="form-control" id="inputCity">
            </div>
            <div class="col-md-6">
              <label for="inputAddress" class="form-label">Contact Person Name</label>
              <input type="text" name="party_contact_person_name" <?php if(isset($supplier)){echo 'value="'.$supplier[0]['party_contact_person_name'].'"';}?> class="form-control" id="inputCity">
            </div>
            <div class="col-md-6">
              <label for="inputAddress2" class="form-label">Designation</label>
              <input type="text" name="party_designation" class="form-control" <?php if(isset($supplier)){echo 'value="'.$supplier[0]['party_designation'].'"';}?> id="inputCity">
              <label for="inputCity" class="form-label">Email</label>
              <input type="email" class="form-control" name="party_email" <?php if(isset($supplier)){echo 'value="'.$supplier[0]['party_email'].'"';}?> id="inputCity">
            </div>
            <div class="col-md-6">
              <label for="inputState" class="form-label">Address</label>
              <textarea name="party_address_1" rows="4" class="form-control"><?php if(isset($supplier)){echo $supplier[0]['party_address_1'];}?></textarea>
            </div>
            <div class="col-md-4">
              <label for="inputZip" class="form-label">Mobile-1</label>
              <input type="text" class="form-control" name="party_mobile" <?php if(isset($supplier)){echo 'value="'.$supplier[0]['party_mobile'].'"';}?>>
            </div>
            <div class="col-md-4">
              <label for="inputZip" class="form-label">Mobile-2</label>
              <input type="text" class="form-control" name="party_mobile2" <?php if(isset($supplier)){echo 'value="'.$supplier[0]['party_mobile1'].'"';}?>>
            </div>
            <div class="col-md-4">
              <label for="inputZip" class="form-label">Mobile-3</label>
              <input type="text" class="form-control" name="party_mobile3" <?php if(isset($supplier)){echo 'value="'.$supplier[0]['party_mobile2'].'"';}?>>
            </div>
            <div class="col-md-4">
              <label for="inputZip" class="control-label">State</label>
              <select class="form-select" name="party_state" id="party_state" onchange="getcity(this.value)">
                <option value=""> Select </option>
                <?php foreach($states as $s){?>
                <option <?php if(isset($supplier) && $supplier[0]['party_state']==$s['id']){echo 'selected="selected"';}?> value="<?php echo $s['id']?>"> <?php echo $s['state_name']?> </option>
                <?php  }?>
              </select>
            </div>
            
            <div class="col-md-4">
              <label class="control-label">City</label>
              
              <select class="form-select" name="party_city" id="party_city" required="true">
              <?php if(isset($supplier)){
				  echo ' <option value="'.$supplier[0]['party_city'].'">'.GetForeignKey('cities','id',$supplier[0]['party_city'],'city_name').'</option>';
				  ?>
              <?php }else{echo '<option value=""> Select </option>'; }?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="control-label">Pincode</label>
              <input type="text" class="form-control" id="party_pincode" name="party_pincode" placeholder=""  <?php if(isset($supplier)){echo 'value="'.$supplier[0]['party_pincode'].'"';}?>>
            </div>
            <div class="col-md-4">
              <label class="control-label">Phone</label>
              <input type="text" class="form-control" id="party_phone" name="party_phone" placeholder=""  <?php if(isset($supplier)){echo 'value="'.$supplier[0]['party_phone'].'"';}?>>
            </div>
            <div class="col-md-4">
              <label class="control-label">Phone-2</label>
              <input type="text" class="form-control" id="party_phone1" name="party_phone1" placeholder=""  <?php if(isset($supplier)){echo 'value="'.$supplier[0]['party_phone1'].'"';}?>>
            </div>
            <div class="col-md-4">
              <label class="control-label">Phone-3</label>
              <input type="text" class="form-control" id="party_phone2" name="party_phone2" placeholder=""  <?php if(isset($supplier)){echo 'value="'.$supplier[0]['party_phone2'].'"';}?>>
            </div>
            <div class="col-md-4">
              <label class="control-label">Area</label>
              <input type="text" class="form-control" id="party_area" name="party_area" placeholder="" <?php if(isset($supplier)){echo 'value="'.$supplier[0]['party_area'].'"';}?>>
            </div>
            <div class="col-md-4">
              <label class="control-label">Country</label>
              <input type="text" class="form-control" id="party_country" name="party_country" placeholder=""  <?php if(isset($supplier)){echo 'value="'.$supplier[0]['party_country'].'"';}?>>
            </div>
            <div class="col-md-4">
              <label class="control-label">Website</label>
              <input type="text" class="form-control" id="party_websites" name="party_websites" placeholder="" <?php if(isset($supplier)){echo 'value="'.$supplier[0]['party_websites'].'"';}?>>
            </div>
            <div class="col-md-4">
              <label class=" control-label">GST Number</label>
              <input type="text" class="form-control" id="party_gst_number" name="party_gst_number" placeholder=""  <?php if(isset($supplier)){echo 'value="'.$supplier[0]['party_gst_number'].'"';}?>>
            </div>
            <div class="col-md-4">
              <label class=" control-label">PAN Number</label>
              <input type="text" class="form-control" id="party_pan_number" name="party_pan_number" placeholder=""  <?php if(isset($supplier)){echo 'value="'.$supplier[0]['party_pan_number'].'"';}?>>
            </div>
            <div class="col-md-4">
              <label class=" control-label">MSME Number</label>
              <input type="text" class="form-control" id="party_msme_number" name="party_msme_number" placeholder=""  <?php if(isset($supplier)){echo 'value="'.$supplier[0]['party_msme_number'].'"';}?>>
            </div>
            <h6 class="mb-0 text-uppercase">Bank Details</h6>
            <div class="col-md-6">
              <label class="control-label">Beneficiary Name</label>
              <input type="text" class="form-control" id="party_beneficiary_name" name="party_beneficiary_name"  <?php if(isset($supplier)){echo 'value="'.$supplier[0]['party_beneficiary_name'].'"';}?>>
            </div>
            <div class="col-md-6">
              <label class="control-label">Bank Name</label>
              <input type="text" class="form-control" id="party_bank_name" name="party_bank_name" placeholder=""  <?php if(isset($supplier)){echo 'value="'.$supplier[0]['party_bank_name'].'"';}?>>
            </div>
            <div class="col-md-6">
              <label class="control-label">Account Type </label>
              <select class="form-select" name="party_bank_account_type" id="party_bank_account_type">
                <option value=""> Select </option>
                <option <?php if(isset($supplier) && $supplier[0]['party_bank_account_type']=='Saving'){echo 'selected="selected"';}?> value="Saving"> Saving</option>
                <option <?php if(isset($supplier) && $supplier[0]['party_bank_account_type']=='Current'){echo 'selected="selected"';}?> value="Current"> Current</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="control-label">Bank Address </label>
              <input type="text" class="form-control" id="party_bank_address" name="party_bank_address" placeholder="" <?php if(isset($supplier)){echo 'value="'.$supplier[0]['party_bank_address'].'"';}?>>
            </div>
            <div class="col-md-6">
              <label class="control-label">Account Number</label>
              <input type="text" class="form-control" id="party_bank_account_no" name="party_bank_account_no" placeholder="" <?php if(isset($supplier)){echo 'value="'.$supplier[0]['party_bank_account_no'].'"';}?>>
            </div>
            <div class="col-md-6">
              <label class="control-label">Account IFSC Code</label>
              <input type="text" class="form-control" id="party_bank_ifsc_code" name="party_bank_ifsc_code" placeholder="" <?php if(isset($supplier)){echo 'value="'.$supplier[0]['party_bank_ifsc_code'].'"';}?>>
            </div>
            <div class="col-12">
              <button type="submit" class="btn pull-right btn-primary px-5"><?php echo $button_name?></button>
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
              <input type="hidden" value="" name="add_master_id" id="add_master_id" />
              <input type="hidden" value="" name="target" id="target" />
              
            </div>
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
	
function newmaster(id,target){
	$('#add_master_id').val(id);
	$('#target').val(target);
	}
function save_master(){
    var name= encodeURIComponent($('#add_master_name').val());
	var master_id= encodeURIComponent($('#add_master_id').val());
	var target= ($('#target').val());
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
					$('#'+target).append('<option value="'+data+'"selected="selected" >'+name+'</option>')
				   }
                },
            });
		}
	
	
function getcity(id){

    var dataString = 'action=getcity&id='+encodeURIComponent(id);
		 $.ajax({
              type: "POST",
              url: "<?php echo site_url('master/ajax_getcity')?>",             
			  data: dataString,
              cache: false,
                success: function (data) {
					$('#party_city').html(data)
					},
            });
		}
	

</script> 
