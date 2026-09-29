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
      <div class="ms-auto"> <a type="button" href="<?= site_url('configrations/workflow'); ?>" class="btn btn-primary"><i class="fa fa-arrow-right"></i>Go to list</a> </div>
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
          <form class="row g-3" action="<?php if(isset($row)){echo  site_url('configrations/workflow_update');}else{echo site_url('configrations/workflow_create');}; ?>" method="post" enctype="multipart/form-data">
            <div class="col-md-4">
              <label for="inputFirstName" class="form-label">Vertical Type* </label>
              <select class="form-select" name="vertical_type" id="vt" required="true">
                <option value=""> Select </option>
                <?php foreach($vertical as  $v){?>
                <option  <?php if(isset($row) && $row[0]['vertical_type']==$v['id']){echo 'selected="selected"';}?>  value="<?php echo $v['id']?>"><?php echo $v['master_name']?></option>
                <?php }?>
              </select>
            </div>
            <div class="col-md-2">
              <button class="btn btn-info btn-sm add-new-btn" style="color:#FFF;margin-top:30px"  data-bs-toggle="modal" type="button" data-bs-target="#addnewmaster" onclick="newmaster(4,'vt')">Add new</button>
            </div>
            <div class="col-md-6">
              <label class="form-label">Type of Form * </label>
              <select class="form-select" name="doc_type" id="doc_type"required="true" >
                <option value=""> Select </option>
                 <option value="PO"<?php if(isset($row) && $row[0]['doc_type']=='PR'){echo 'selected="selected"';}?>> Purchase Requisition </option>
                <option value="PO"<?php if(isset($row) && $row[0]['doc_type']=='PO'){echo 'selected="selected"';}?>> Purchase Order </option>
                <option value="SI"<?php if(isset($row) && $row[0]['doc_type']=='SI'){echo 'selected="selected"';}?>> Supplier Invoice </option>
                <option value="PY"<?php if(isset($row) && $row[0]['doc_type']=='PY'){echo 'selected="selected"';}?>> Payment </option>
                <option value="IP"<?php if(isset($row) && $row[0]['doc_type']=='IP'){echo 'selected="selected"';}?>> IPC </option>
                <option value="PC"<?php if(isset($row) && $row[0]['doc_type']=='PC'){echo 'selected="selected"';}?>> Imprest A/c </option>
                <option value="TA"<?php if(isset($row) && $row[0]['doc_type']=='TA'){echo 'selected="selected"';}?>> Travel Approval </option>
                <option value="TE"<?php if(isset($row) && $row[0]['doc_type']=='TE'){echo 'selected="selected"';}?>> Travel Expenses </option>
                <option value="BD"<?php if(isset($row) && $row[0]['doc_type']=='BA'){echo 'selected="selected"';}?>> Budget Adjustment </option>
              </select>
            </div>
            <div class="col-6">
              <label for="inputAddress" class="form-label">For Transaction Type* </label>
              <input type="text" name="trans_type" class="form-control" <?php if(isset($row)){echo 'value="'.$row[0]['trans_type'].'"';}?> required="true">
            </div>
            <div class="col-md-6">
              <label class="form-label">Prefix  </label>
              <input type="text" name="prefix" class="form-control" <?php if(isset($row)){echo 'value="'.$row[0]['prefix'].'"';}?> >
            </div>
            <?php if(isset($row)){?>
            <input type="hidden" name="id" value="<?php echo $row[0]['id']?>" />
            <?php }?>
            <div class="col-6">
              <label for="inputAddress" class="form-label">Suffix </label>
              <input type="text" name="suffix" class="form-control" <?php if(isset($row)){echo 'value="'.$row[0]['suffix'].'"';}?>>
            </div>
            <div class="col-md-6">
              <label class="form-label">From Value * </label>
              <input type="text" name="from_value" id="from_value" class="form-control" <?php if(isset($row)){echo 'value="'.$row[0]['from_value'].'"';}?> required="true" onchange="chk_value()">
            </div>
            <div class="col-md-6">
              <label class="form-label">To Value * </label>
              <input type="text" name="to_value" id="to_value" class="form-control" <?php if(isset($row)){echo 'value="'.$row[0]['to_value'].'"';}?> required="true" onchange="chk_value()">
            </div>
            <div class="col-md-4">
              <label class="form-label">Approver Level 1 * </label>
              <select class="form-select" name="approval_role_1" id="a1" required="true">
                <option value=""> Select </option>
                <?php foreach($role as  $r){?>
                <option  <?php if(isset($row) && $row[0]['approval_role_1']==$r['id']){echo 'selected="selected"';}?>  value="<?php echo $r['id']?>"><?php echo $r['master_name']?></option>
                <?php }?>
              </select>
            </div>
            <div class="col-md-2">
              <button class="btn btn-info btn-sm add-new-btn" style="color:#FFF;margin-top:30px"  data-bs-toggle="modal" type="button" data-bs-target="#addnewmaster" onclick="newmaster(6,'a1')">Add new</button>
            </div>
            <div class="col-md-4">
              <label class="form-label">Approver Level 2 * </label>
              <select class="form-select" name="approval_role_2" id="a2" required="true">
                <option value=""> Select </option>
                <?php foreach($role as  $r){?>
                <option  <?php if(isset($row) && $row[0]['approval_role_2']==$r['id']){echo 'selected="selected"';}?>  value="<?php echo $r['id']?>"><?php echo $r['master_name']?></option>
                <?php }?>
              </select>
            </div>
            <div class="col-md-2">
              <button class="btn btn-info btn-sm add-new-btn" style="color:#FFF;margin-top:30px"  data-bs-toggle="modal" type="button" data-bs-target="#addnewmaster" onclick="newmaster(6,'a2')">Add new</button>
            </div>
            <div class="col-md-4">
              <label class="form-label">Approver Level 3 * </label>
              <select class="form-select" name="approval_role_3" id="a3" required="true">
                <option value=""> Select </option>
                <?php foreach($role as  $r){?>
                <option  <?php if(isset($row) && $row[0]['approval_role_3']==$r['id']){echo 'selected="selected"';}?>  value="<?php echo $r['id']?>"><?php echo $r['master_name']?></option>
                <?php }?>
              </select>
            </div>
            <div class="col-md-2">
              <button class="btn btn-info btn-sm add-new-btn" style="color:#FFF;margin-top:30px"  data-bs-toggle="modal" type="button" data-bs-target="#addnewmaster" onclick="newmaster(6,'a3')">Add new</button>
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
            <label class="col-md-4 control-label">Master Name* </label>
            <div class="col-md-8">
              <input type="text" <?php if(isset($row)){echo 'value="'.$row[0]['loc_name'].'"';}?> class="form-control" id="add_master_name" name="master_name" placeholder="" value="" required="required">
              <input type="hidden" value="" name="add_master_id" id="add_master_id" />
              <input type="hidden" value="" name="add_type" id="add_type" />
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
	
function newmaster(id,type){
	$('#add_master_id').val(id);
	$('#add_type').val(type);
	}
	
function save_master(){
	var add_type=$('#add_type').val();
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
					$('#'+add_type).append('<option value="'+data+'"selected="selected" >'+name+'</option>')
				   }
                },
            });
		}
	
function chk_value(){
    var from_value= ($('#from_value').val());
	var to_value= ($('#to_value').val());
	if(from_value>=to_value){alert('To Value Should be greater than From value');$('#to_value').focus();$('#to_value').val('');return false;}
		}
	

</script> 
