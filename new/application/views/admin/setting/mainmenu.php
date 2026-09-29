 <?php include(APPPATH.'views/admin/layout/Header.php'); ?>

<div class="page-wrapper">
  <div class="page-content"> 
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Main Menu</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a> </li>
            <li class="breadcrumb-item active" aria-current="page">Main Menu List</li>
          </ol>
        </nav>
      </div>
      <div class="ms-auto">
        <button type="button" data-bs-toggle="modal" data-bs-target="#addnewmodel" class="btn btn-primary"><i class="fa fa-plus"></i>Add New </button>
      </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">Main Menu List</h6>
    <div class="error"> 
    <?php if($this->session->flashdata('this_error')) {?>
    <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show"><div class="text-white"><?= $this->session->flashdata('this_error'); ?></div></div>
    <?php }?>
     </div>
    <?php include APPPATH.'views/admin/layout/alerts.php'; ?>
    <hr/>
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table id="" class=" datatable table table-striped table-bordered" style="width:100%">
            <thead>
              <tr>
                <th>S.No</th>
                <th>Menu  Name</th>
                <th>Menu Order</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php $i=1;foreach($mainmenu as $m): ?>
              <tr>
                <td><?= $i ?></td>
                <td><?= $m['menu_name']; ?></td>
                <td><?= $m['order_no']; ?></td>
                <td><?php if($m['status']==1){echo 'Active';}else{{echo 'Inactive';}} ?></td>
                <td><a data-bs-toggle="modal" onclick="edit_fn(<?php echo $m['id']?>,'<?php echo $m['menu_name']?>','<?php echo $m['order_no']?>','<?php echo $m['status']?>')" data-bs-target="#editmodel" class="btn btn-primary px-4 radius-30"><i class="fa fa-edit"></i> Edit</a> <a href="<?= site_url('setting/delete_mainmenu/'.$m['id']); ?>" onclick="return confirm('Are you sure ?')" class="btn btn-danger px-4 radius-30"><i class="fa fa-times"></i> Delete</a></td>
              </tr>
              <?php $i++; endforeach; ?>
            </tbody>
            <tfoot>
              <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>Action</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="addnewmodel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Main Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form class="row g-3" action="<?= site_url('setting/create_mainmenu'); ?>" method="post">
        <div class="modal-body">
          <div class="row mb-4 mt-4">
            <label for="inputEnterYourName" class="col-sm-4 col-form-label">Main Menu *</label>
            <div class="col-sm-8">
              <input type="text" name="menu_name" class="form-control"  required="required">
            </div>
          </div>
          <div class="row mb-4 mt-4">
            <label for="inputEnterYourName" class="col-sm-4 col-form-label">Order Number*</label>
            <div class="col-sm-8">
              <input type="text" name="order_no" class="form-control"  required="required">
            </div>
          </div>
          
          <div class="row mb-4 mt-4">
            <label for="inputEnterYourName" class="col-sm-4 col-form-label">Status</label>
            <div class="col-sm-8">
             <div class="form-check form-switch">
									<input class="form-check-input" type="checkbox" id="act" name="status" value="1" checked>
									<label class="form-check-label"  for="act">Active/Inactive</label>
								</div>
								
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="editmodel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Main Menu Type</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form class="row g-3" action="<?= site_url('setting/update_mainmenu'); ?>" method="post">
        <div class="modal-body">
          <div class="row mb-4 mt-4">
            <label for="inputEnterYourName" class="col-sm-4 col-form-label">Name *</label>
            <div class="col-sm-8">
              <input type="text" name="name" id="edit_name" class="form-control" >
              <input type="hidden" value="" name="id" id="edit_id"/>
            </div>
          </div>
          <div class="row mb-4 mt-4">
            <label for="inputEnterYourName" class="col-sm-4 col-form-label">Order Number *</label>
            <div class="col-sm-8">
              <input type="text" name="order_no" id="edit_order" class="form-control" >
            </div>
          </div>
          <div class="row mb-4 mt-4">
            <label for="inputEnterYourName" class="col-sm-4 col-form-label">Status</label>
            <div class="col-sm-8">
             <div class="form-check form-switch">
									<input class="form-check-input" type="checkbox" id="editact" name="status" value="1" checked>
									<label class="form-check-label"  for="act">Active/Inactive</label>
								</div>
								
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" 5onclick="update()"  class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include(APPPATH.'views/admin/layout/master-bottom.php'); ?>
<script>
function edit_fn(id,name,order_no,status){
	 $('#edit_name').val(name);
	 $('#edit_id').val(id);
	 $('#edit_order').val(order_no);
	 if (status==1) {
            $('#editact').attr('checked', 'checked');
        } else {
            $('#editact').removeAttr('checked');
        }
	}
function update(){
    var edit_name= encodeURIComponent($('#edit_name').val());
	var edid_id= encodeURIComponent($('#edid_id').val());
	var edit_order= encodeURIComponent($('#edit_order').val());
	var status=$('#editact').val();
	alert(status);
	return false;
	var mt=  $("#mt").val();
    var dataString = 'action=ajax_update_master&edit_name='+edit_name+'&edid_id='+edid_id+'&edit_order='+edit_order;
		 $.ajax({
              type: "POST",
              url: "<?php echo site_url('master/ajax_update_mainmenu')?>",             
			  data: dataString,
              cache: false,
                success: function (data) {
					if(data==0){Swal.fire('Oops...','Duplicate Menu or Order Number not allowed','error');}
					else{table.ajax.reload(); 
					$('#editmodel').modal('hide');
					Swal.fire('Good job!','Menu Updated','success');
				   }
                },
            });
		}	

</script>