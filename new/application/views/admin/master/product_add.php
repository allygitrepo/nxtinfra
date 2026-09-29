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
      <div class="ms-auto"> <a type="button" href="<?= site_url('master/cost-center'); ?>" class="btn btn-primary"><i class="fa fa-arrow-right"></i>Go to list</a> </div>
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
          <form class="row g-3" action="<?php if(isset($product)){echo  site_url('master/product_update');}else{echo site_url('master/product_create');}; ?>" method="post" enctype="multipart/form-data">
            <div class="col-md-4">
              <label for="inputFirstName" class="form-label">Vertical Type</label>
              <select class="form-select" name="vertical_type" onchange="get_data()" id="vt" required="true">
                <option value=""> Select </option>
                <?php foreach($vertical as  $v){?>
                <option <?php if(isset($product) && $product[0]['vertical_type']==$v['id']){echo 'selected="selected"';}?> value="<?php echo $v['id']?>"><?php echo $v['master_name']?></option>
                <?php }?>
              </select>
            </div>
            <div class="col-md-2">
              <button class="btn btn-info btn-sm add-new-btn" style="color:#FFF;margin-top:30px"  data-bs-toggle="modal" type="button" data-bs-target="#addnewmaster" onclick="newmaster(4,'vt')">Add new</button>
            </div>
            <div class="col-md-6">
              <label for="inputEmail" class="form-label">Product Name<span class="rdot">*</span></label>
              <input type="text" name="name"  <?php if(isset($product)){echo 'value="'.$product[0]['name'].'"';}?>  class="form-control" required="required" >
              <?php if(isset($product)){?>
              <input type="hidden" name="id" value="<?php echo $product[0]['id']?>" />
              <?php }?>
            </div>
            <div class="col-md-4">
              <label for="inputFirstName" class="form-label">Product Group</label>
              <select class="form-select" name="product_group"  id="pg" required="true">
              
                <option value=""> Select </option>
                <?php foreach($product_group as  $pg){?>
                <option <?php if(isset($product) && $product[0]['product_group']==$pg['id']){echo 'selected="selected"';}?> value="<?php echo $pg['id']?>"><?php echo $pg['master_name']?></option>
                <?php }?>
              </select>
            </div>
            <div class="col-md-2">
              <button class="btn btn-info btn-sm add-new-btn" style="color:#FFF;margin-top:30px"  data-bs-toggle="modal" type="button" data-bs-target="#addnewmaster" onclick="newmaster(1,'pg')">Add new</button>
            </div>
            <div class="col-md-4">
              <label for="inputFirstName" class="form-label">Unit Of Measurement*</label>
              <select class="form-select" name="uom"  id="ut" required="true">
                <option value=""> Select </option>
                <?php foreach($units as  $u){?>
                <option <?php if(isset($product) && $product[0]['uom']==$u['id']){echo 'selected="selected"';}?> value="<?php echo $u['id']?>"><?php echo $u['master_name']?></option>
                <?php }?>
              </select>
            </div>
            <div class="col-md-2">
              <button class="btn btn-info btn-sm add-new-btn" style="color:#FFF;margin-top:30px"  data-bs-toggle="modal" type="button" data-bs-target="#addnewmaster" onclick="newmaster(3,'ut')">Add new</button>
            </div>
            <div class="col-md-6">
              <label for="inputEmail" class="form-label">Category<span class="rdot">*</span></label>
              <br />
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="category" id="inlineRadio1" value="M" <?php if(isset($product) && $product[0]['category']=='M'){echo 'checked="checked';}?><?php if(!isset($product)){echo 'checked="checked"';}?> <?php if(!isset($product)){echo 'checked="checked"';}?>  >
                <label class="form-check-label" for="inlineRadio1">Material </label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="category" id="inlineRadio2" value="S" <?php if(isset($product) && $product[0]['category']=='S'){echo 'checked="checked';}?> >
                <label class="form-check-label" for="inlineRadio2">Service </label>
              </div>
            </div>
            <div class="col-md-6">
              <label for="inputEmail" class="form-label">PO Threashold on<span class="rdot">*</span></label>
              <br />
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="po_threashold" id="inlineRadio3" value="Q" <?php if(isset($product) && $product[0]['po_threashold']=='Q'){echo 'checked="checked';}?> <?php if(!isset($product)){echo 'checked="checked"';}?>     >
                <label class="form-check-label" for="inlineRadio3">Qty </label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="po_threashold" id="inlineRadio4" value="V" <?php if(isset($product) && $product[0]['po_threashold']=='V'){echo 'checked="checked';}?>>
                <label class="form-check-label" for="inlineRadio4">Value </label>
              </div>
            </div>
            <div class="col-md-6">
              <label for="inputEmail" class="form-label">GST Type<span class="rdot"></span></label>
              <select class="form-select" name="gst_type"  id="gst" required="true">
               <?php if(isset($product)){
				  echo ' <option value="'.$product[0]['gst_type'].'">'.GetForeignKey('gst_mst','id',$product[0]['gst_type'],'gst_name').'</option>';
				  }else{echo '<option value=""> Select </option>'; }?>
              </select>
            </div>
            <div class="col-md-6">
              <label for="inputEmail" class="form-label">Debit Allocation A/c<span class="rdot"></span></label>
              <select class="form-select" name="account_id"  id="ac" required="true">
               <?php if(isset($product)){
				  echo ' <option value="'.$product[0]['gst_type'].'">'.GetForeignKey(' 	account_mst ','id',$product[0]['account_id'],'account_name').'</option>';
				  }else{echo '<option value=""> Select </option>'; }?>
              </select>
            </div>
            <div class="col-md-6">
              <label for="inputEmail" class="form-label">Tolerance Level%* <span class="rdot">*</span></label>
              <input type="text" name="tolerance_level"  <?php if(isset($product)){echo 'value="'.$product[0]['tolerance_level'].'"';}?>  class="form-control"  required="required" >
            </div>
            <div class="col-md-6">
              <label for="inputEmail" class="form-label">HSN Code<span class="rdot">*</span></label>
              <input type="text" name="hsn_code" <?php if(isset($product)){echo 'value="'.$product[0]['hsn_code'].'"';}?>   class="form-control" required="required" >
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
function get_data(){
	var vt= ($('#vt').val());
	/*For Debit Allocation A/c*/
	var dataString = 'action=get_ac_for_add_product&vt='+vt;
		 $.ajax({
              type: "POST",
              url: "<?php echo site_url('master/get_ac_for_add_product')?>",             
			  data: dataString,
              cache: false,
                success: function (data) {
					$('#ac').html(data);
                },
            });
			/*For GST Type*/
	var dataString = 'action=get_gst_for_add_product&vt='+vt;
		 $.ajax({
              type: "POST",
              url: "<?php echo site_url('master/get_gst_for_add_product')?>",             
			  data: dataString,
              cache: false,
                success: function (data) {
					$('#gst').html(data);
                },
            });		
	}	

</script> 
