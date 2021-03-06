<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-upload').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-salesman"><?php echo $entry_salesman; ?></label>
                <input type="text" name="filter_salesman" value="<?php echo $filter_salesman; ?>" placeholder="<?php echo $entry_salesman; ?>" id="input-salesman" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-filename"><?php echo $entry_filename; ?></label>
                <input type="text" name="filter_filename" value="<?php echo $filter_filename; ?>" placeholder="<?php echo $entry_filename; ?>" id="input-filename" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-salesman-son"><?php echo $entry_salesman_son; ?></label>
                  <select name="filter_salesman_son" id="input-salesman-son" class="form-control">
                  <?php if ($filter_salesman_son === "1") { ?>
                  <option value="1" selected="selected"><?php echo $text_salesman_son_yes; ?></option>
                  <option value="0" ><?php echo $text_salesman_son_no; ?></option>
                  <?php } else { ?>
                 <option value="1" ><?php echo $text_salesman_son_yes; ?></option>
                  <option value="0" selected="selected"><?php echo $text_salesman_son_no; ?></option>
                  <?php } ?>
                  </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-upload">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'dd.name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'd.filename') { ?>
                    <a href="<?php echo $sort_filename; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_filename; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_filename; ?>"><?php echo $column_filename; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'd.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($uploads) { ?>
                <?php foreach ($uploads as $upload) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($upload['upload_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $upload['upload_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $upload['upload_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $upload['name']; ?></td>
                  <td class="text-left"><?php echo $upload['filename']; ?></td>
                  <td class="text-right"><?php echo $upload['date_added']; ?></td>
                  <td class="text-center"><a href="<?php echo $upload['download']; ?>" data-toggle="tooltip" title="<?php echo $button_download; ?>" class="btn btn-info"><i class="fa fa-download"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=salesman/upload&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_filename = $('input[name=\'filter_filename\']').val();

	if (filter_filename) {
		url += '&filter_filename=' + encodeURIComponent(filter_filename);
	}

	var filter_date_added = $('input[name=\'filter_date_added\']').val();

	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}

	var filter_salesman = $('input[name=\'filter_salesman\']').val();

	if (filter_salesman) {
		url += '&filter_salesman=' + encodeURIComponent(filter_salesman);
	}

	var filter_salesman_son = $('select[name=\'filter_salesman_son\']').val();

	if (filter_salesman_son) {
		url += '&filter_salesman_son=' + encodeURIComponent(filter_salesman_son);
	}
	location = url;
});
//--></script>
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>