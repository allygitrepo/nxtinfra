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
      <div class="ms-auto"> <a type="button" href="<?= site_url('master/gst'); ?>" class="btn btn-primary"><i class="fa fa-arrow-right"></i>Go to list</a> </div>
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
    <?php include APPPATH.'views/admin/layout/alerts.php';?>
    <hr/>
    <div class="card">
      <div class="card-body">
        <div class="">
          <form class="row g-3" action="<?php if(isset($row)){echo  site_url('master/gst_update');}else{echo site_url('master/gst_create');}; ?>" method="post" enctype="multipart/form-data">
            <div class="col-md-4">
              <label for="inputFirstName" class="form-label">Vertical Type</label>
              <select class="form-select" onchange="get_account()" name="vertical_type"  id="master_select" required="true">
                <option value=""> Select </option>
                <?php foreach($vertical as  $v){?>
                <option <?php if(isset($row) && $row[0]['vertical_type']==$v['id']){echo 'selected="selected"';}?> value="<?php echo $v['id']?>"><?php echo $v['master_name']?></option>
                <?php }?>
              </select>
            </div>
            <div class="col-md-2">
              <button class="btn btn-info btn-sm add-new-btn" style="color:#FFF;margin-top:30px"  data-bs-toggle="modal" type="button" data-bs-target="#addnewmaster" onclick="newmaster(4)">Add new</button>
            </div>
            
            <div class="col-md-6">
              <label for="inputEmail" class="form-label">TAX Description<span class="rdot">*</span></label>
              <input type="text" name="gst_name"  <?php if(isset($row)){echo 'value="'.$row[0]['gst_name'].'"';}?>  class="form-control" required="required" >
              <?php if(isset($row)){?>
              <input type="hidden" name="id" value="<?php echo $row[0]['id']?>" />
              <?php }?>
            </div>
            
            <div class="col-md-6">
              <label for="inputEmail" class="form-label">GST % Rate<span class="rdot">*</span></label>
              <input type="text" name="rate"  <?php if(isset($row)){echo 'value="'.$row[0]['igst'].'"';}?>  class="form-control" required="required" >
              
            </div>
            
            <div class="col-md-6">
              <label for="inputFirstName" class="form-label">SGST Account Name*</label>
              <select class="form-select gst-select" name="sgst"  required="true">
                 <?php if(isset($row)){
				  echo ' <option value="'.$row[0]['gst_type'].'">'.GetForeignKey(' 	account_mst ','id',$row[0]['sgst'],'account_name').'</option>';
				  }else{echo '<option value=""> Select </option>'; }?>
              </select>
            </div>
            <div class="col-md-6">
              <label for="inputFirstName" class="form-label">CGST Account Name*</label>
              <select class="form-select gst-select" name="cgst"  required="true">
                 <?php if(isset($row)){
				  echo ' <option value="'.$row[0]['gst_type'].'">'.GetForeignKey(' 	account_mst ','id',$row[0]['cgst'],'account_name').'</option>';
				  }else{echo '<option value=""> Select </option>'; }?>
              </select>
            </div>
            <div class="col-md-6">
              <label for="inputFirstName" class="form-label">IGST Account Name*</label>
              <select class="form-select gst-select" name="igst"  required="true">
                 <?php if(isset($row)){
				  echo ' <option value="'.$row[0]['gst_type'].'">'.GetForeignKey(' 	account_mst ','id',$row[0]['igst'],'account_name').'</option>';
				  }else{echo '<option value=""> Select </option>'; }?>
              </select>
            </div>
            
            <div class="col-6">
              <div class="s" style="margin-top:8px">
                <label for="inputEnterYourName" class="form-label">Status</label>
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" <?php if(isset($row) && $row[0]['status']=='Y'){echo 'checked';}?>   name="status" value="1" >
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
function get_account(){
	var mid=$('#master_select').val();
	var dataString = 'action=ajax_get_gst_account&mid='+mid;
		 $.ajax({
              type: "POST",
              url: "<?php echo site_url('master/ajax_get_gst_account')?>",             
			  data: dataString,
              cache: false,
                success: function (data) {
					$('.gst-select').html(data);
					},
            });
	
	}	
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
