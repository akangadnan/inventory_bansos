<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.6/dist/loadingoverlay.min.js">
</script>

<script src="<?= BASE_ASSET; ?>/js/jquery.hotkeys.js"></script>
<script type="text/javascript">
	function domo() {
		// Binding keys
		$('*').bind('keydown', 'Ctrl+s', function assets() {
			$('#btn_save').trigger('click');
			return false;
		});

		$('*').bind('keydown', 'Ctrl+x', function assets() {
			$('#btn_cancel').trigger('click');
			return false;
		});

		$('*').bind('keydown', 'Ctrl+d', function assets() {
			$('.btn_save_back').trigger('click');
			return false;
		});
	}

	jQuery(document).ready(domo);
</script>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Posko <small>Edit Posko</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class=""><a href="<?= site_url('administrator/posko'); ?>">Posko</a></li>
		<li class="active">Edit</li>
	</ol>
</section>

<style>
	/* .group-posko-nama */
	.group-posko-nama {}

	.group-posko-nama .control-label {}

	.group-posko-nama .col-sm-8 {}

	.group-posko-nama .form-control {}

	.group-posko-nama .help-block {}

	/* end .group-posko-nama */
</style>
<!-- Main content -->
<section class="content">
<?= form_open(base_url('administrator/posko/edit_save/'.$this->uri->segment(4)), [
		'name' 		=> 'form_posko',
		// 'class' 	=> 'form-horizontal form-step',
		'id' 		=> 'form_posko',
		'method' 	=> 'POST'
	]);

	$user_groups = $this->model_group->get_user_group_ids();
?>
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-body ">
					<!-- Widget: user widget style 1 -->
					<div class="box box-widget widget-user-2">
						<!-- Add the bg color to the header using any of the bg-* classes -->
						<div class="widget-user-header ">
							<div class="widget-user-image">
								<img class="img-circle" src="<?= BASE_ASSET; ?>/img/add2.png" alt="User Avatar">
							</div>
							<!-- /.widget-user-image -->
							<h3 class="widget-user-username">Posko</h3>
							<h5 class="widget-user-desc">Edit Posko</h5>
							<hr>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group group-posko-nama  ">
									<label for="posko_nama" class="control-label">Nama Posko <i class="required">*</i></label>
									<input type="text" class="form-control" name="posko_nama" id="posko_nama" placeholder="" value="<?= set_value('posko_nama', $posko->posko_nama); ?>">
									<small class="info help-block"><b>Input Posko Nama</b> Max Length : 255.</small>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group group-posko-alamat">
									<label for="posko_alamat" class="control-label">Alamat Posko <i class="required">*</i></label>
									<input type="text" class="form-control" name="posko_alamat" id="posko_alamat" placeholder="Alamat Posko" value="<?= set_value('posko_alamat', $posko->posko_alamat); ?>">
									<small class="info help-block"></small>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group group-kecamatan-id">
									<label for="kecamatan_id" class="control-label">Kecamatan <i class="required">*</i></label>
									<select class="form-control chosen chosen-select-deselect" name="kecamatan_id" id="kecamatan_id" data-placeholder="Pilih Kecamatan">
										<option value=""></option>
										<?php
											$conditions = [
												];
											?>
										<?php foreach (db_get_all_data('kecamatan', $conditions) as $row): ?>
										<option <?= $row->kecamatan_id == $posko->kecamatan_id ? 'selected' : ''; ?> value="<?= $row->kecamatan_id ?>"><?= $row->kecamatan_nama; ?></option>
										<?php endforeach; ?>
									</select>
									<small class="info help-block"></small>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group group-kelurahan-id">
									<label for="kelurahan_id" class="control-label">Kelurahan <i class="required">*</i></label>
									<select class="form-control chosen chosen-select-deselect" name="kelurahan_id" id="kelurahan_id" data-placeholder="Pilih Kelurahan">
										<option value=""></option>
									</select>
									<small class="info help-block"></small>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group group-posko_penanggung_jawab">
									<label for="posko_penanggung_jawab" class="control-label">Penanggung Jawab Posko</label>
									<select class="form-control chosen chosen-select-deselect" name="posko_penanggung_jawab" id="posko_penanggung_jawab" data-placeholder="Pilih Nama Penanggung Jawab Posko">
										<option value=""></option>
										<?php
											$conditions = [];
											?>
										<?php foreach (db_get_all_data('users', $conditions) as $row): ?>
										<option <?= $row->user_id == $posko->posko_penanggung_jawab ? 'selected' : ''; ?> value="<?= $row->user_id ?>"><?= $row->user_nama_lengkap; ?></option>
										<?php endforeach; ?>
									</select>
									<small class="info help-block"></small>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group group-posko_pic">
									<label for="posko_pic" class="control-label">PIC Posko </label>
									<select class="form-control chosen chosen-select-deselect" name="posko_pic" id="posko_pic" data-placeholder="Pilih Nama PIC Posko">
										<option value=""></option>
										<?php
											$conditions = [];
											?>
										<?php foreach (db_get_all_data('users', $conditions) as $row): ?>
										<option <?= $row->user_id == $posko->posko_pic ? 'selected' : ''; ?>
											value="<?= $row->user_id ?>"><?= $row->user_nama_lengkap; ?></option>
										<?php endforeach; ?>
									</select>
									<small class="info help-block"></small>
								</div>
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-header"><h3 class="box-title">Jenis Layanan Posko</h3></div>
								<div class="box-body">
									<div class="row">
										<div class="col-md-12"><a href="javascript:void(0);" id="addRow" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambahkan Layanan</a></div>
									</div>
									<div class="row">
										<table class="table table-striped" id="tableJenisLayanan">
											<thead>
												<tr>
													<th>Jenis Layanan</th>
													<th>PIC Layanan</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
										<?php
											$lajens = db_get_all_data('layanan_posko', ['posko_id' => $posko->posko_id]);
										?>
												<tr id="inputFormRow">
													<td>
														<select class="form-control chosen chosen-select-deselect" name="jenis_layanan[]" id="jenis_layanan[]" data-placeholder="Pilih Jenis Layanan Posko">
															<option value=""></option>
														<?php
															foreach (db_get_all_data('jenis_layanan', ['jenis_layanan_id' => $lajens[0]->jenis_layanan_id]) as $row) {
														?>
															<option value="<?= $row->jenis_layanan_id;?>" <?= $lajens[0]->jenis_layanan_id == $row->jenis_layanan_id ? 'selected="selected"' : '';?> ><?= $row->jenis_layanan_nama; ?></option>
														<?php }; ?>
														</select>
													</td>
													<td>
														<select class="form-control chosen chosen-select-deselect" name="pic_layanan[]" id="pic_layanan[]" data-placeholder="Pilih PIC Layanan Posko">
															<option value=""></option>
														<?php
															foreach (db_get_all_data('users', ['user_id' => $lajens[0]->layanan_posko_pic]) as $row) {
														?>
															<option value="<?= $row->user_id ?>" <?= $lajens[0]->layanan_posko_pic == $row->user_id ? 'selected="selected"' : '';?> ><?= $row->user_nama_lengkap; ?></option>
														<?php }; ?>
														</select>
													</td>
													<td>
														&nbsp;
													</td>
												</tr>
												
										<?php
											for ($i=1; $i < count($lajens); $i++) {
										?>
												<tr id="inputFormRow">
													<td>
														<select class="form-control chosen chosen-select-deselect" name="jenis_layanan[]" id="jenis_layanan[]" data-placeholder="Pilih Jenis Layanan Posko">
															<option value=""></option>
														<?php
															foreach (db_get_all_data('jenis_layanan', ['jenis_layanan_id' => $lajens[$i]->jenis_layanan_id]) as $row) {
														?>
															<option value="<?= $row->jenis_layanan_id;?>" <?= $lajens[$i]->jenis_layanan_id == $row->jenis_layanan_id ? 'selected="selected"' : '';?> ><?= $row->jenis_layanan_nama; ?></option>
														<?php }; ?>
														</select>
													</td>
													<td>
														<select class="form-control chosen chosen-select-deselect" name="pic_layanan[]" id="pic_layanan[]" data-placeholder="Pilih PIC Layanan Posko">
															<option value=""></option>
														<?php
															foreach (db_get_all_data('users', ['user_id' => $lajens[$i]->layanan_posko_pic]) as $row) {
														?>
															<option value="<?= $row->user_id ?>" <?= $lajens[$i]->layanan_posko_pic == $row->user_id ? 'selected="selected"' : '';?> ><?= $row->user_nama_lengkap; ?></option>
														<?php }; ?>
														</select>
													</td>
													<td>
														<a href="javascript:void(0);" id="removeRow" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
													</td>
												</tr>
										<?php
											}
										?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="message"></div>
				</div>
				<!--/box body -->
				<div class="box-footer">
					<button class="btn btn-flat btn-primary btn_save btn_action" id="btn_save" data-stype='stay'
						title="<?= cclang('save_button'); ?> (Ctrl+s)">
						<i class="fa fa-save"></i> <?= cclang('save_button'); ?>
					</button>
					<a class="btn btn-flat btn-info btn_save btn_action btn_save_back" id="btn_save"
						data-stype='back' title="<?= cclang('save_and_go_the_list_button'); ?> (Ctrl+d)">
						<i class="ion ion-ios-list-outline"></i> <?= cclang('save_and_go_the_list_button'); ?>
					</a>
					<a class="btn btn-flat btn-default btn_action" id="btn_cancel"
						title="<?= cclang('cancel_button'); ?> (Ctrl+x)">
						<i class="fa fa-undo"></i> <?= cclang('cancel_button'); ?>
					</a>
					<span class="loading loading-hide">
						<img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg">
						<i><?= cclang('loading_saving_data'); ?></i>
					</span>
				</div>
			</div>
			<!--/box -->
		</div>
	</div>
	<?= form_close(); ?>
</section>
<!-- /.content -->
<!-- Page script -->
<script>
	$(document).ready(function () {
		window.event_submit_and_action = '';

		(function () {
			var posko_nama = $('#posko_nama');
			/* 
	posko_nama.on('change', function() {});
	*/

		})()

		$("#addRow").on('click', function () {
			var html = '';
			html += '<tr id="inputFormRow">';
			html += '<td><select class="form-control chosen chosen-select-deselect" name="jenis_layanan[]" id="jenis_layanan[]" data-placeholder="Pilih Jenis Layanan Posko"><option value="">- Pilih Layanan -</option>';
			<?php
				$conditions = [];
				foreach (db_get_all_data('jenis_layanan', $conditions) as $row) {
			?>
				html += '<option value="<?= $row->jenis_layanan_id ?>"><?= $row->jenis_layanan_nama; ?></option>';
			<?php }; ?>
			html +=	'</select></td>';
			html += '<td><select class="form-control chosen chosen-select-deselect" name="pic_layanan[]" id="pic_layanan[]" data-placeholder="Pilih PIC Layanan Posko"><option value="">- Pilih PIC Layanan -</option>';
			<?php
				$conditions = [];
				foreach (db_get_all_data('users', $conditions) as $row) {
			?>
				html += '<option value="<?= $row->user_id ?>"><?= $row->user_nama_lengkap; ?></option>';
			<?php }; ?>
			html +=	'</select></td>';
			html +=	'<td><a href="javascript:void(0);" id="removeRow" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></a></td>';
			html += '</tr>';

			$('#tableJenisLayanan tr:last').after(html);
		});

		$(document).on('click', '#removeRow', function () {
			$(this).closest('#inputFormRow').remove();
		});

		$('#btn_cancel').click(function () {
			swal({
				title: "Are you sure?",
				text: "the data that you have created will be in the exhaust!",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes!",
				cancelButtonText: "No!",
				closeOnConfirm: true,
				closeOnCancel: true
			},
			function (isConfirm) {
				if (isConfirm) {
					window.location.href = BASE_URL + 'administrator/posko';
				}
			});

			return false;
		}); /*end btn cancel*/

		$('.btn_save').click(function () {
			$('.message').fadeOut();

			var form_posko = $('#form_posko');
			var data_post = form_posko.serializeArray();
			var save_type = $(this).attr('data-stype');
			data_post.push({
				name: 'save_type',
				value: save_type
			});

			(function () {
				data_post.push({
					name: '_example',
					value: 'value_of_example',
				})
			})()

			data_post.push({
				name: 'event_submit_and_action',
				value: window.event_submit_and_action
			});

			$('.loading').show();

			$.ajax({
				url: form_posko.attr('action'),
				type: 'POST',
				dataType: 'json',
				data: data_post,
			})
			.done(function (res) {
				$('form').find('.form-group').removeClass('has-error');
				$('form').find('.error-input').remove();
				$('.steps li').removeClass('error');
				if (res.success) {
					var id = $('#posko_image_galery').find('li').attr('qq-file-id');
					if (save_type == 'back') {
						window.location.href = res.redirect;
						return;
					}

					$('.message').printMessage({
						message: res.message
					});
					$('.message').fadeIn();
					$('.data_file_uuid').val('');

				} else {
					if (res.errors) {
						parseErrorField(res.errors);
					}
					$('.message').printMessage({
						message: res.message,
						type: 'warning'
					});
				}
			})
			.fail(function () {
				$('.message').printMessage({
					message: 'Error save data',
					type: 'warning'
				});
			})
			.always(function () {
				$('.loading').hide();
				$('html, body').animate({
					scrollTop: $(document).height()
				}, 2000);
			});

			return false;
		}); /*end btn save*/

		function chained_kelurahan_id(selected, complete) {
			var val = $('#kecamatan_id').val();
			$.LoadingOverlay('show')
			return $.ajax({
				url: BASE_URL + 'administrator/posko/ajax_kelurahan_id/' + val,
				dataType: 'JSON',
			})
			.done(function (res) {
				var html = '<option value=""></option>';
				$.each(res, function (index, val) {
					html += '<option ' + (selected == val.kelurahan_id ? 'selected' : '') + ' value="' + val.kelurahan_id + '">' + val.kelurahan_nama + '</option>'
				});
				$('#kelurahan_id').html(html);
				$('#kelurahan_id').trigger('chosen:updated');
				if (typeof complete != 'undefined') {
					complete();
				}
			})
			.fail(function () {
				toastr['error']('Error', 'Getting data fail')
			})
			.always(function () {
				$.LoadingOverlay('hide')
			});
		}


		$('#kecamatan_id').change(function (event) {
			chained_kelurahan_id('')
		});

		async function chain() {
			await chained_kelurahan_id("<?= $posko->kelurahan_id;?>");
		}

		chain();

	}); /*end doc ready*/
</script>