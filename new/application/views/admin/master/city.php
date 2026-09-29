<?php include(APPPATH.'views/admin/layout/Header.php'); ?>

<div class="page-wrapper">
  <div class="page-content"> 
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">City</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a> </li>
            <li class="breadcrumb-item active" aria-current="page">City List</li>
          </ol>
        </nav>
      </div>
      <div class="ms-auto">
        <button type="button" data-bs-toggle="modal" data-bs-target="#addnewmodel" class="btn btn-primary"><i class="fa fa-plus"></i>Add New </button>
      </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">City List</h6>
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
                
                <th>State Name</th>
                <th>City Name</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                <th>S.No</th>
                 <th>State Name</th>
                  <th>City Name</th>
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
        <h5 class="modal-title" id="exampleModalLabel">Add State</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form class="row g-3" action="<?= site_url('master/create_city'); ?>" method="post">
        <div class="modal-body">
         <div class="row mb-4 mt-4">
            <label class="col-md-4 control-label">State Name</label>
            <div class="col-md-8">
              <select class="form-select" name="state_id"  required="">
                <option value=""> Select </option>
                <?php foreach($state as $s){?>
                 <option value="<?php echo $s['id']?>"><?php echo $s['state_name']?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          
          <div class="row mb-4 mt-4">
            <label class="col-md-4 control-label">City Name</label>
            <div class="col-md-8">
              <input type="text" class="form-control"  name="city_name" placeholder="" value="" required="required">
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
        <h5 class="modal-title" id="exampleModalLabel">Edit State</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form class="row g-3" action="<?= site_url('master/update_state'); ?>" method="post">
        <div class="modal-body">
          <div class="row mb-4 mt-4">
            <label class="col-md-4 control-label">State Name</label>
            <div class="col-md-8">
             <select class="form-select" id="state_id"  required="">
                <option value=""> Select </option>
                <?php foreach($state as $s){?>
                 <option value="<?php echo $s['id']?>"><?php echo $s['state_name']?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="row mb-4 mt-4">
            <label class="col-md-4 control-label">City Name</label>
            <div class="col-md-8">
              <input type="text" class="form-control"  name="city_name" id="city_name" placeholder="" value="" required="required">
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
            "url": "<?php echo site_url('master/ajax_city_list')?>",
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

function edit_fn(id){
	var city_name=$('#city_'+id).val();
	var state=$('#states_id_'+id).val();
	 $('#city_name').val(city_name);
	 $('#edid_id').val(id);
	 $('#state_id').val(state).change();
	}
	
function update(){
    var city_name= encodeURIComponent($('#city_name').val());
	var edid_id= encodeURIComponent($('#edid_id').val());
	var state_id= encodeURIComponent($('#state_id').val());
    var dataString = 'action=ajax_update_city&city_name='+city_name+'&edid_id='+edid_id+'&state_id='+state_id;
		 $.ajax({
              type: "POST",
              url: "<?php echo site_url('master/ajax_update_city')?>",             
			  data: dataString,
              cache: false,
                success: function (data) {
					if(data==0){Swal.fire('Oops...','Duplicate City Name not allowed','error');}
					else{table.ajax.reload(); 
					$('#editmodel').modal('hide');
					Swal.fire('Good job!','City Updated','success');
				   }
                },
            });
		}
	

</script>