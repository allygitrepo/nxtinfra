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
            <li class="breadcrumb-item active" aria-current="page"><?php echo $page_name?></li>
          </ol>
        </nav>
      </div>
      <div class="ms-auto"> <a type="button" href="<?= site_url('configrations/location'); ?>" class="btn btn-primary"><i class="fa fa-arrow-right"></i>Go to list</a> </div>
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
          <form class="row g-3" action="<?php if(isset($row)){echo  site_url('configrations/location_update');}else{echo site_url('configrations/location_create');}; ?>" method="post" enctype="multipart/form-data">
            <div class="col-md-6">
              <label for="inputEmail" class="form-label">Location Name <span class="rdot">*</span></label>
              <input type="text" name="loc_name" <?php if(isset($row)){echo 'value="'.$row[0]['loc_name'].'"';}?> class="form-control" required="required" >
            </div>
            <div class="col-md-6">
              <label for="inputFirstName" class="form-label">Under Company Name</label>
              <select class="form-select" name="loc_comp_id" id="master_select" required="true">
                <option value=""> Select </option>
                <?php foreach($company as  $c){?>
                <option  <?php if(isset($row) && $row[0]['loc_comp_id']==$c['comp_id']){echo 'selected="selected"';}?> value="<?php echo $c['comp_id']?>"><?php echo $c['comp_name']?></option>
                <?php }?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Contact Person Name </label>
              <input type="text" name="loc_contact_person" class="form-control" <?php if(isset($row)){echo 'value="'.$row[0]['loc_contact_person'].'"';}?>>
            </div>
            <div class="col-6">
              <label for="inputAddress" class="form-label">Contact Person Mobile</label>
              <input type="text" name="loc_mobile" class="form-control" <?php if(isset($row)){echo 'value="'.$row[0]['loc_contact_person_mobile'].'"';}?>>
            </div>
            <div class="col-md-6">
              <label class="form-label">Contact Person Email </label>
              <input type="email" name="loc_email" class="form-control" <?php if(isset($row)){echo 'value="'.$row[0]['loc_email'].'"';}?>>
            </div>
            <div class="col-md-6">
              <label for="inputFirstName" class="form-label">State</label>
              <select class="form-select" name="loc_state"  required="true">
                <option value=""> Select </option>
                <?php foreach($state as  $s){?>
                <option  <?php if(isset($row) && $row[0]['loc_state']==$s['id']){echo 'selected="selected"';}?> value="<?php echo $s['id']?>"><?php echo $s['state_name']?></option>
                <?php }?>
              </select>
            </div>
            <?php if(isset($row)){?>
              <input type="hidden" name="id" value="<?php echo $row[0]['id']?>" />
              <?php }?>
            <div class="col-12">
              <label for="inputAddress" class="form-label">Delivery Address</label>
              <textarea class="form-control" name="loc_addr1" rows="2"><?php if(isset($row)){echo $row[0]['loc_addr1'];}?></textarea>
            </div>
            <div class="col-4">
              <label for="inputAddress" class="form-label">GST No.</label>
              <input type="text" name="loc_gst_no" class="form-control" <?php if(isset($row)){echo 'value="'.$row[0]['loc_gst_no'].'"';}?>>
            </div>
            <div class="col-4">
              <label for="inputAddress" class="form-label">PAN No.s</label>
              <input type="text" name="loc_pan_no" class="form-control" <?php if(isset($row)){echo 'value="'.$row[0]['loc_pan_no'].'"';}?>>
            </div>
            <div class="col-4">
              <div class="s" style="margin-top:8px">
                <label for="inputEnterYourName" class="form-label">Status</label>
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" onclick="radio_chk()" id="edit_act" name="edstatus" value="1" <?php if(isset($row) && $row
				[0]['status']=='1'){echo 'checked="checked';}?> <?php if(!isset($row)){echo 'checked="checked"';}?>>
                  <label class="form-check-label"  for="act">Active/Inactive</label>
                </div>
              </div>
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
