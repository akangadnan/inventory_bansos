<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.6/dist/loadingoverlay.min.js"></script>
<section class="content-header">
	<h1>
		Barang Masuk <small>Edit Barang Masuk</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class=""><a href="<?= site_url('administrator/barangmasuk'); ?>">Barang Masuk</a></li>
		<li class="active">Edit</li>
	</ol>
</section>

<style>
	/* .group-asal */
	.group-asal {}

	.group-asal .control-label {}

	.group-asal .col-sm-8 {}

	.group-asal .form-control {}

	.group-asal .help-block {}

	/* end .group-asal */



	/* .group-id-barang */
	.group-id-barang {}

	.group-id-barang .control-label {}

	.group-id-barang .col-sm-8 {}

	.group-id-barang .form-control {}

	.group-id-barang .help-block {}

	/* end .group-id-barang */



	/* .group-jumlah */
	.group-jumlah {}

	.group-jumlah .control-label {}

	.group-jumlah .col-sm-8 {}

	.group-jumlah .form-control {}

	.group-jumlah .help-block {}

	/* end .group-jumlah */



	/* .group-keterangan */
	.group-keterangan {}

	.group-keterangan .control-label {}

	.group-keterangan .col-sm-8 {}

	.group-keterangan .form-control {}

	.group-keterangan .help-block {}

	/* end .group-keterangan */
</style>
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
							<h5 class="widget-user-desc">Edit Barang Masuk</h5>
							<hr>
						</div>
						<?= form_open(base_url('administrator/barangmasuk/edit_save/'.$this->uri->segment(4)), [
							'name' => 'form_barangmasuk',
							'class' => 'form-horizontal form-step',
							'id' => 'form_barangmasuk',
							'method' => 'POST'
						]); ?>

						<?php
						$user_groups = $this->model_group->get_user_group_ids();
						?>



						<div class="form-group group-asal">
							<label for="asal" class="col-sm-2 control-label">Asal Posko<i class="required">*</i></label>
							<div class="col-sm-8">
								<select class="form-control chosen chosen-select-deselect" name="asal_posko" id="asal_posko" data-placeholder="Select Asal">
									<option value=""></option>
									<?php
										$conditions = [
											];
										?>
									<?php foreach (db_get_all_data('posko', $conditions) as $row): ?>
									<option <?= $row->posko_id == $barangmasuk->asal_posko ? 'selected' : ''; ?> value="<?= $row->posko_id ?>"><?= $row->posko_nama; ?></option>
									<?php endforeach; ?>
								</select>
								<small class="info help-block">
								</small>
							</div>
						</div>

						<div class="form-group group-asal ">
	                            <label for="asal" class="col-sm-2 control-label">Nama Donatur <i class="required">*</i>
	                            </label>
	                            <!-- <div class="col-sm-8">
									<select class="form-control chosen chosen-select-deselect" name="asal" id="asal"
										data-placeholder="Select Asal">
										<option value=""></option>
										<?php
										$conditions = [
											];
										?>

										<?php foreach (db_get_all_data('sumber', $conditions) as $row): ?>
										<option value="<?= $row->id_sumber ?>"><?= $row->nama_sumber; ?></option>
										<?php endforeach; ?>
									</select>
									<small class="info help-block">
									</small>
								</div> -->
	                            <div class="col-sm-8">
	                                <input type="text" class="form-control" name="nama_donatur" id="nama_donatur"
	                                    placeholder="Nama Donatur" value="<?= set_value('nama_donatur', $barangmasuk->nama_donatur); ?>">
	                            </div>
	                        </div>
	                        <div class="form-group group-asal ">
	                            <label for="asal" class="col-sm-2 control-label">Alamat Donatur <i class="required">*</i>
	                            </label>
	                            <div class="col-sm-8">
	                                <input type="text" class="form-control" name="alamat_donatur" id="alamat_donatur"
	                                    placeholder="Alamat Donatur" value="<?= set_value('alamat_donatur', $barangmasuk->alamat_donatur); ?>">
	                            </div>
	                        </div>
	                        <div class="form-group group-asal ">
	                            <label for="asal" class="col-sm-2 control-label"> No telepon Donatur<i
	                                    class="required">*</i>
	                            </label>

	                            <div class="col-sm-8">
	                                <input type="number" class="form-control" name="phone_donatur" id="phone_donatur"
	                                    placeholder="No telepon Donatur" value="<?= set_value('phone_donatur', $barangmasuk->phone_donatur); ?>">
	                            </div>
	                        </div>

						<!-- <div class="form-group group-asal">
							<label for="asal" class="col-sm-2 control-label">Asal <i class="required">*</i>
							</label>
							<div class="col-sm-8">
								<select class="form-control chosen chosen-select-deselect" name="asal" id="asal"
									data-placeholder="Select Asal">
									<option value=""></option>
									<?php
										$conditions = [
											];
										?>
									<?php foreach (db_get_all_data('sumber', $conditions) as $row): ?>
									<option <?= $row->id_sumber == $barangmasuk->asal ? 'selected' : ''; ?>
										value="<?= $row->id_sumber ?>"><?= $row->nama_sumber; ?></option>
									<?php endforeach; ?>
								</select>
								<small class="info help-block">
								</small>
							</div>
						</div> -->






						<!-- <div class="form-group group-id-barang">
							<label for="id_barang" class="col-sm-2 control-label">Nama Barang <i class="required">*</i>
							</label>
							<div class="col-sm-8">
								<select class="form-control chosen chosen-select-deselect" name="id_barang"
									id="id_barang" data-placeholder="Select Nama Barang">
									<option value=""></option>
								</select>
								<small class="info help-block">
								</small>
							</div>
						</div> -->





						<div class="form-group group-jumlah  ">
							<label for="jumlah" class="col-sm-2 control-label">Jumlah Stok <i class="required">*</i>
							</label>
							<div class="col-sm-8">
								<input type="number" class="form-control" name="jumlah" id="jumlah" placeholder=""
									value="<?= set_value('jumlah', $barangmasuk->jumlah); ?>">
								<small class="info help-block">
									<b>Input Jumlah</b> Max Length : 12.</small>
							</div>
						</div>




						<div class="form-group group-keterangan  ">
							<label for="keterangan" class="col-sm-2 control-label">Keterangan </label>
							<div class="col-sm-8">
								<textarea placeholder="Keterangan" id="keterangan" name="keterangan" rows="5"
									class="textarea form-control"><?= set_value('keterangan', $barangmasuk->keterangan); ?></textarea>
								<small class="info help-block">
								</small>
							</div>
						</div>




						<div class="form-group group-tanggal  ">
							<label for="tanggal" class="col-sm-2 control-label">Tanggal <i class="required">*</i>
							</label>
							<div class="col-sm-6">
								<div class="input-group date col-sm-8">
									<input type="text" class="form-control pull-right datepicker" name="tanggal"
										placeholder="" id="tanggal"
										value="<?= set_value('barangmasuk_tanggal_name', $barangmasuk->tanggal); ?>">
								</div>
								<small class="info help-block">
								</small>
							</div>
						</div>





						<div class="form-group group-waktu  ">
							<label for="waktu" class="col-sm-2 control-label">Waktu <i class="required">*</i>
							</label>
							<div class="col-sm-6">
								<div class="input-group date col-sm-8">
									<input type="text" class="form-control pull-right timepicker" name="waktu"
										id="waktu"
										value="<?= set_value('barangmasuk_waktu_name', $barangmasuk->waktu); ?>">
								</div>
								<small class="info help-block">
								</small>
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
	                                            <div class="col-md-12"><a href="javascript:void(0);" id="addRow"
	                                                    class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah
	                                                    Barang</a></div>
	                                        </div>
	                                        <div class="row">
	                                            <table class="table table-striped" id="tableJenisLayanan">
	                                                <thead>
	                                                    <tr>
	                                                        <th>Nama Barang</th>
	                                                        <th>Jumlah Barang</th>
	                                                        <th>Action</th>
	                                                    </tr>
	                                                </thead>
	                                                <tbody>
	                                                    <tr id="inputFormRow">
	                                                        <td>
	                                                                <select
	                                                                    class="form-control chosen chosen-select-deselect"
	                                                                    name="id_barang[]" id="id_barang0"
	                                                                    data-placeholder="Select Nama Barang">
	                                                                    <option value=""></option>
	                                                                </select>
	                                                                <small class="info help-block">
	                                                                </small>
	                                                        </td>
	                                                        <td>
	                                                            <input type="number" class="form-control" name="jumlah[]" id="jumlah[]"
																	placeholder="Jumlah Stok" value="<?= set_value('jumlah'); ?>">
																<small class="info help-block">
																	<b>Input Jumlah</b> Max Length : 12.</small>
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
							<!-- <button class="btn btn-flat btn-primary btn_save btn_action" id="btn_save" data-stype='stay'
								title="<?= cclang('save_button'); ?> (Ctrl+s)">
								<i class="fa fa-save"></i> <?= cclang('save_button'); ?>
							</button> -->
							<a class="btn btn-flat btn-info btn_save btn_action btn_save_back" id="btn_save"
								data-stype='back' title="<?= cclang('save_and_go_the_list_button'); ?> (Ctrl+d)">
								<i class="ion ion-ios-list-outline"></i> <?= cclang('save_and_go_the_list_button'); ?>
							</a>

							<div class="custom-button-wrapper">

							</div>
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
<script>
	$(document).ready(function () {
		window.event_submit_and_action = '';

		(function () {
			var asal = $('#asal');
			/* 
	asal.on('change', function() {});
	*/
			var id_barang = $('#id_barang');
			var jumlah = $('#jumlah');
			var keterangan = $('#keterangan');

		})()



		$("#addRow").on('click', function () {
			var html = '';
			html += '<tr id="inputFormRow">';
			html += '<td><select class="form-control chosen chosen-select-deselect" name="id_barang[]" id="id_barang1 data-placeholder="Select Nama Barang"><option value=""></option>';
			<?php
				$conditions = [];
				foreach (db_get_all_data('barang', $conditions) as $row) {
			?>
				html += '<option value="<?= $row->id_barang ?>"><?= $row->nama_barang; ?>( <?= $row->stok; ?>) </option>';
			<?php }; ?>
			html +=	'</select><small class="info help-block"></small></td>';
			html += '<td><input type="number" class="form-control" name="jumlah[]" id="jumlah[]" placeholder="Jumlah Stok" value="<?= set_value('jumlah'); ?>"><small class="info help-block"><b>Input Jumlah</b> Max Length : 12.</small>';
			html +=	'</td>';
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
					url: form_barangmasuk.attr('action'),
					type: 'POST',
					dataType: 'json',
					data: data_post,
				})
				.done(function (res) {
					$('form').find('.form-group').removeClass('has-error');
					$('form').find('.error-input').remove();
					$('.steps li').removeClass('error');
					if (res.success) {
						var id = $('#barangmasuk_image_galery').find('li').attr('qq-file-id');
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





		function chained_id_barang(selected, complete) {
			var val = $('#id_barang').val();
			$.LoadingOverlay('show')
			return $.ajax({
					url: BASE_URL + '/administrator/barangmasuk/ajax_barang/' + val,
					dataType: 'JSON',
				})
				.done(function (res) {
					var html = '<option value=""></option>';
					$.each(res, function (index, val) {
						html += '<option ' + (selected == val.id_barang ? 'selected' : '') + ' value="' + val.id_barang + '">' + val.nama_barang+' ('+val.stok+' '+val.nama_satuan+')' + '</option>'
					});
					$('#id_barang').html(html);
					$('#id_barang').trigger('chosen:updated');
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

		async function chain() {
			await chained_id_barang("<?= $barangmasuk->id_barang ?>");
		}

		chain();




	}); /*end doc ready*/
</script>