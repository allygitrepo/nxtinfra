<?php include 'layout/master-top.php'; ?>

<!--main stuffs-->

<div class="row">
  <div class="col-md-7">
    <div class="card"> <?php echo form_open_multipart('admin/upload_picture'); ?>
      <div class="card-body">
        <h4 class="card-title">Name</h4>
        <div class="form-group row">
          <div class="col-sm-8">
            <?= $admin['name'] ?>
            <br>
            <br>
            <?php echo form_upload(['name'=>'userfile','class'=>'form-control']); ?> </div>
        </div>
      </div>
      
      <?php echo form_close(); ?> </div>
  </div>
  
  <div class="col-md-7">
    <div class="card"> <?php echo form_open('admin/update_profile'); ?>
      <div class="card-body">
        <h4 class="card-title">Update Profile</h4>
        <div class="form-group row">
          <label>Username*</label>
          <?php echo form_input(['name'=>'username','value'=>$admin['username'],'class'=>'form-control','disabled'=>'true']); ?> </div>
        <div class="form-group row">
          <label>Name</label>
          <?php echo form_input(['name'=>'name','value'=>$admin['name'],'class'=>'form-control','placeholder'=>'Enter Name']); ?> </div>
        <div class="form-group row">
          <label>Phone</label>
          <?php echo form_input(['name'=>'phone','value'=>$admin['phone'],'class'=>'form-control','placeholder'=>'Enter Phone']); ?> </div>
        <div class="form-group row">
          <label>Email</label>
          <?php echo form_input(['name'=>'email','value'=>$admin['email'],'class'=>'form-control','placeholder'=>'Enter Email']); ?> </div>
        <div class="form-group row">
          
          
      </div>
      <div class="border-top">
        <div class="card-body"> <?php echo form_submit(['class'=>'btn btn-primary','value'=>'Update Profile']); ?> </div>
      </div>
      <?php echo form_close(); ?> </div>
  </div>
  <div class="col-md-5">
    <?= form_error('name'); ?>
    <?= form_error('phone'); ?>
    <?= form_error('email'); ?>
    <?= form_error('business'); ?>
    <?php include 'layout/alerts.php'; ?>
  </div>
</div>

<!--main stuffs-->

<?php include 'layout/master-bottom.php'; ?>
