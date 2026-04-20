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
									<h3 class="modal-title pt-1 text-uppercase">BDM Sale Entry Form.</h3>
									<a href="bdm_sale_plan_report.php?menu=1" class="btn btn-warning btn-xs" style="float:right;margin-top:-5%;" target="_blank">Report</a>
								</div>
								<div class="body">
									<form class="form-horizontal" id="searchform" name="myForm" role="form" action="" method="POST" enctype="multipart/form-data">
										<div class="form-group" id="">
											<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Select Item<span style="color:red">*</span> : </label>
											<div class="col-sm-6">
												<select class="form-control col-xs-10 col-sm-5 chosen-select" name="item_code" id="item_code" required="">
													<option value="">-- Select  --</option>
												<?php foreach(getActiveItemsList() as $itemar){ ?>
													<option value="<?php echo $itemar->item_code; ?>"><?php echo $itemar->item_desc; ?>
													</option>
												<?php } ?>
												</select>
											</div>
										</div>
										<div class="form-group" id="">
											<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Market Rate<span style="color:red">*</span> : </label>
											<div class="col-sm-6">
												<input type="text" name="market_rate" placeholder="Market Rate" id="market_rate" class="form-control" required="required"/>
											</div>
										</div>
										<div class="form-group" id="">
											<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Expected Demand<span style="color:red">*</span> : </label>
											<div class="col-sm-6">
												<input type="text" name="expacted_demand" placeholder="Enter Here...." id="expacted_demand" class="form-control" required="required" value=""/>
											</div>
										</div>
										<div class="clearfix form-actions">
											<div class="col-md-offset-3 col-md-9">
												<button type="submit" name="submit" class="btn btn-info"><i class="ace-icon fa fa-check bigger-110"></i>  Submit
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
								$market_rate = $_POST['market_rate'];
								$expacted_demand = $_POST['expacted_demand'];
								$date = date('Y-m-d');
								$date_time = date('Y-m-d H:i:s');
								
								$item_data = getInventoryDataByCode($item_code);

								$data = array();
								$data['bdm_id'] = $bdm_id;
								$data['item_code'] = $item_code;
								$data['item_name'] = $item_data->item_desc;
								$data['brand'] = $item_data->brand_name;
								$data['main_category'] = $item_data->main_category_id;
								$data['sub_category'] = $item_data->sub_category_id;
								$data['unit'] = $item_data->unit;
								$data['market_rate_data'] = $market_rate;
								$data['expected_demand'] = $expacted_demand;
								$data['added_date'] = $date;
								$data['status'] = 0;
								$data['added_by'] = $_SESSION['name'];


								$table_name = 'bdm_sale_plan_tbl';

                                    
                                        $insert = insert($table_name, $data);
                                        if ($insert) {
                                            print '<script>window.location="bdm_sale_plan_entry_form.php?menu=1&success=1";</script>';
                                            exit;
                                        } else {
                                            print '<script>window.location="bdm_sale_plan_entry_form.php?menu=1&error=1";</script>';
                                            exit;
                                        }
								}
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
