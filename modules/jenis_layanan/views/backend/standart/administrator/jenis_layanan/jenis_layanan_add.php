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
	/* .group-jenis_layanan_nama */
	.group-jenis_layanan_nama {}

	.group-jenis_layanan_nama .control-label {}

	.group-jenis_layanan_nama .col-sm-8 {}

	.group-jenis_layanan_nama .form-control {}

	.group-jenis_layanan_nama .help-block {}

	/* end .group-jenis_layanan_nama */



	/* .group-jenis_layanan_created_at */
	.group-jenis_layanan_created_at {}

	.group-jenis_layanan_created_at .control-label {}

	.group-jenis_layanan_created_at .col-sm-8 {}

	.group-jenis_layanan_created_at .form-control {}

	.group-jenis_layanan_created_at .help-block {}

	/* end .group-jenis_layanan_created_at */



	/* .group-jenis_layanan_user_created */
	.group-jenis_layanan_user_created {}

	.group-jenis_layanan_user_created .control-label {}

	.group-jenis_layanan_user_created .col-sm-8 {}

	.group-jenis_layanan_user_created .form-control {}

	.group-jenis_layanan_user_created .help-block {}

	/* end .group-jenis_layanan_user_created */
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Jenis Layanan <small><?= cclang('new', ['Jenis Layanan']); ?> </small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class=""><a href="<?= site_url('administrator/jenis_layanan'); ?>">Jenis Layanan</a></li>
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
							<h3 class="widget-user-username">Jenis Layanan</h3>
							<h5 class="widget-user-desc"><?= cclang('new', ['Jenis Layanan']); ?></h5>
							<hr>
						</div>
						<?= form_open('', [
							'name' => 'form_jenis_layanan',
							'class' => 'form-horizontal form-step',
							'id' => 'form_jenis_layanan',
							'enctype' => 'multipart/form-data',
							'method' => 'POST'
						]); ?>
						<?php
						$user_groups = $this->model_group->get_user_group_ids();
						?>
						<div class="form-group group-jenis_layanan_nama ">
							<label for="jenis_layanan_nama" class="col-sm-2 control-label">Nama Layanan <i class="required">*</i>
							</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="jenis_layanan_nama" id="jenis_layanan_nama" placeholder="Nama Layanan" value="<?= set_value('jenis_layanan_nama'); ?>">
								<small class="info help-block"><b>Input Jenis Layanan Nama</b> Max Length : 255.</small>
							</div>
						</div>

						<div class="message"></div>
						<div class="row-fluid col-md-7 container-button-bottom">
							<button class="btn btn-flat btn-primary btn_save btn_action" id="btn_save" data-stype='stay'
								title="<?= cclang('save_button'); ?> (Ctrl+s)">
								<i class="fa fa-save"></i> <?= cclang('save_button'); ?>
							</button>
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
			var jenis_layanan_nama = $('#jenis_layanan_nama');
			/* 
	jenis_layanan_nama.on('change', function() {});
	*/
			var jenis_layanan_created_at = $('#jenis_layanan_created_at');
			var jenis_layanan_user_created = $('#jenis_layanan_user_created');

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
						window.location.href = BASE_URL + 'administrator/jenis_layanan';
					}
				});

			return false;
		}); /*end btn cancel*/

		$('.btn_save').click(function () {
			$('.message').fadeOut();

			var form_jenis_layanan = $('#form_jenis_layanan');
			var data_post = form_jenis_layanan.serializeArray();
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
					url: BASE_URL + '/administrator/jenis_layanan/add_save',
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