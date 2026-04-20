<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
?>
<!DOCTYPE html>
<html lang="en">
    <?php require_once 'includes/header.php'; ?>
	<body class="no-skin">
        <?php require_once 'includes/menu.php'; ?>
		<div class="main-container ace-save-state" id="main-container">
            <?php require_once 'includes/left_sidebar.php'; ?>
			<div class="main-content">
				<div class="main-content-inner">
                    <?php require_once 'includes/breadcrumbs.php'; ?>
					<div class="page-content">
                        <?php require_once 'includes/page-header.php'; ?>
						<div class="row">
							<div class="col-xs-12">
								<div class="header">
									<h3 class="modal-title pt-1 text-uppercase">BDM Sale Entry Report.</h3>
									<a href="bdm_sale_plan_entry_form.php?menu=1" class="btn btn-warning btn-xs" style="float:right;margin-top:-5%;" target="_blank">Back</a>
								</div>
								<div class="body">
									<form class="form-horizontal" id="searchform" name="myForm" role="form" action="" method="POST" enctype="multipart/form-data">
										<div class="form-group" id="">
											<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Select Item<span style="color:red">*</span> : </label>
											<div class="col-sm-6">
												<select class="form-control col-xs-10 col-sm-5 chosen-select" name="item_code" id="item_code" required="">
													<option value="00">-- Select  --</option>
												<?php foreach(getActiveItemsList() as $itemar){ ?>
													<option value="<?php echo $itemar->item_code; ?>"><?php echo $itemar->item_desc; ?>
													</option>
												<?php } ?>
												</select>
											</div>
										</div>
										<div class="form-group" id="">
											<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">&nbsp; </label>
											<div class="col-sm-6">
												<h4 style="font-weight:bolder;">OR</h4>
											</div>
										</div>
										<div class="form-group" id="">
											<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> From Date<span style="color:red">*</span> : </label>
											<div class="col-sm-6">
												<input type="date" name="from_date" id="from_date" class="form-control" value=<?= date('Y-m-d'); ?> />
                                            </div>
										</div>
										<div class="form-group" id="">
											<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> To Date<span style="color:red">*</span> : </label>
											<div class="col-sm-6">
												<input type="date" name="to_date" id="to_date" class="form-control" value=<?= date('Y-m-d'); ?> />
                                            </div>
										</div>
										<div class="clearfix form-actions">
											<div class="col-md-offset-3 col-md-9">
												<button type="submit" name="submit" class="btn btn-info">
													<i class="ace-icon fa fa-check bigger-110"></i> Show
                                                </button>
                                                &nbsp; &nbsp; &nbsp;
                                            </div>
										</div>
									</form>
								</div>
                                <?php
                                if (isset($_POST['submit'])) {
								$bdm_id = $_SESSION['id'];
								$item_code = $_POST['item_code'];
								$from_date = $_POST['from_date'];
								$to_date = $_POST['to_date'];
								
								$date = date('Y-m-d');
								$date_time = date('Y-m-d H:i:s');
								
								$where = "bdm_id = '".$bdm_id."'";
								if($item_code == '00'){
									$where .= "item_code = '".$item_code."'";
								}else{
									$where .= "date(added_date) between '".$from_date."' and '".$to_date."'";								
								}
								
								$item_data = get_bdm_sale_data_by_whr($where);
?>
									<div class="modal-body">
                                            <div class="row clearfix">
                                                <div class="pull-right tableTools-container"></div>
                                            </div>
                                        </div>
                                    <table id="dynamic-table"  class="table table-bordered">
										<thead>
											<tr>
												<th>SrNo</th>
												<th>AddedDate</th>
												<th>ItemName</th>
												<th>Brand</th>
												<th>Category</th>
												<th>Unit</th>
												<th>Market Rate </th>
												<th>Expected demand</th>
											</tr>
										</thead>
										<tbody>
										<?php 
										$index = 1;
										foreach($item_data as $data1) { ?>
											<tr>
												<td><?php echo $index; ?></td>
												<td><?php echo date('Y-m-d',strtotime($data1->item_name)); ?></td>
												<td><?php echo $data1->item_name; ?></td>
												<td><?php echo $data1->brand; ?></td>
												<td><?php echo $data1->main_category; ?></td>
												<td><?php echo $data1->unit; ?></td>
												<td><?php echo $data1->market_rate_data; ?></td>
												<td><?php echo $data1->expected_demand; ?></td>
											</tr>
										<?php $index++;
										} ?>
										</tbody>
								</table>
                                       
							<?php }
                                ?>
							</div>
						</div>
						<!-- /.row -->
					</div>
					<!-- /.page-content -->
				</div>
			</div>
			<!-- /.main-content -->
			<!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>
		</div>
		<script type="text/javascript">

        </script>
	</body>
</html>
