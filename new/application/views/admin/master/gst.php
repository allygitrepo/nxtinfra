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
            <li class="breadcrumb-item active" aria-current="page">GST List</li>
          </ol>
        </nav>
      </div>
      <div class="ms-auto"> <a type="button" href="<?= site_url('master/gst/add'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i>Add New </a> </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">GST List</h6>
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
                <th>Vertical Type</th>
                <th>Tax Description</th>
                <th>GST % Rate</th>
                <th>SGST Account Name</th>
                <th>CGST Account Name</th>
                <th>IGST Account Name</th>
                <th>Status </th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php $i=1; foreach($gst as $g){?>
              <tr>
                <td><?php echo $i;?></td>
                <td><?php echo GetForeignKey('master','id',$g['vertical_type'],'master_name');?></td>
                <td><?php echo $g['gst_name'];?></td>
                <td><?php echo $g['igst'];?></td>
                <td><?php echo GetForeignKey('account_mst','id',$g['sgst_account_id'],'account_name');?></td>
                <td><?php echo GetForeignKey('account_mst','id',$g['cgst_account_id'],'account_name');?></td>
                <td><?php echo GetForeignKey('account_mst','id',$g['igst_account_id'],'account_name');?></td>
                <td><?php if($g['status']=='Y'){echo 'Active';}else{echo 'Inactive';};?></td>
                <td><a href="<?php echo site_url('master/gst/edit/'.$g['id']);?>" class="btn btn-primary px-4 radius-30"><i class="fa fa-edit"></i> Edit</a> <a href="<?php echo site_url('master/gst_delete/'.$g['id']);?>" onclick="return confirm('Are you sure ?')" class="btn btn-danger px-4 radius-30"><i class="fa fa-times"></i> Delete</a></td>
              </tr>
              <?php  $i++;}?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include(APPPATH.'views/admin/layout/master-bottom.php'); ?>
