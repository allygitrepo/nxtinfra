<?php include(APPPATH.'views/admin/layout/Header.php'); ?>

<div class="page-wrapper">
  <div class="page-content"> 
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Sub Menu</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a> </li>
            <li class="breadcrumb-item active" aria-current="page">Sub Menu List</li>
          </ol>
        </nav>
      </div>
      <div class="ms-auto">
        <button type="button" data-bs-toggle="modal" data-bs-target="#addnewmodel" class="btn btn-primary"><i class="fa fa-plus"></i>Add New </button>
      </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">Sub Menu List</h6>
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
        <div class="table-responsive">
          <table id="table" class=" table table-striped table-bordered" style="width:100%">
            <thead>
              <tr>
                <th>S.No</th>
                <th>Menu Name</th>
                <th>Sub Menu Name</th>
                <th>Order No.</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                <th>S.No</th>
                <th>Menu Name</th>
                <th>Sub Menu Name</th>
                <th>Order No.</th>
                <th>Status</th>
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
        <h5 class="modal-title" id="exampleModalLabel">Add Sub Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form class="row g-3" action="<?= site_url('setting/create_submenu'); ?>" method="post">
        <div class="modal-body">
          <div class="row mb-4 mt-4">
            <label class="col-md-4 control-label">Main Menu</label>
            <div class="col-md-8">
              <select class="form-select" name="main_menu" id="main_menu" required="">
                <option value=""> Select </option>
                <?php $i=1;foreach($mainmenu as $m){ ?>
                <option value="<?php echo $m['id']?>"><?php echo $m['menu_name']?></option>
                <?php }?>
              </select>
            </div>
          </div>
          <div class="row mb-4 mt-4">
            <label class="col-md-4 control-label">Sub Menu Name</label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="sub_menu_name" name="sub_menu_name" placeholder="" value="" required="required">
            </div>
          </div>
          <div class="row mb-4 mt-4">
            <label class="col-md-4 control-label">Order No.</label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="order_no" name="order_no" placeholder="" autocomplete="off" value="" required="required">
            </div>
          </div>
          <div class="row mb-4 mt-4">
            <label class="col-md-4 control-label">Source Link</label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="source" name="source" placeholder="" autocomplete="off" value="" required="required">
            </div>
          </div>
          <div class="row mb-4 mt-4">
            <label class="col-md-4 control-label">Target</label>
            <div class="col-md-8 ">
              <select class="form-select" name="target" id="target" required="">
                <option value=""> Select </option>
                <option value="Self" selected="selected"> Self </option>
                <option value="Blank"> Blank </option>
              </select>
            </div>
          </div>
          <div class="row mb-4 mt-4">
            <label for="inputEnterYourName" class="col-sm-4 col-form-label">Status</label>
            <div class="col-sm-8" style="margin-top:8px">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="act" name="status" value="Y" checked>
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
        <h5 class="modal-title" id="exampleModalLabel">Edit Sub Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form class="row g-3" action="<?= site_url('setting/update_submenu'); ?>" method="post">
        <div class="modal-body">
          <div class="row mb-4 mt-4">
            <label class="col-md-4 control-label">Main Menu</label>
            <div class="col-md-8">
              <select class="form-select" name="main_menu" id="edit_main_menu" required="">
                <option value=""> Select </option>
                <?php $i=1;foreach($mainmenu as $m){ ?>
                <option value="<?php echo $m['id']?>"><?php echo $m['menu_name']?></option>
                <?php }?>
              </select>
            </div>
          </div>
          <div class="row mb-4 mt-4">
            <label class="col-md-4 control-label">Sub Menu Name</label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="edit_sub_menu_name" name="sub_menu_name" placeholder="" value="" required="required">
            </div>
          </div>
          <div class="row mb-4 mt-4">
            <label class="col-md-4 control-label">Order No.</label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="edit_order_no" name="order_no" placeholder="" autocomplete="off" value="" required="required">
            </div>
          </div>
          <div class="row mb-4 mt-4">
            <label class="col-md-4 control-label">Source Link</label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="edit_source" name="source" placeholder="" autocomplete="off" value="" required="required">
            </div>
          </div>
          <div class="row mb-4 mt-4">
            <label class="col-md-4 control-label">Target</label>
            <div class="col-md-8 ">
              <select class="form-select" name="target" id="edit_target" required="">
                <option value=""> Select </option>
                <option value="Self"> Self </option>
                <option value="Blank"> Blank </option>
              </select>
            </div>
          </div>
          <div class="row mb-4 mt-4">
            <label for="inputEnterYourName" class="col-sm-4 col-form-label">Status</label>
            <div class="col-sm-8" style="margin-top:8px">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" onclick="radio_chk()" id="edit_act" name="edstatus" value="Y" checked>
                <label class="form-check-label"  for="act">Active/Inactive</label>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
        <input type="hidden" id="edid_id" value=""/>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" onclick="update()" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include(APPPATH.'views/admin/layout/master-bottom.php'); ?>
<script type="text/javascript">

var table;

$(document).ready(function() {
    //datatables
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('setting/ajax_submenu_list')?>",
            "type": "POST",
            "data": function ( data ) {
            }
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
		drawCallback: function() {}
    });

    $('#btn-filter').click(function(){ //button filter event click
        table.ajax.reload();  //just reload table
    });
});

</script> 
<script>
function edit_fn(id,manuid,order_no){
	var sub_name=$('#sub_name_'+id).val();
	var sub_source=$('#sub_source_'+id).val();
	var main_name=$('#main_name_'+id).val();
	var target=$('#sub_target_'+id).val();
	var status=$('#sub_status_'+id).val();
	var main_name=$('#main_name_'+id).val();
	
	 $('#edit_sub_menu_name').val(sub_name);
	 $('#edit_source').val(sub_source);
	 $('#edid_id').val(id);
	 $('#edit_order_no').val(order_no);
	
	 $('#edit_target[value='+target+']').attr('selected','selected');
	 $("#edit_target").val(target).change();
	 $("#edit_main_menu").val(manuid).change();
	 if (status=='Y') {
            $('#edit_act').attr('checked', 'checked');
        } else {
            $('#edit_act').removeAttr('checked');
        }
	}
function update(){
	 var name=$('#edit_sub_menu_name').val();
	 var source=$('#edit_source').val();
	 var id=$('#edid_id').val();
	 var order_no=$('#edit_order_no').val();
	 var target=$("#edit_target").val()
	 var status=$("#edit_act").val();
	 var manuid=$("#edit_main_menu").val();
	 var dataString = 'name='+name+'&source='+source+'&id='+id+'&order_no='+order_no+'&target='+target+'&status='+status+'&manuid='+manuid;
		 $.ajax({
                type: "POST",
                 url: "<?php echo site_url('setting/ajax_update_submenu')?>",                data: dataString,
                cache: false,
                success: function (data) {
                if(data==0){Swal.fire('Oops...','Duplicate State Name not allowed','error');}
					else{table.ajax.reload(); 
					$('#editmodel').modal('hide');
					Swal.fire('Good job!','State Updated','success');
				   }
                },
            });
	}	
</script>