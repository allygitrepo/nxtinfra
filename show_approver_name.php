<div class="box-footer">
											<?php
											if (!empty($approver_1)) {
												$sql = " SELECT a.* FROM sma_user a WHERE 1 AND a.id = '$approver_1' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_1_name = $rw['username'];
												$approver_1_role = $rw['primary_role'];

												$sql = " SELECT * FROM sma_role  WHERE 1 AND id = '$approver_1_role' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_1_role = $rw['role'];

												?>
												<div class="col-sm-2">
													<label class="control-label1">Approver 1</label><BR>
													<label
														class="control-label1"><?= $approver_1_name . " <BR> " ; ?>
													</label>
												</div>
											<?php
											}

											if (!empty($approver_2)) {
												$sql = " SELECT a.* FROM sma_user a WHERE 1 AND a.id = '$approver_2' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_2_name = $rw['username'];
												$approver_2_role = $rw['primary_role'];

												$sql = " SELECT * FROM sma_role  WHERE 1 AND id = '$approver_2_role' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_2_role = $rw['role'];
												?>
												<div class="col-sm-2">
													<label class="control-label1">Approver 2</label><BR>
													<label
														class="control-label1"><?= $approver_2_name . " <BR> " ; ?>
													</label>
												</div>
											<?php
											}

											if (!empty($approver_3)) {
												$sql = " SELECT a.* FROM sma_user a WHERE 1 AND a.id = '$approver_3' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_3_name = $rw['username'];
												$approver_3_role = $rw['primary_role'];

												$sql = " SELECT * FROM sma_role  WHERE 1 AND id = '$approver_3_role' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_3_role = $rw['role'];
												?>
												<div class="col-sm-2">
													<label class="control-label1">Approver 3</label><BR>
													<label
														class="control-label1"><?= $approver_3_name . " <BR> "; ?>
													</label>
												</div>
											<?php
											}
											if (!empty($approver_4)) {
												$sql = " SELECT a.* FROM sma_user a WHERE 1 AND a.id = '$approver_4' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_4_name = $rw['username'];
												$approver_4_role = $rw['primary_role'];

												$sql = " SELECT * FROM sma_role  WHERE 1 AND id = '$approver_4_role' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_4_role = $rw['role'];
												?>
												<div class="col-sm-2">
													<label class="control-label1">Approver 4</label><BR>
													<label
														class="control-label1"><?= $approver_4_name . " <BR> " ; ?>
													</label>
												</div>
											<?php
											}
											if (!empty($approver_5)) {
												$sql = " SELECT a.* FROM sma_user a WHERE 1 AND a.id = '$approver_5' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_5_name = $rw['username'];
												$approver_5_role = $rw['primary_role'];

												$sql = " SELECT * FROM sma_role  WHERE 1 AND id = '$approver_5_role' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_5_role = $rw['role'];
												?>
												<div class="col-sm-2">
													<label class="control-label1">Approver 5</label><BR>
													<label
														class="control-label1"><?= $approver_5_name . " <BR> " ; ?>
													</label>
												</div>
											<?php
											}
											if (!empty($approver_6)) {
												$sql = " SELECT a.* FROM sma_user a WHERE 1 AND a.id = '$approver_6' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_6_name = $rw['username'];
												$approver_6_role = $rw['primary_role'];

												$sql = " SELECT * FROM sma_role  WHERE 1 AND id = '$approver_6_role' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_6_role = $rw['role'];
												?>
												<div class="col-sm-2">
													<label class="control-label1">Approver 6</label><BR>
													<label
														class="control-label1"><?= $approver_6_name . " <BR> " ; ?>
													</label>
												</div>
											<?php
											}
											if (!empty($approver_7)) {
												$sql = " SELECT a.* FROM sma_user a WHERE 1 AND a.id = '$approver_7' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_7_name = $rw['username'];
												$approver_7_role = $rw['primary_role'];

												$sql = " SELECT * FROM sma_role  WHERE 1 AND id = '$approver_7_role' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_7_role = $rw['role'];
												?>
												<div class="col-sm-2">
													<label class="control-label1">Approver 7</label><BR>
													<label
														class="control-label1"><?= $approver_7_name . " <BR> " ; ?>
													</label>
												</div>
											<?php
											}
											if (!empty($approver_8)) {
												$sql = " SELECT a.* FROM sma_user a WHERE 1 AND a.id = '$approver_8' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_8_name = $rw['username'];
												$approver_8_role = $rw['primary_role'];

												$sql = " SELECT * FROM sma_role  WHERE 1 AND id = '$approver_8_role' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_8_role = $rw['role'];
												?>
												<div class="col-sm-2">
													<label class="control-label1">Approver 8</label><BR>
													<label
														class="control-label1"><?= $approver_8_name . " <BR> " ; ?>
													</label>
												</div>
											<?php
											}

											if (!empty($approver_9)) {
												$sql = " SELECT a.* FROM sma_user a WHERE 1 AND a.id = '$approver_9' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_9_name = $rw['username'];
												$approver_9_role = $rw['primary_role'];

												$sql = " SELECT * FROM sma_role  WHERE 1 AND id = '$approver_9_role' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_9_role = $rw['role'];

												?>
												<div class="col-sm-2">
													<label class="control-label">Approver 9</label><BR>
													<label
														class="control-label1"><?= $approver_9_name . " <BR> " ; ?>
													</label>
												</div>
											<?php
											}
											if (!empty($approver_10)) {
												$sql = " SELECT a.* FROM sma_user a WHERE 1 AND a.id = '$approver_10' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_10_name = $rw['username'];
												$approver_10_role = $rw['primary_role'];

												$sql = " SELECT * FROM sma_role  WHERE 1 AND id = '$approver_10_role' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_10_role = $rw['role'];

												?>
												<div class="col-sm-2">
													<label class="control-label">Approver 10</label><BR>
													<label
														class="control-label1"><?= $approver_10_name . " <BR> " ; ?>
													</label>
												</div>
											<?php
											}
											
											if (!empty($approver_11)) {
												$sql = " SELECT a.* FROM sma_user a WHERE 1 AND a.id = '$approver_11' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_11_name = $rw['username'];
												$approver_11_role = $rw['primary_role'];

												$sql = " SELECT * FROM sma_role  WHERE 1 AND id = '$approver_11_role' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_11_role = $rw['role'];

												?>
												<div class="col-sm-2">
													<label class="control-label">Approver 11</label><BR>
													<label
														class="control-label1"><?= $approver_11_name . " <BR> " ; ?>
													</label>
												</div>
											<?php
											}
											if (!empty($approver_12)) {
												$sql = " SELECT a.* FROM sma_user a WHERE 1 AND a.id = '$approver_12' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_12_name = $rw['username'];
												$approver_12_role = $rw['primary_role'];

												$sql = " SELECT * FROM sma_role  WHERE 1 AND id = '$approver_12_role' ";
												$rs = mysqli_query($con, $sql);
												$rw = mysqli_fetch_array($rs);
												$approver_12_role = $rw['role'];

											?>
												<div class="col-sm-2">
													<label class="control-label">Approver 12</label><BR>
													<label
														class="control-label1"><?= $approver_12_name . " <BR> " ; ?>
													</label>
												</div>
											<?php
											}
											?>
										</div>
										