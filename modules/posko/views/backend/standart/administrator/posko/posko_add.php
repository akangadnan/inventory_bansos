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
<style>
	/* .group-posko-nama */
	.group-posko-nama {}

	.group-posko-nama .control-label {}

	.group-posko-nama .col-sm-8 {}

	.group-posko-nama .form-control {}

	.group-posko-nama .help-block {}

	/* end .group-posko-nama */
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Posko <small><?= cclang('new', ['Posko']); ?> </small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class=""><a href="<?= site_url('administrator/posko'); ?>">Posko</a></li>
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
							<h3 class="widget-user-username">Posko</h3>
							<h5 class="widget-user-desc"><?= cclang('new', ['Posko']); ?></h5>
							<hr>
						</div>
						<?= form_open('', [
						'name' => 'form_posko',
						'class' => 'form-horizontal form-step',
						'id' => 'form_posko',
						'enctype' => 'multipart/form-data',
						'method' => 'POST'
					]); ?>
						<?php
					$user_groups = $this->model_group->get_user_group_ids();
					?>
						<div class="form-group group-kecamatan-id ">
							<label for="kecamatan_id" class="col-sm-2 control-label">Kecamatan <i class="required">*</i></label>
							<div class="col-sm-8">
								<select class="form-control chosen chosen-select-deselect" name="kecamatan_id" id="kecamatan_id" data-placeholder="Pilih Kecamatan">
									<option value=""></option>
							<?php
								$conditions = [];
								
								foreach (db_get_all_data('kecamatan', $conditions) as $row): ?>
									<option value="<?= $row->kecamatan_id ?>"><?= $row->kecamatan_nama; ?></option>
									<?php endforeach; ?>
								</select>
								<small class="info help-block">
								</small>
							</div>
						</div>

						<div class="form-group group-kelurahan-id ">
							<label for="kelurahan_id" class="col-sm-2 control-label">Kelurahan <i class="required">*</i></label>
							<div class="col-sm-8">
								<select class="form-control chosen chosen-select-deselect" name="kelurahan_id" id="kelurahan_id" data-placeholder="Pilih Kelurahan">
									<option value=""></option>
								</select>
								<small class="info help-block"></small>
							</div>
						</div>

						<div class="form-group group-posko-nama ">
							<label for="posko_nama" class="col-sm-2 control-label">Nama Posko <i class="required">*</i></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="posko_nama" id="posko_nama" placeholder="Nama Posko" value="<?= set_value('posko_nama'); ?>">
								<small class="info help-block"><b>Input Posko Nama</b> Max Length : 255.</small>
							</div>
						</div>

						<div class="form-group group-posko_penanggung_jawab ">
							<label for="posko_penanggung_jawab" class="col-sm-2 control-label">Penanggung Jawab Posko </label>
							<div class="col-sm-8">
								<select class="form-control chosen chosen-select-deselect" name="posko_penanggung_jawab" id="posko_penanggung_jawab" data-placeholder="Pilih Nama Penanggung Jawab Posko">
									<option value=""></option>
							<?php
								$conditions = [];

								foreach (db_get_all_data('users', $conditions) as $row): ?>
									<option value="<?= $row->user_id ?>"><?= $row->user_nama_lengkap; ?></option>
									<?php endforeach; ?>
								</select>
								<small class="info help-block"></small>
							</div>
						</div>

						<div class="form-group group-posko_pic ">
							<label for="posko_pic" class="col-sm-2 control-label">PIC Posko </label>
							<div class="col-sm-8">
								<select class="form-control chosen chosen-select-deselect" name="posko_pic" id="posko_pic" data-placeholder="Pilih Nama PIC Posko">
									<option value=""></option>
									<?php
									$conditions = [
										];
									?>

									<?php foreach (db_get_all_data('users', $conditions) as $row): ?>
									<option value="<?= $row->user_id ?>"><?= $row->user_nama_lengkap; ?></option>
									<?php endforeach; ?>
								</select>
								<small class="info help-block">
								</small>
							</div>
						</div>

						<div class="message"></div>
						<div class="row-fluid col-md-7 container-button-bottom">
							<button class="btn btn-flat btn-primary btn_save btn_action" id="btn_save"
								data-stype='stay' title="<?= cclang('save_button'); ?> (Ctrl+s)">
								<i class="fa fa-save"></i> <?= cclang('save_button'); ?>
							</button>
							<a class="btn btn-flat btn-info btn_save btn_action btn_save_back" id="btn_save"
								data-stype='back' title="<?= cclang('save_and_go_the_list_button'); ?> (Ctrl+d)">
								<i class="ion ion-ios-list-outline"></i>
								<?= cclang('save_and_go_the_list_button'); ?>
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
			var posko_nama = $('#posko_nama');
			/* 
				posko_nama.on('change', function() {});
				*/

		})()






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
					url: BASE_URL + '/administrator/posko/add_save',
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
									$('.steps li:eq(' + index + ')').addClass('error')
										.find('a').trigger('click');
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
					url: BASE_URL + '/administrator/posko/ajax_kelurahan_id/' + val,
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




	}); /*end doc ready*/
</script>