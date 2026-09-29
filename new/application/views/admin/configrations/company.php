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
            <li class="breadcrumb-item active" aria-current="page">Company List</li>
          </ol>
        </nav>
      </div>
      <div class="ms-auto">
        <a type="button" href="<?= site_url('configrations/add-company'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i>Add New </a>
      </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">Company List</h6>
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
                <th>Company Name</th>
                <th>Code</th>
                <th>Vertical</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                 <th>S.No</th>
                <th>Company Name</th>
                <th>Code</th>
                <th>Vertical</th>
                <th>Action</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
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
            "url": "<?php echo site_url('configrations/ajax_company_list')?>",
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
