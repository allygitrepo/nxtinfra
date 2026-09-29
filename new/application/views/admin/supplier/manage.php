<?php include(APPPATH.'views/admin/layout/Header.php'); ?>

<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Supplier</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Supplier List</li>
							</ol>
						</nav>
					</div>
					<div class="ms-auto">
						
					</div>
				</div>
				<!--end breadcrumb-->
				<h6 class="mb-0 text-uppercase">Supplier List</h6>
				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="" class=" datatable table table-striped table-bordered" style="width:100%">
								<thead>
									<tr>
										<th>S.No</th>
										<th>Supplier</th>
										<th>Category</th>
										<th>Mobile	</th>
										<th>Address</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									
                                   <?php $i=1;foreach($service as $s): ?>  
									<tr>
										<td><?= $i ?></td>
										<td><?= $s['party_name']; ?></td>
										<td><?= $s['party_category']; ?></td>
										<td><?= $s['party_mobile']; ?></td>
										<td><?= $s['party_address_1']; ?></td>
										<td><span class="btn btn-info"><i class="fa fa-edit"></i></span>
                                        <span class="btn btn-danger"><i class="fa fa-times"></i></span>
                                        </td>
									</tr>
                                   <?php $i++; endforeach; ?>  
								</tbody>
								<tfoot>
									<tr>
										<th>S.No</th>
										<th>Supplier</th>
										<th>Category</th>
										<th>Mobile	</th>
										<th>Address</th>
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



