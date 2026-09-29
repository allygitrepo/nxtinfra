<?php include(APPPATH.'views/admin/layout/Header.php'); ?>

<div class="page-wrapper">
  <div class="page-content"> 
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Supplier</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a> </li>
            <li class="breadcrumb-item active" aria-current="page">Add Supplier</li>
          </ol>
        </nav>
      </div>
      <div class="ms-auto"> </div>
    </div>
    <!--end breadcrumb-->
    
    <hr/>
    <div class="card">
      <div class="card-body">
        <div class="col">
          <h6 class="mb-0 text-uppercase">Add Supplier</h6>
          <hr>
          <div class="card border-top border-0 border-4 border-primary">
            <div class="card-body p-5">
              <hr>
              <form class="row g-3" action="<?= site_url('supplier/create'); ?>" method="post">
                <div class="col-md-4">
                  <label for="inputFirstName" class="form-label">Type</label>
                  <select class="form-control" name="party_type" id="party_type" required="true">
                    <option value="0"> Select </option>
                    <option value="5">MSME Creditors</option>
                    <option value="7">Other Creditors</option>
                    <option value="4">Sundry Creditors</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label for="inputLastName" class="form-label">Supplier Category</label>
                  <select class="form-control" name="party_category" id="party_category" required="true">
                    <option value=""> Select </option>
                    <option value="35">Agency</option>
                    <option value="21">Arbitration</option>
                    <option value="48">Arbitration Expenses</option>
                    <option value="49">Arbitration Expenses</option>
                    <option value="36">Bank</option>
                    <option value="12">Car Supplier</option>
                    <option value="53">Communication charges</option>
                    <option value="30">Concrete Board Supplier</option>
                    <option value="11">Construction</option>
                    <option value="37">Construction of drain</option>
                    <option value="32">Consultant</option>
                    <option value="13">Contractor</option>
                    <option value="38">Courier</option>
                    <option value="20">Diary Supplier</option>
                    <option value="54">Directors Sitting Fees</option>
                    <option value="26">Electrical</option>
                    <option value="56">Electrical Consumables</option>
                    <option value="50">Electricity Expenses </option>
                    <option value="16">Email Service</option>
                    <option value="19">Employee</option>
                    <option value="17">Fuel Supply</option>
                    <option value="60">Guest House Miscellaneous exp.</option>
                    <option value="57">Headman</option>
                    <option value="41">Hotel</option>
                    <option value="31">Independent Engineer</option>
                    <option value="7">Information Technologies</option>
                    <option value="58">Institute </option>
                    <option value="47">Insurance </option>
                    <option value="45">Land Lord</option>
                    <option value="8">Manpower Supply</option>
                    <option value="44">NBFC</option>
                    <option value="43">NBSC</option>
                    <option value="62">Not Defined</option>
                    <option value="25">Painter</option>
                    <option value="9">Paints Supplier</option>
                    <option value="46">Photography</option>
                    <option value="29">Printing and stationary </option>
                    <option value="15">Printing Stationery</option>
                    <option value="10">Professional</option>
                    <option value="28">Professional</option>
                    <option value="59">Repair &amp; Maintenance - Electrical</option>
                    <option value="51">RM Vehicle Expenses </option>
                    <option value="14">Service Provider</option>
                    <option value="34">Service Provider</option>
                    <option value="33">Supplier</option>
                    <option value="18">Supplier  (Traders)</option>
                    <option value="23">Supplier (Avenue Plant)</option>
                    <option value="22">Supplier (Tanker)</option>
                    <option value="24">Supplier (Tree Guards)</option>
                    <option value="40">Supplier (Vehicle)</option>
                    <option value="27">Surveyor</option>
                    <option value="61">Testing</option>
                    <option value="55">Training and Education</option>
                    <option value="52">UPS AMC</option>
                    <option value="39">Water Supplier</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label for="inputEmail" class="form-label">Supplier Name</label>
                  <input type="text" name="party_name" class="form-control" required="required" >
                </div>
                
               
                       <div class="col-md-4">
                  <label for="inputPassword" class="form-label">Tax Category
 </label>
                  <br />

                  <div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="tax_category" id="inlineRadio1" value="R">
									<label class="form-check-label" for="inlineRadio1">Registered   </label>
								</div>
                                <div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="tax_category" id="inlineRadio1" value="U">
									<label class="form-check-label" for="inlineRadio1">Unregistered  </label>
								</div>
                </div>
                          
                <div class="col-md-4">
                  <label for="inputPassword" class="form-label">Tally Account Name </label>
                  <input type="text" name="tally_account_name" class="form-control" id="inputCity">
                </div>
                <div class="col-4">
                  <label for="inputAddress" class="form-label">Contact Person Name</label>
                  <input type="text" name="party_contact_person_name" class="form-control" id="inputCity">
                </div>
                
                <div class="col-md-6">
                <label for="inputAddress2" class="form-label">Designation</label>
                  <input type="text" name="party_designation" class="form-control" id="inputCity">
                  <label for="inputCity" class="form-label">Email</label>
                  <input type="email" class="form-control" name="party_email" id="inputCity">
                 
                </div>
                <div class="col-md-6">
                  <label for="inputState" class="form-label">Address</label>
                  <textarea name="party_address_1" rows="4" class="form-control"></textarea>
                </div>
                <div class="col-md-4">
                  <label for="inputZip" class="form-label">Mobile-1</label>
                  <input type="text" class="form-control" name="party_mobile">
                </div>
                <div class="col-md-4">
                  <label for="inputZip" class="form-label">Mobile-2</label>
                  <input type="text" class="form-control" name="party_mobile2">
                </div>
                <div class="col-md-4">
                  <label for="inputZip" class="form-label">Mobile-3</label>
                  <input type="text" class="form-control" name="party_mobile3">
                </div>
                <div class="col-md-4">
                  <label for="inputZip" class="control-label">State</label>
                  <select class="form-control" name="party_state" id="party_state" onchange="getcity(this.value)">
                    <option value=""> Select </option>
                    <option value="1">Andaman &amp; Nicobar Islands</option>
                    <option value="2">Andhra Pradesh</option>
                    <option value="3">Arunachal Pradesh</option>
                    <option value="4">Assam</option>
                    <option value="5">Bihar</option>
                    <option value="6">Chandigarh</option>
                    <option value="7">Chattisgarh</option>
                    <option value="8">Dadra &amp; Nagar Haveli</option>
                    <option value="9">Daman &amp; Diu</option>
                    <option value="10">Delhi</option>
                    <option value="11">Goa</option>
                    <option value="12">Gujarat</option>
                    <option value="13">Haryana</option>
                    <option value="14">Himachal Pradesh</option>
                    <option value="15">Jammu &amp; Kashmir</option>
                    <option value="16">Jharkhand</option>
                    <option value="17">Karnataka</option>
                    <option value="18">Kerala</option>
                    <option value="19">Lakshadweep</option>
                    <option value="20">Madhya Pradesh</option>
                    <option value="21">Maharashtra</option>
                    <option value="22">Manipur</option>
                    <option value="23">Meghalaya</option>
                    <option value="24">Mizoram</option>
                    <option value="25">Nagaland</option>
                    <option value="26">Odisha</option>
                    <option value="27">Poducherry</option>
                    <option value="28">Punjab</option>
                    <option value="29">Rajasthan</option>
                    <option value="30">Sikkim</option>
                    <option value="31">Tamil Nadu</option>
                    <option value="32">Telangana</option>
                    <option value="33">Tripura</option>
                    <option value="34">Uttar Pradesh</option>
                    <option value="35">Uttarakhand</option>
                    <option value="36">West Bengal</option>
                  </select>
                </div>
                <div class="col-md-4">
								<label class="control-label">City</label>
							
                <select class="form-control" name="party_city" id="party_city" required="true">
                                                    <option value=""> Select </option><option value="186">Goa
                                                </option>
                                                </select>
							</div>
                            
                            <div class="col-md-4">
								<label class="control-label">Pincode</label>
								<input type="text" class="form-control" id="party_pincode" name="party_pincode" placeholder="" value="">
                                
							</div>
                            
                          <div class="col-md-4">
								<label class="control-label">Phone</label>
								<input type="text" class="form-control" id="party_phone" name="party_phone" placeholder="" value="">
							</div>
                            
                            <div class="col-md-4">
								<label class="control-label">Phone-2</label>
								<input type="text" class="form-control" id="party_phone1" name="party_phone1" placeholder="" value="">
							</div>
                            
                            <div class="col-md-4">
								<label class="control-label">Phone-3</label>
								<input type="text" class="form-control" id="party_phone2" name="party_phone2" placeholder="" value="">
							</div>  
                            
                      <div class="col-md-4">
								<label class="control-label">Area</label>
								<input type="text" class="form-control" id="party_area" name="party_area" placeholder="" value="">
							</div>
						
							<div class="col-md-4">
								<label class="control-label">Country</label>
								<input type="text" class="form-control" id="party_country" name="party_country" placeholder="" value="">
							</div>
							
							<div class="col-md-4">
								<label class="control-label">Website</label>
								<input type="text" class="form-control" id="party_websites" name="party_websites" placeholder="" value="">
							</div>
						
					
				
						
							<div class="col-md-4">
								<label class=" control-label">GST Number</label>
								<input type="text" class="form-control" id="party_gst_number" name="party_gst_number" placeholder="" value="">
							</div>
							
							<div class="col-md-4">
								<label class=" control-label">PAN Number</label>
								<input type="text" class="form-control" id="party_pan_number" name="party_pan_number" placeholder="" value="">
							</div>
			
							<div class="col-md-4">
								<label class=" control-label">MSME Number</label>
								<input type="text" class="form-control" id="party_msme_number" name="party_msme_number" placeholder="" value="">
							</div>      
                            
                
                <div class="col-12">
                  <button type="submit" class="btn pull-right btn-primary px-5">Add</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include(APPPATH.'views/admin/layout/master-bottom.php'); ?>
