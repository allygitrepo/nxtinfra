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
        <a type="button" href="<?= site_url('configrations/location/add'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i>Add New </a>
      </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">Location List</h6>
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
          <table id="table" class="datatable table table-striped table-bordered" style="width:100%">
            <thead>
              <tr>
                <th>S.No</th>
                <th>Location</th>
                <th>Company</th>
                <!--<th>Address</th>-->
                <th>Contact No.</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            <?php $i=1; foreach($location as $m){?>
            <tr>
                <td><?= $i ?></td>
                <td><?= $m['loc_name']; ?></td>
                <td><?php echo GetForeignKey('company','comp_id',$m['loc_comp_id'],'comp_name'); $m['loc_comp_id']; ?></td>
                <!--<td><?= $m['loc_addr1']; ?></td>-->
                <td><?= $m['loc_contact_person_mobile']; ?></td>
                <td><?= $m['loc_email']; ?></td>
                <td><?php if($m['status']==1){echo 'Active';}else{{echo 'Inactive';}} ?></td>
                <td><a href="<?= site_url('configrations/location/edit/'.$m['id']); ?>" class="btn btn-primary px-4 radius-30"><i class="fa fa-edit"></i> Edit</a> <a href="<?= site_url('configrations/delete_location/'.$m['id']); ?>" onclick="return confirm('Are you sure ?')" class="btn btn-danger px-4 radius-30"><i class="fa fa-times"></i> Delete</a></td>
              </tr>
            <?php $i++; } ?>
            </tbody>
            
          </table>
        </div>
      </div>
    </div>
  </div>
</div>


<?php include(APPPATH.'views/admin/layout/master-bottom.php'); ?>
