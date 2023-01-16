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
<section class="content-header">
	<h1>
		Permohonan <small><?= cclang('new', ['Permohonan']); ?> </small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class=""><a href="<?= site_url('administrator/permohonan'); ?>">Permohonan</a></li>
		<li class="active"><?= cclang('new'); ?></li>
	</ol>
</section>
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
			<?= form_open('', [
					'name' 		=> 'form_permohonan',
					'class' 	=> 'form-horizontal form-step',
					'id' 		=> 'form_permohonan',
					'enctype' 	=> 'multipart/form-data',
					'method' 	=> 'POST'
				]);

				$user_groups = $this->model_group->get_user_group_ids();
			?>
				<div class="box-body">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-widget widget-user-2">
								<!-- Add the bg color to the header using any of the bg-* classes -->
								<div class="widget-user-header">
									<div class="widget-user-image">
										<img class="img-circle" src="<?= BASE_ASSET; ?>/img/add2.png" alt="User Avatar">
									</div>
									<!-- /.widget-user-image -->
									<h3 class="widget-user-username">Permohonan</h3>
									<h5 class="widget-user-desc"><?= cclang('new', ['Permohonan']); ?></h5>
									<hr>
								</div>
								<div class="form-group group-permohonan-tanggal">
									<label for="permohonan_tanggal" class="col-sm-2 control-label">Tanggal Permohonan <i class="required">*</i></label>
									<div class="col-sm-6">
										<div class="input-group date col-sm-8">
											<input type="text" class="form-control pull-right datepicker" name="permohonan_tanggal" placeholder="Tanggal Permohonan" id="permohonan_tanggal">
										</div>
										<small class="info help-block"></small>
									</div>
								</div>
								<div class="form-group group-permohonan-waktu">
									<label for="permohonan_waktu" class="col-sm-2 control-label">Waktu <i class="required">*</i></label>
									<div class="col-sm-6">
										<div class="input-group date col-sm-8">
											<input type="text" class="form-control pull-right timepicker" name="permohonan_waktu" id="permohonan_waktu">
										</div>
										<small class="info help-block"></small>
									</div>
								</div>
								<div class="form-group group-posko-id">
									<label for="posko_id" class="col-sm-2 control-label">Posko <i class="required">*</i></label>
									<div class="col-sm-8">
										<select class="form-control chosen chosen-select-deselect" name="posko_id" id="posko_id" data-placeholder="Select Posko">
											<option value=""></option>
											<?php foreach (db_get_all_data('posko') as $row): ?>
											<option value="<?= $row->posko_id ?>"><?= $row->posko_nama; ?></option>
											<?php endforeach; ?>
										</select>
										<small class="info help-block"></small>
									</div>
								</div>
								<div class="form-group group-permohonan-pemohon">
									<label for="permohonan_pemohon" class="col-sm-2 control-label">Pemohon <i class="required">*</i></label>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="permohonan_pemohon" id="permohonan_pemohon" placeholder="Pemohon" value="<?= set_value('permohonan_pemohon'); ?>">
										<small class="info help-block"></small>
									</div>
								</div>
								<div class="form-group group-permohonan-keterangan">
									<label for="permohonan_keterangan" class="col-sm-2 control-label">Keterangan </label>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="permohonan_keterangan" id="permohonan_keterangan" placeholder="Keterangan" value="<?= set_value('permohonan_keterangan'); ?>">
										<small class="info help-block"></small>
									</div>
								</div>
								<div class="form-group group-permohonan-mengetahui">
									<label for="permohonan_mengetahui" class="col-sm-2 control-label">Mengetahui Posko <i class="required">*</i></label>
									<div class="col-sm-8">
										<select class="form-control chosen chosen-select-deselect" name="permohonan_mengetahui" id="permohonan_mengetahui" data-placeholder="Select Mengetahui Posko">
											<option value=""></option>
											<?php foreach (db_get_all_data('aauth_users') as $row): ?>
											<option value="<?= $row->id ?>"><?= $row->full_name; ?></option>
											<?php endforeach; ?>
										</select>
										<small class="info help-block"></small>
									</div>
								</div>
								<div class="form-group group-permohonan-respon-posko">
									<label for="permohonan_respon_posko" class="col-sm-2 control-label">Keterangan Posko</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="permohonan_respon_posko" id="permohonan_respon_posko" placeholder="Keterangan Posko" value="<?= set_value('permohonan_respon_posko'); ?>">
										<small class="info help-block"></small>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
							<div class="box-header">
									<h3 class="box-title">Daftar Permintaan Bantuan Barang</h3>
								</div>
								<div class="box-body">
									<div class="row">
										<div class="col-md-12">
											<a href="javascript:void(0);" id="addRow" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Barang</a>
										</div>
									</div>
									<div class="row">
										<table class="table table-striped" id="tableJenisLayanan">
											<thead>
												<tr>
													<th>Nama Barang</th>
													<th>Jumlah Barang</th>
													<th>Keterangan Barang</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												<tr id="inputFormRow">
													<td>
														<select class="form-control chosen chosen-select-deselect" name="id_barang[]" id="id_barang0" data-placeholder="Select Nama Barang">
															<option value=""></option>
													<?php foreach(db_get_all_data('barang') as $row) {?>
														<option value="<?= $row->id_barang ?>"><?= $row->nama_barang; ?> ( <?= join_multi_select($row->satuan, 'satuan', 'id_satuan', 'nama_satuan'); ?>) </option>
													<?php }?>
														</select>
													</td>
													<td>
														<input type="number" class="form-control" name="jumlah[]" id="jumlah[]" placeholder="Masukkan Jumlah Stok">
													</td>
													<td>
														<input type="text" class="form-control" name="keterangan_barang[]" id="keterangan_barang[]" placeholder="Masukkan Keterangan Barang">
													</td>
													<td>
														&nbsp;
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="message"></div>
				</div>
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
				<?= form_close(); ?>
			</div>
			<!--/box -->
		</div>
	</div>
</section>
<!-- /.content -->
<!-- Page script -->

<script>
	$(document).ready(function () {

		window.event_submit_and_action = '';

		(function () {
			var permohonan_tanggal = $('#permohonan_tanggal');
			/* 
	permohonan_tanggal.on('change', function() {});
	*/
			var permohonan_waktu = $('#permohonan_waktu');
			var posko_id = $('#posko_id');
			var user_id = $('#user_id');
			var permohonan_keterangan = $('#permohonan_keterangan');
			var permohonan_status = $('#permohonan_status');
			var permohonan_respon_posko = $('#permohonan_respon_posko');
			var permohonan_mengetahui = $('#permohonan_mengetahui');

		})()

		$("#addRow").on('click', function () {
			var html = '';
			html += '<tr id="inputFormRow">';
			html +=
				'<td><select class="form-control chosen chosen-select-deselect" name="id_barang[]" id="id_barang[]" data-placeholder="Select Nama Barang"><option value="">- Pilih Nama Barang -</option>';
		<?php foreach(db_get_all_data('barang') as $row) {?>
				html += '<option value="<?= $row->id_barang ?>"><?= $row->nama_barang; ?> ( <?= join_multi_select($row->satuan, 'satuan', 'id_satuan', 'nama_satuan'); ?>) </option>';
		<?php }; ?>
			html += '</select><small class="info help-block"></small></td>';
			html += '<td><input type="number" class="form-control" name="jumlah[]" id="jumlah[]" placeholder="Jumlah Stok"><small class="info help-block"></small></td>';
			html += '<td><input type="text" class="form-control" name="keterangan_barang[]" id="keterangan_barang[]" placeholder="Masukkan Keterangan Barang"></td>';
			html += '<td><a href="javascript:void(0);" id="removeRow" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></a></td>';
			html += '</tr>';

			$('#tableJenisLayanan tr:last').after(html);
		});

		$(document).on('click', '#removeRow', function () {
			$(this).closest('#inputFormRow').remove();
		});




		$('#btn_cancel').click(function () {
			swal({
					title:"<?= cclang('are_you_sure'); ?>",
					text:"<?= cclang('data_to_be_deleted_can_not_be_restored'); ?>",
					type:"warning",
					showCancelButton: true,
					confirmButtonColor:"#DD6B55",
					confirmButtonText:"Yes!",
					cancelButtonText:"No!",
					closeOnConfirm: true,
					closeOnCancel: true
				},
				function (isConfirm) {
					if (isConfirm) {
						window.location.href = BASE_URL + 'administrator/permohonan';
					}
				});

			return false;
		}); /*end btn cancel*/

		$('.btn_save').click(function () {
			$('.message').fadeOut();

			var form_permohonan = $('#form_permohonan');
			var data_post = form_permohonan.serializeArray();
			var save_type = $(this).attr('data-stype');

			data_post.push({
				name: 'save_type',
				value: save_type
			});

			data_post.push({
				name: 'event_submit_and_action',
				value: window.event_submit_and_action
			});

			(function () {
				data_post.push({
					name: '_example',
					value: 'value_of_example',
				})
			})()


			$('.loading').show();

			$.ajax({
					url: BASE_URL + '/administrator/permohonan/add_save',
					type: 'POST',
					dataType: 'json',
					data: data_post,
				})
				.done(function (res) {
					$('form').find('.form-group').removeClass('has-error');
					$('.steps li').removeClass('error');
					$('form').find('.error-input').remove();
					if (res.success) {

						if (save_type == 'back') {
							window.location.href = res.redirect;
							return;
						}

						$('.message').printMessage({
							message: res.message
						});
						$('.message').fadeIn();
						resetForm();
						$('.chosen option').prop('selected', false).trigger('chosen:updated');

					} else {
						if (res.errors) {

							$.each(res.errors, function (index, val) {
								$('form #' + index).parents('.form-group').addClass(
									'has-error');
								$('form #' + index).parents('.form-group').find('small')
									.prepend(`
					  <div class="error-input">` + val + `</div>
					  `);
							});
							$('.steps li').removeClass('error');
							$('.content section').each(function (index, el) {
								if ($(this).find('.has-error').length) {
									$('.steps li:eq(' + index + ')').addClass('error').find(
										'a').trigger('click');
								}
							});
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
	}); /*end doc ready*/
</script>