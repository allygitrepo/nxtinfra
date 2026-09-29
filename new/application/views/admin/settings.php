<?php include 'layout/master-top.php'; ?>

    <!--main stuffs-->

    <div class="row">

                    <div class="col-md-7">

                        <div class="card">

                            <?php echo form_open('admin/change_password'); ?>

                                <div class="card-body">

                                    <h4 class="card-title">Change Password</h4>

                                    <div class="form-group row">

                                        <label for="fname" class="col-sm-4 text-right control-label col-form-label">Current Password*</label>

                                        <div class="col-sm-8">

                                            <?php echo form_password(['name'=>'current_password','value'=>set_value('current_password'),'class'=>'form-control','placeholder'=>'Enter Current Password']); ?>

                                        </div>

                                    </div>

                                    <div class="form-group row">

                                        <label for="lname" class="col-sm-4 text-right control-label col-form-label">New Password*</label>

                                        <div class="col-sm-8">

                                            <?php echo form_password(['name'=>'new_password','value'=>set_value('new_password'),'class'=>'form-control','placeholder'=>'Enter New Password']); ?>

                                        </div>

                                    </div>

                                    <div class="form-group row">

                                        <label for="lname" class="col-sm-4 text-right control-label col-form-label">Confirm New Password*</label>

                                        <div class="col-sm-8">

                                            <?php echo form_password(['name'=>'retype_new_password','value'=>set_value('retype_new_password'),'class'=>'form-control','placeholder'=>'Retype New Password']); ?>

                                        </div>

                                    </div>

                                    

                                </div>

                                <div class="border-top">

                                    <div class="card-body">

                                        <?php echo form_submit(['class'=>'btn btn-primary','value'=>'Change Password']); ?>

                                    </div>

                                </div>

                            <?php echo form_close(); ?>

                        </div>

                        

                        </div>

                        <div class="col-lg-5">

                                <?= form_error('current_password'); ?>

                                <?= form_error('new_password'); ?>

                                <?= form_error('retype_new_password'); ?>                               

                                

                                <?php include 'layout/alerts.php'; ?>

                        </div>

                    </div>

    <!--main stuffs-->

<?php include 'layout/master-bottom.php'; ?>