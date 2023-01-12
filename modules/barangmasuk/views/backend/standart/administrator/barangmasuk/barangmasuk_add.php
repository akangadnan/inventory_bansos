<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.6/dist/loadingoverlay.min.js"></script>

<section class="content-header">
	<h1>
		Barang Masuk <small><?= cclang('new', ['Barang Masuk']); ?> </small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class=""><a href="<?= site_url('administrator/barangmasuk'); ?>">Barang Masuk</a></li>
		<li class="active"><?= cclang('new'); ?></li>
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
							<div class="widget-user-image">
								<img class="img-circle" src="<?= BASE_ASSET; ?>/img/add2.png" alt="User Avatar">
							</div>
							<!-- /.widget-user-image -->
							<h3 class="widget-user-username">Barang Masuk</h3>
							<h5 class="widget-user-desc"><?= cclang('new', ['Barang Masuk']); ?></h5>
							<hr>
						</div>
						<?= form_open('', [
						'name' => 'form_barangmasuk',
						'class' => 'form-horizontal form-step',
						'id' => 'form_barangmasuk',
						'enctype' => 'multipart/form-data',
						'method' => 'POST'
					]); ?>
						<?php
					$user_groups = $this->model_group->get_user_group_ids();
					?>
						<div class="form-group group-asal-posko ">
							<label for="asal" class="col-sm-2 control-label">Asal Posko<i class="required">*</i></label>
							<div class="col-sm-8">
								<select class="form-control chosen chosen-select-deselect" name="asal_posko" id="asal_posko" data-placeholder="Select Asal">
									<option value=""></option>
									<?php
									$conditions = [
										];
									?>

									<?php foreach (db_get_all_data('posko', $conditions) as $row): ?>
									<option value="<?= $row->posko_id ?>"><?= $row->posko_nama; ?></option>
									<?php endforeach; ?>
								</select>
								<small class="info help-block"></small>
							</div>
						</div>
						<div class="form-group group-asal ">
							<label for="asal" class="col-sm-2 control-label">Nama Donatur <i class="required">*</i></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="nama_donatur" id="nama_donatur" placeholder="Nama Donatur" value="<?= set_value('nama_donatur'); ?>">
							</div>
						</div>
						<div class="form-group group-asal ">
							<label for="asal" class="col-sm-2 control-label">Alamat Donatur <i class="required">*</i></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="alamat_donatur" id="alamat_donatur" placeholder="Alamat Donatur" value="<?= set_value('alamat_donatur'); ?>">
							</div>
						</div>
						<div class="form-group group-kecamatan-id ">
							<label for="kecamatan_id" class="col-sm-2 control-label">Kecamatan <i class="required">*</i></label>
							<div class="col-sm-8">
								<select class="form-control chosen chosen-select-deselect" name="kecamatan_id" id="kecamatan_id" data-placeholder="Select Kecamatan">
									<option value=""></option>
									<?php foreach (db_get_all_data('kecamatan') as $row): ?>
									<option value="<?= $row->kecamatan_id ?>"><?= $row->kecamatan_nama; ?></option>
									<?php endforeach; ?>
								</select>
								<small class="info help-block"></small>
							</div>
						</div>
						<div class="form-group group-kelurahan-id ">
							<label for="kelurahan_id" class="col-sm-2 control-label">Kelurahan <i class="required">*</i></label>
							<div class="col-sm-8">
								<select class="form-control chosen chosen-select-deselect" name="kelurahan_id" id="kelurahan_id" data-placeholder="Select Kelurahan">
									<option value=""></option>
								</select>
								<small class="info help-block"></small>
							</div>
						</div>
						<div class="form-group group-asal ">
							<label for="asal" class="col-sm-2 control-label"> No telepon Donatur<i class="required">*</i></label>
							<div class="col-sm-8">
								<input type="number" class="form-control" name="phone_donatur" id="phone_donatur" placeholder="No telepon Donatur" value="<?= set_value('phone_donatur'); ?>">
							</div>
						</div>
						<div class="form-group group-keterangan">
							<label for="keterangan" class="col-sm-2 control-label">Keterangan </label>
							<div class="col-sm-8">
								<input id="keterangan" name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan" value="<?= set_value('keterangan'); ?>"></input>
								<small class="info help-block">
								</small>
							</div>
						</div>
						<div class="form-group group-tanggal">
							<label for="tanggal" class="col-sm-2 control-label">Tanggal <i class="required">*</i></label>
							<div class="col-sm-6">
								<div class="input-group date col-sm-8">
									<input type="text" class="form-control pull-right datepicker" name="tanggal" placeholder="Tanggal" id="tanggal">
								</div>
								<small class="info help-block"></small>
							</div>
						</div>
						<div class="form-group group-waktu">
							<label for="waktu" class="col-sm-2 control-label">Waktu <i class="required">*</i></label>
							<div class="col-sm-6">
								<div class="input-group date col-sm-8">
									<input type="text" class="form-control pull-right timepicker" name="waktu" id="waktu">
								</div>
								<small class="info help-block"></small>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="box box-primary">
									<div class="box-header">
										<h3 class="box-title">Daftar Barang</h3>
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
															</select>
														</td>
														<td>
															<input type="number" class="form-control" name="jumlah[]" id="jumlah[]" placeholder="Masukkan Jumlah Stok">
														</td>
														<td>
															<input type="number" class="form-control" name="keterangan_barang[]" id="keterangan_barang[]" placeholder="Masukkan Keterangan Barang">
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
						<div class="row-fluid col-md-7 container-button-bottom">
							<!-- <button class="btn btn-flat btn-primary btn_save btn_action" id="btn_save" data-stype='stay' title="<?= cclang('save_button'); ?> (Ctrl+s)">
								<i class="fa fa-save"></i> <?= cclang('save_button'); ?>
							</button> -->
							<a class="btn btn-flat btn-info btn_save btn_action btn_save_back" id="btn_save"
								data-stype='back' title="<?= cclang('save_and_go_the_list_button'); ?> (Ctrl+d)">
								<i class="ion ion-ios-list-outline"></i> <?= cclang('save_and_go_the_list_button'); ?>
							</a>

							<div class="custom-button-wrapper"></div>

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
				</div>
				<!--/box body -->
			</div>
			<!--/box -->
		</div>
	</div>
</section>
<!-- /.content -->
<!-- Page script -->

<script type="text/javascript">
	$(document).ready(function () {
		window.event_submit_and_action = '';

		(function () {
			var asal = $('#asal');
			var id_barang = $('#id_barang');
			var jumlah = $('#jumlah');
			var keterangan = $('#keterangan_barang');

		})()

		$("#addRow").on('click', function () {
			var html = '';
			html += '<tr id="inputFormRow">';
			html +=
				'<td><select class="form-control chosen chosen-select-deselect" name="id_barang[]" id="id_barang[]" data-placeholder="Select Nama Barang"><option value="">- Pilih Nama Barang -</option>';
		<?php 
			$conditions = [];
			foreach(db_get_all_data('barang', $conditions) as $row) {
		?>
				html += '<option value="<?= $row->id_barang ?>"><?= $row->nama_barang; ?>( <?= $row->stok; ?>) </option>';
		<?php }; ?>
			html += '</select><small class="info help-block"></small></td>';
			html += '<td><input type="number" class="form-control" name="jumlah[]" id="jumlah[]" placeholder="Jumlah Stok"><small class="info help-block"></small></td>';
			html += '<td><input type="number" class="form-control" name="keterangan_barang[]" id="keterangan_barang[]" placeholder="Masukkan Keterangan Barang"></td>';
			html += '<td><a href="javascript:void(0);" id="removeRow" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></a></td>';
			html += '</tr>';

			$('#tableJenisLayanan tr:last').after(html);
		});

		$(document).on('click', '#removeRow', function () {
			$(this).closest('#inputFormRow').remove();
		});

		$('#btn_cancel').click(function () {
			swal({
				title: "<?= cclang('are_you_sure'); ?>",
				text: "<?= cclang('data_to_be_deleted_can_not_be_restored'); ?>",
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
					window.location.href = BASE_URL + 'administrator/barangmasuk';
				}
			});

			return false;
		}); /*end btn cancel*/

		$('.btn_save').click(function () {
			$('.message').fadeOut();

			var form_barangmasuk = $('#form_barangmasuk');
			var data_post = form_barangmasuk.serializeArray();
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
				url: BASE_URL + '/administrator/barangmasuk/add_save',
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
							$('form #' + index).parents('.form-group').find('small').prepend(`<div class="error-input">` + val + `</div>`);
						});
						$('.steps li').removeClass('error');
						$('.content section').each(function (index, el) {
							if ($(this).find('.has-error').length) {
								$('.steps li:eq(' + index + ')').addClass('error').find('a').trigger('click');
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

		$('#kecamatan_id').change(function (event) {
			var val = $(this).val();
			$.LoadingOverlay('show')
			$.ajax({
				url: BASE_URL + '/administrator/barangmasuk/ajax_kelurahan_id/' + val,
				dataType: 'JSON',
			})
			.done(function (res) {
				var html = '<option value=""></option>';
				$.each(res, function (index, val) {
					html += '<option value="' + val.kelurahan_id + '">' + val
						.kelurahan_nama + '</option>'
				});
				$('#kelurahan_id').html(html);
				$('#kelurahan_id').trigger('chosen:updated');

			})
			.fail(function () {
				toastr['error']('Error', 'Getting data fail')
			})
			.always(function () {
				$.LoadingOverlay('hide')
			});
		});

		function chained_barang(complete) {
			$.LoadingOverlay('show');
			return $.ajax({
				url: BASE_URL + 'administrator/barangmasuk/ajax_barang/',
				dataType: 'JSON',
			})
			.done(function (res) {
				var html = '<option value=""></option>';
				$.each(res, function (index, val) {
					html += '<option value="' + val.id_barang + '">' + val.nama_barang + ' (' + val.stok + ' ' + val.nama_satuan + ')' + '</option>'
				});

				$('#id_barang0').html(html);
				$('#id_barang0').trigger('chosen:updated');

			})
			.fail(function () {
				toastr['error']('Error', 'Getting data fail')
			})
			.always(function () {
				$.LoadingOverlay('hide')
			});

		};

		async function chain() {
			await chained_barang();
		}

		chain();

	}); /*end doc ready*/
</script>