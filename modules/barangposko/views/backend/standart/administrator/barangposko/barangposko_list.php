<script src="<?= BASE_ASSET; ?>/js/jquery.hotkeys.js"></script>

<script type="text/javascript">
//This page is a result of an autogenerated content made by running test.html with firefox.
function domo() {

    // Binding keys
    $('*').bind('keydown', 'Ctrl+a', function assets() {
        window.location.href = BASE_URL + '/administrator/Barang/add';
        return false;
    });

    $('*').bind('keydown', 'Ctrl+f', function assets() {
        $('#sbtn').trigger('click');
        return false;
    });

    $('*').bind('keydown', 'Ctrl+x', function assets() {
        $('#reset').trigger('click');
        return false;
    });

    $('*').bind('keydown', 'Ctrl+b', function assets() {

        $('#reset').trigger('click');
        return false;
    });
}

jQuery(document).ready(domo);
</script>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?= cclang('Stok Posko') ?><small><?= cclang('list_all'); ?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?= cclang('Stok Posko') ?></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">

        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body ">
                    <!-- Widget: user widget style 1 -->
                    <div class="box box-widget widget-user-2">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header ">
                            <div class="row pull-right">
                                <!-- <?php is_allowed('barang_add', function(){?>
								<a class="btn btn-flat btn-success btn_add_new" id="btn_add_new"
									title="<?= cclang('add_new_button', [cclang('Stok Posko')]); ?>  (Ctrl+a)"
									href="<?=  site_url('administrator/barang/add'); ?>"><i
										class="fa fa-plus-square-o"></i>
									<?= cclang('add_new_button', [cclang('Stok Posko')]); ?></a>
								<?php }) ?> -->
                                <?php is_allowed('barang_export', function(){?>
                                <a class="btn btn-flat btn-success"
                                    title="<?= cclang('export'); ?> <?= cclang('Stok Posko') ?> "
                                    href="<?= site_url('administrator/barang/export?q='.$this->input->get('q').'&f='.$this->input->get('f')); ?>"><i
                                        class="fa fa-file-excel-o"></i> <?= cclang('export'); ?> XLS</a>
                                <?php }) ?>
                            </div>
                            <div class="widget-user-image">
                                <img class="img-circle" src="<?= BASE_ASSET; ?>/img/list.png" alt="User Avatar">
                            </div>
                            <!-- /.widget-user-image -->
                            <h3 class="widget-user-username"><?= cclang('Stok Posko') ?></h3>
                            <h5 class="widget-user-desc"><?= cclang('list_all', [cclang('Stok Posko')]); ?> <i
                                    class="label bg-yellow"><?= $barang_counts; ?> <?= cclang('items'); ?></i></h5>
                        </div>

                        <div class="padd-left-0 ">
                            <select class="form-control chosen chosen-select" name="posko" id="posko">
                                <option value="">Pilih Posko</option>
                                <?php foreach (db_get_all_data('posko') as $row): ?>
                                <option value="<?= $row->posko_id ?>"><?= $row->posko_nama; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
						<table id="example" class="display" style="width:100%">
							<thead>
								<tr>
									<th>Nama Barang</th>
									<th>Stok</th>
									<th>action</th>
								</tr>
							</thead>
							<tbody id="hasilposko">
								
							</tbody>
						</table>

              
                <!--/box body -->
            </div>
            <!--/box -->
        </div>
    </div>
</section>
<!-- /.content -->

<!-- Page script -->

<script>
$(document).ready(function() {
	$('#posko').change(function() {
        var idposko = $(this).val();
		// console.log("<?php echo site_url('administrator/barangposko/getdata');?>");
		// return;

        // console.log(idposko);

        $.ajax({
            url: "<?php echo site_url('administrator/barangposko/getdata');?>",
            method: "GET",
            data: {
                id: idposko
            },
            dataType: 'json',
            success: function(data) {

				let html = '' ;
				const target = $('#hasilposko');
                $.each(data.getdata, function(key, val) {
                    $.each(function(key, val) {
                        html += '<tr>'+
									'<td>'+val.id_barang+'</td>' +
									'<td>'+val.jumlah+'</td>' +
									'<td>Lihat</td'>
								'</tr>'
						
		           });
                })
                target.append(html);

            }
        });
        return false;
    });




    $('.remove-data').click(function() {

        var url = $(this).attr('data-href');

        swal({
                title: "<?= cclang('are_you_sure'); ?>",
                text: "<?= cclang('data_to_be_deleted_can_not_be_restored'); ?>",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "<?= cclang('yes_delete_it'); ?>",
                cancelButtonText: "<?= cclang('no_cancel_plx'); ?>",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    document.location.href = url;
                }
            });

        return false;
    });


    $('#apply').click(function() {

        var bulk = $('#bulk');
        var serialize_bulk = $('#form_barang').serialize();

        if (bulk.val() == 'delete') {
            swal({
                    title: "<?= cclang('are_you_sure'); ?>",
                    text: "<?= cclang('data_to_be_deleted_can_not_be_restored'); ?>",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<?= cclang('yes_delete_it'); ?>",
                    cancelButtonText: "<?= cclang('no_cancel_plx'); ?>",
                    closeOnConfirm: true,
                    closeOnCancel: true
                },
                function(isConfirm) {
                    if (isConfirm) {
                        document.location.href = BASE_URL + '/administrator/barang/delete?' +
                            serialize_bulk;
                    }
                });

            return false;

        } else if (bulk.val() == '') {
            swal({
                title: "Upss",
                text: "<?= cclang('please_choose_bulk_action_first'); ?>",
                type: "warning",
                showCancelButton: false,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Okay!",
                closeOnConfirm: true,
                closeOnCancel: true
            });

            return false;
        }

        return false;

    }); /*end appliy click*/


    //check all
    var checkAll = $('#check_all');
    var checkboxes = $('input.check');

    checkAll.on('ifChecked ifUnchecked', function(event) {
        if (event.type == 'ifChecked') {
            checkboxes.iCheck('check');
        } else {
            checkboxes.iCheck('uncheck');
        }
    });

    checkboxes.on('ifChanged', function(event) {
        if (checkboxes.filter(':checked').length == checkboxes.length) {
            checkAll.prop('checked', 'checked');
        } else {
            checkAll.removeProp('checked');
        }
        checkAll.iCheck('update');
    });
    initSortable('barang', $('table.dataTable'));
}); /*end doc ready*/
</script>