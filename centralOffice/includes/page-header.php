
<div class="page-header">
    <h1>
        Welcome 
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            <?php echo ucwords($user_detail->name . " - Central Office"); ?>
            <?php
            $assign_company_array = explode(',', $user_detail->company_id);
            foreach ($assign_company_array as $assign_company) {
                echo ' | ';
                echo getCompanyNameById($assign_company);
            }
            ?>
        </small>
    </h1>
</div><!-- /.page-header -->