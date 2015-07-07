<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_records_list; ?></h3>
      </div>
      <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left"><?php echo $column_record_id?></td>
                  <td class="text-left"><?php echo $column_date_processed?></td>
                  <td class="text-left"><?php echo $column_status?></td>
                  <td class="text-left"><?php echo $column_reject_reason?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($records) { ?>
                <?php foreach ($records as $record) { ?>
                <tr>
                  <td class="text-left"><?php echo $record['record_id']; ?></td>
                  <td class="text-left"><?php echo $record['date_processed']; ?></td>
                  <td class="text-left">
                  	<?php 
                  		if ($record['status'] == 1) { echo $entry_status_1;}
						if ($record['status'] == 2) { echo $entry_status_2;}
						if ($record['status'] == 3) { echo $entry_status_3;}
						if ($record['status'] == 4) { echo $entry_status_4;}
                  	?>
                  </td>
                  <td class="text-left"><?php echo $record['reject_reason']; ?></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="10"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
      </div>
    </div>
  </div>

</div>
<?php echo $footer; ?> 