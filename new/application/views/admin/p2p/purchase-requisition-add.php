<?php include(APPPATH.'views/admin/layout/Header.php'); ?>

<div class="page-wrapper">
  <div class="page-content"> 
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">P2P</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a> </li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $page_name?></li>
          </ol>
        </nav>
      </div>
      <div class="ms-auto"> <a type="button" href="<?= site_url('p2p/purchase-requisition'); ?>" class="btn btn-primary"><i class="fa fa-arrow-right"></i>Go to list</a> </div>
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
          <form class="row g-3" action="<?php if(isset($row)){echo  site_url('p2p/pr_update');}else{echo site_url('p2p/pr_create');}; ?>" method="post" enctype="multipart/form-data">
            <div class="col-md-2">
              <?php if(isset($row)){?>
              <input type="hidden" name="id" value="<?php echo $row[0]['comp_id']?>" />
              <?php }?>
              <label for="inputEmail" class="form-label">Serial No. <span class="rdot">*</span></label>
              <input type="text" <?php if(isset($row)){echo 'value="'.$row[0]['serial'].'"';}?> name="serial" class="form-control" required="required" >
            </div>
            <div class="col-md-4">
              <?php if(isset($row)){?>
              <input type="hidden" name="id" value="<?php echo $row[0]['comp_id']?>" />
              <?php }?>
              <label for="inputEmail" class="form-label">Company  Name <span class="rdot">*</span></label>
              <select class="form-select" onchange="get_location()" name="company_id" id="company_id" required="true">
                <option value=""> Select </option>
                <?php foreach($company as  $c){?>
                <option  <?php if(isset($row) && $row[0]['company_id']==$c['comp_id']){echo 'selected="selected"';}?>  value="<?php echo $c['comp_id']?>"><?php echo $c['comp_name']?></option>
                <?php }?>
              </select>
            </div>
            <div class="col-md-4">
              <label for="inputEmail" class="form-label">Location <span class="rdot">*</span></label>
              <select class="form-select" name="location_id" id="location_id" required="true">
                <option value=""> Select </option>
              </select>
            </div>
            <div class="col-md-2">
              <label for="inputEmail" class="form-label">Date <span class="rdot">*</span></label>
              <input type="text" <?php if(isset($row)){echo 'value="'.$row[0]['on_date'].'"';}?> name="on_date" class="date form-control" required="required" >
            </div>
            <div class="col-md-4">
              <label for="inputEmail" class="form-label">Department<span class="rdot">*</span></label>
              <select class="form-select" name="department_id" id="department_id" required="true">
                <option value=""> Select </option>
                <?php foreach($department as  $d){?>
                <option  <?php if(isset($row) && $row[0]['department_id']==$d['id']){echo 'selected="selected"';}?>  value="<?php echo $d['id']?>"><?php echo $d['master_name']?></option>
                <?php }?>
              </select>
            </div>
            <div class="col-md-4">
              <?php if(isset($row)){?>
              <input type="hidden" name="id" value="<?php echo $row[0]['comp_id']?>" />
              <?php }?>
              <label for="inputEmail" class="form-label">Cost Centre Group <span class="rdot">*</span></label>
              <select class="form-select" onchange="get_cost_centre()" name="cost_centre_group_id" id="cost_centre_group_id" required="true">
                <option value=""> Select </option>
                <?php foreach($cost_group as  $cg){?>
                <option  <?php if(isset($row) && $row[0]['cost_centre_group_id']==$cg['id']){echo 'selected="selected"';}?>  value="<?php echo $cg['id']?>"><?php echo $cg['master_name']?></option>
                <?php }?>
              </select>
            </div>
            <div class="col-md-4">
              <?php if(isset($row)){?>
              <input type="hidden" name="id" value="<?php echo $row[0]['comp_id']?>" />
              <?php }?>
              <label for="inputEmail" class="form-label">Cost Centre Name <span class="rdot">*</span></label>
              <select class="form-select" name="cost_centre_id" id="cost_centre_id" required="true">
                <option value=""> Select </option>
              </select>
            </div>
            <div class="col-12">
              <label for="inputAddress" class="form-label">Remark <span class="rdot">*</span> </label>
              <textarea class="form-control" name="remark"><?php if(isset($row)){echo $row[0]['remark'];}?>
</textarea>
            </div>
            <div class="accordion" id="accordionExample">
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  Products  &nbsp;(<span id="product_count" class="product-count">0</span> )
                  </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <span class="pull-right acc-btn"><a class="btn btn-success" href="javascript:void(0)"  data-bs-toggle="modal" data-bs-target="#addproduct"> <i class="fa fa-plus"></i>Add</a></span>
                  <table class="table table-responsive" id="product_table">
                    <thead>
                      <tr>
                        <th>Material Name</th>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Value</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                      
                      
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  Documents <span id="document_count acc-count">(0)</span> 
                  </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                  <div class="accordion-body">
                  <table class="table table-responsive">
                    <thead>
                      <tr>
                        <th>S.No.</th>
                        <th>Material Name</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Value</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>1</td>
                        <td>Test</td>
                        <td>6</td>
                        <td>10</td>
                        <td>60</td>
                        <td><a href="javascript:void(0)" class="btn btn-info btn-xs"><i class="fa fa-edit"></i>Edit</a> <a href="javascript:void(0)" class="btn btn-danger  btn-xs"><i class="fa fa-times"></i>Delete</a></td>
                      </tr>
                      <tr>
                        <td>1</td>
                        <td>Test</td>
                        <td>6</td>
                        <td>10</td>
                        <td>60</td>
                        <td><a href="javascript:void(0)" class="btn btn-info btn-xs"><i class="fa fa-edit"></i>Edit</a> <a href="javascript:void(0)" class="btn btn-danger  btn-xs"><i class="fa fa-times"></i>Delete</a></td>
                      </tr>
                      <tr>
                        <td>1</td>
                        <td>Test</td>
                        <td>6</td>
                        <td>10</td>
                        <td>60</td>
                        <td><a href="javascript:void(0)" class="btn btn-info btn-xs"><i class="fa fa-edit"></i>Edit</a> <a href="javascript:void(0)" class="btn btn-danger  btn-xs"><i class="fa fa-times"></i>Delete</a></td>
                      </tr>
                    </tbody>
                  </table>
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                  Approval Log <span id="document_count acc-count">(0)</span> 
                  </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                  <div class="accordion-body">
                  <table class="table table-responsive">
                    <thead>
                      <tr>
                        <th>S.No.</th>
                        <th>Material Name</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Value</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>1</td>
                        <td>Test</td>
                        <td>6</td>
                        <td>10</td>
                        <td>60</td>
                        <td><a href="javascript:void(0)" class="btn btn-info btn-xs"><i class="fa fa-edit"></i>Edit</a> <a href="javascript:void(0)" class="btn btn-danger  btn-xs"><i class="fa fa-times"></i>Delete</a></td>
                      </tr>
                      <tr>
                        <td>1</td>
                        <td>Test</td>
                        <td>6</td>
                        <td>10</td>
                        <td>60</td>
                        <td><a href="javascript:void(0)" class="btn btn-info btn-xs"><i class="fa fa-edit"></i>Edit</a> <a href="javascript:void(0)" class="btn btn-danger  btn-xs"><i class="fa fa-times"></i>Delete</a></td>
                      </tr>
                      <tr>
                        <td>1</td>
                        <td>Test</td>
                        <td>6</td>
                        <td>10</td>
                        <td>60</td>
                        <td><a href="javascript:void(0)" class="btn btn-info btn-xs"><i class="fa fa-edit"></i>Edit</a> <a href="javascript:void(0)" class="btn btn-danger  btn-xs"><i class="fa fa-times"></i>Delete</a></td>
                      </tr>
                    </tbody>
                  </table>
                  </div>
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
<div class="modal fade" id="addproduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
        <div class="modal-body">
          <div class="row mb-2  mt-2">
            <label class="col-md-4 control-label">Material  Category</label>
            <div class="col-md-8">
              <select class="form-select" onchange="get_product('add')" name="main_menu" id="add_category">
                <option value=""> Select </option>
                <?php $i=1;foreach($category as $c){ ?>
                <option value="<?php echo $c['id']?>"><?php echo $c['master_name']?></option>
                <?php }?>
              </select>
            </div>
          </div>
          <div class="row mb-2  mt-2">
            <label class="col-md-4 control-label">Material Name</label>
            <div class="col-md-8">
            <select class="form-select" onchange="get_unit('add')" name="main_menu" id="add_product" required="">
                <option value=""> Select </option>
              </select>
            </div>
          </div>
          <div class="row mb-2  mt-2">
            <label class="col-md-4 control-label">Description</label>
            <div class="col-md-8">
             
             <textarea class="form-control" id="add_description" name="description" placeholder=""></textarea>
              
            </div>
          </div>
          <div class="">
          <div class="row mb-2  mt-2">
            <label class="col-md-4 control-label">Quantity</label>
            <div class="col-md-3">
              <input type="text" class="form-control" id="add_quantity" name="quantity" placeholder="" autocomplete="off"onkeypress='return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)' onchange="get_rate('add')" value="0" required="required">
            </div>
              <label class="col-md-2 control-label">Unit</label>
            <div class="col-md-3">
              <input type="text" class="form-control" id="add_unit" name="add_unit" placeholder="" autocomplete="off" value="0"   required="required" readonly="readonly">
            </div>
          </div>
      
          </div>
          <div class="row mb-2  mt-2">
            <label class="col-md-4 control-label">Rate</label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="add_rate" name="quantity" placeholder="" autocomplete="off" onkeypress='return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)' value="0" onchange="get_rate('add')" required="required">
            </div>
          </div>
          <div class="row mb-2  mt-2">
            <label class="col-md-4 control-label">Value</label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="add_value" name="add_value" placeholder="" autocomplete="off" value="" required="required" readonly="readonly">
            </div>
          </div>
          <p id="add_error" class="error_product"></p>
          
        </div>
        <div class="modal-footer">
        <input type="hidden" id="edid_id" value=""/>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" onclick="add_product()" class="btn btn-primary">Save</button>
        </div>
 
    </div>
  </div>
</div>
<div class="modal fade" id="editproduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
        <div class="modal-body">
          <div class="row mb-2  mt-2">
            <label class="col-md-4 control-label">Material  Category</label>
            <div class="col-md-8">
              <select class="form-select" onchange="get_product('edit')" name="main_menu" id="edit_category">
                <option value="" > Select </option>
                <?php $i=1;foreach($category as $c){ ?>
                <option value="<?php echo $c['id']?>"><?php echo $c['master_name']?></option>
                <?php }?>
              </select>
            </div>
          </div>
          <div class="row mb-2  mt-2">
            <label class="col-md-4 control-label">Material Name</label>
            <div class="col-md-8">
            <select class="form-select" onchange="get_unit('edit')" name="main_menu" id="edit_product" required="">
               
              </select>
            </div>
          </div>
          <div class="row mb-2  mt-2">
            <label class="col-md-4 control-label">Description</label>
            <div class="col-md-8">
             
             <textarea class="form-control" id="edit_description" name="description" placeholder=""></textarea>
              
            </div>
          </div>
          <div class="">
          <div class="row mb-2  mt-2">
            <label class="col-md-4 control-label">Quantity</label>
            <div class="col-md-3">
              <input type="text" class="form-control" id="edit_quantity" name="quantity" placeholder="" autocomplete="off"onkeypress='return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)' onchange="get_rate('edit')" value="0" required="required">
            </div>
              <label class="col-md-2 control-label">Unit</label>
            <div class="col-md-3">
              <input type="text" class="form-control" id="edit_unit" name="edit_unit" placeholder="" autocomplete="off" value="0"   required="required" readonly="readonly">
            </div>
          </div>
      
          </div>
          <div class="row mb-2  mt-2">
            <label class="col-md-4 control-label">Rate</label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="edit_rate" name="quantity" placeholder="" autocomplete="off" onkeypress='return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)' value="0" onchange="get_rate('edit')" required="required">
            </div>
          </div>
          <div class="row mb-2  mt-2">
            <label class="col-md-4 control-label">Value</label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="edit_value" name="edit_value" placeholder="" autocomplete="off" value="" required="required" readonly="readonly">
            </div>
          </div>
          <input type="hidden" value="" id="edit_product_id" name="edit_product_id"/>
          <p id="edit_error" class="error_product"></p>
          
        </div>
        <div class="modal-footer">
        <input type="hidden" id="edid_id" value=""/>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" onclick="update_product()" class="btn btn-primary">Save</button>
        </div>
 
    </div>
  </div>
</div>
<?php include(APPPATH.'views/admin/layout/master-bottom.php'); ?>
<script>
function add_product(){
	$('#add_error').html('');
	var category=$('#add_category').val();
	var product=$('#add_product').val();
	var description=$('#add_description').val();
	var quantity=$('#add_quantity').val();
	var rate=$('#add_rate').val();
	var value=$('#add_value').val();
	if(category==''){$('#add_error').html('Please Select category');return false;}
	if(product==''){$('#add_error').html('Please Select product');return false;}
	if(description==''){$('#add_error').html('Please Add description');return false;}
	if(quantity==0){$('#add_error').html('Please Add quantity');return false;}
	if(rate==0){$('#add_error').html('Please Add rate');return false;}
	var dataString = 'action=add_product&category='+category+'&product='+product+'&quantity='+quantity+'&rate='+rate+'&value='+value+'&description='+description;
	 $.ajax({
              type: "POST",
              url: "<?php echo site_url('p2p/ajax_add_product')?>",             
			  data: dataString,
              cache: false,
                success: function (data) {
				$('#product_table').append(data);
				calculate('add');//For further calulation part
				 $("#addproduct").modal('hide');//cloose  model	
				 $("#product_count").html($('.pids').length);//to get the product count
				},
            });
	}
function update_product(){
	$('#update_error').html('');
	var category=$('#edit_category').val();
	var product=$('#edit_product').val();
	var description=$('#edit_description').val();
	var quantity=$('#edit_quantity').val();
	var rate=$('#edit_rate').val();
	var value=$('#edit_value').val();
	var id=$('#edit_product_id').val();
	if(category==''){$('#edit_error').html('Please Select category');return false;}
	if(product==''){$('#edit_error').html('Please Select product');return false;}
	if(description==''){$('#edit_error').html('Please Add description');return false;}
	if(quantity==0){$('#edit_error').html('Please Add quantity');return false;}
	if(rate==0){$('#edit_error').html('Please Add rate');return false;}
	var dataString = 'action=edit_product&category='+category+'&product='+product+'&quantity='+quantity+'&rate='+rate+'&value='+value+'&description='+description+'&id='+id;
	 $.ajax({
              type: "POST",
              url: "<?php echo site_url('p2p/ajax_edit_product')?>",             
			  data: dataString,
              cache: false,
                success: function (data) {
				$('#product_'+id).html(data);
				calculate('edit');//For further calulation part
				$("#editproduct").modal('hide');//cloose  model	
				},
            });
	}	
  function delete_product(id){
	 if (!confirm("Do you want to delete this  ?")){
          return false;
        }
		$('#product_'+id).remove();
		$("#product_count").html($('.pids').length);//to get the product count
	}
function edit_product(insert,pname,pid,description,quantity,rate,value,category_id){
	 $("#edit_category").val(category_id).change();
	//get_product('edit');
	$('#edit_description').val(description);
	$('#edit_quantity').val(quantity);
	$('#edit_rate').val(rate);
	$('#edit_value').val(value);
	txt='<option value="'+pid+'" selected="selected"> '+pname+' </option>';
	$('#edit_product').html(txt);
	$('#edit_product_id').val(insert);
	}	
function get_product(type){
	var cat_id=$('#'+type+'_category').val();
	var dataString = 'action=get_product&cat_id='+cat_id;
		 $.ajax({
              type: "POST",
              url: "<?php echo site_url('p2p/ajax_get_product')?>",             
			  data: dataString,
              cache: false,
                success: function (data) {
					if(type=='edit'){$('#'+type+'_product').append(data);}else{$('#'+type+'_product').html(data);}
				calculate(type);	
				},
            });
	}

function get_unit(type){
	var units=$('#'+type+'_product').find('option:selected').attr('unit');
	$('#'+type+'_unit').val(units);
}
	
function calculate(type){
	}
function get_rate(type){
   var qty=$('#'+type+'_quantity').val();
   var rate=$('#'+type+'_rate').val();
   value=Number(qty)*Number(rate);
   $('#'+type+'_value').val(value);
	}		
function get_location(){
    var company_id= ($('#company_id').val());
	var dataString = 'action=get_location&company_id='+company_id;
		 $.ajax({
              type: "POST",
              url: "<?php echo site_url('p2p/ajax_get_location')?>",             
			  data: dataString,
              cache: false,
                success: function (data) {
				$('#location_id').html(data);	
				get_cost_centre()
				},
            });
			
		}
		
		
function get_cost_centre(){
    var company_id= ($('#company_id').val());
	var cost_centre_group_id= ($('#cost_centre_group_id').val());
	if(company_id==''){return false;}
	if(cost_centre_group_id==''){return false;}
	var dataString = 'action=get_location&company_id='+company_id+'&cost_centre_group_id='+cost_centre_group_id;
		 $.ajax({
              type: "POST",
              url: "<?php echo site_url('p2p/ajax_get_cost_centre')?>",             
			  data: dataString,
              cache: false,
                success: function (data) {
				$('#cost_centre_id').html(data);	
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

<style>
.acc-btn {
    margin-top: 10px;
    margin-right: 10px;
}
.acc-btn a, .acc-btn i {
    font-size: 10px;
}
.error_product{width:100%
text-align:center;color:#F00}
</style>