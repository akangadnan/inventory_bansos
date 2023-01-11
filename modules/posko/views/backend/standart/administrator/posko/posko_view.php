<?php
	$kontak_pj = db_get_all_data('user_kontak', ['user_id' => $posko->posko_penanggung_jawab]);

	$pj = [];
	foreach ($kontak_pj as $item) {
		$nokon[] = $item->user_kontak_name;
	}

	$kontak_penannggung_jawab = implode(', ', $nokon);

	$kontak_pic = db_get_all_data('user_kontak', ['user_id' => $posko->posko_pic]);

	$pic = [];
	foreach ($kontak_pic as $item) {
		$pic[] = $item->user_kontak_name;
	}

	$kontak_pc = implode(', ', $pic);
?>

<script src="<?= BASE_ASSET; ?>/js/jquery.hotkeys.js"></script>
<script type="text/javascript">
	//This page is a result of an autogenerated content made by running test.html with firefox.
	function domo() {
		// Binding keys
		$('*').bind('keydown', 'Ctrl+e', function assets() {
			$('#btn_edit').trigger('click');
			return false;
		});

		$('*').bind('keydown', 'Ctrl+x', function assets() {
			$('#btn_back').trigger('click');
			return false;
		});
	}

	jQuery(document).ready(domo);
</script>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Posko <small><?= cclang('detail', ['Posko']); ?> </small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class=""><a href="<?= site_url('administrator/posko'); ?>">Posko</a></li>
		<li class="active"><?= cclang('detail'); ?></li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-body">
					<div class="box box-widget widget-user-2">
						<div class="widget-user-header">
							<div class="widget-user-image">
								<img class="img-circle" src="<?= BASE_ASSET; ?>img/view.png" alt="User Avatar">
							</div>
							<h3 class="widget-user-username">Posko</h3>
							<h5 class="widget-user-desc">Detail Posko</h5>
							<hr>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="box box-primary">
									<div class="box-header"><h3 class="box-title">Detail Posko</h3></div>
									<div class="box-body">
										<table class="table table-striped">
											<tr>
												<td>ID</td>
												<td>:</td>
												<td><?= _ent($posko->posko_id);?></td>
											</tr>
											<tr>
												<td>Nama Posko</td>
												<td>:</td>
												<td><?= _ent($posko->posko_nama);?></td>
											</tr>
											<tr>
												<td>Alamat Posko</td>
												<td>:</td>
												<td><?= _ent($posko->posko_alamat);?></td>
											</tr>
											<tr>
												<td>Kecamatan</td>
												<td>:</td>
												<td><?= _ent($posko->kecamatan_nama);?></td>
											</tr>
											<tr>
												<td>Kelurahan</td>
												<td>:</td>
												<td><?= _ent($posko->kelurahan_nama);?></td>
											</tr>
											<tr>
												<td>Nama Penanggung Jawab</td>
												<td>:</td>
												<td><?= _ent($posko->nama_lengkap_penanggung_jawab);?></td>
											</tr>
											<tr>
												<td>Nomor Kontak Penanggung Jawab</td>
												<td>:</td>
												<td><?= _ent($kontak_penannggung_jawab);?></td>
											</tr>
											<tr>
												<td>Nama PIC</td>
												<td>:</td>
												<td><?= _ent($posko->nama_lengkap_pic);?></td>
											</tr>
											<tr>
												<td>Nomor Kontak PIC</td>
												<td>:</td>
												<td><?= _ent($kontak_pc);?></td>
											</tr>
										</table>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="box box-primary">
									<div class="box-header"><h3 class="box-title">Detail Layanan Posko</h3></div>
									<div class="box-body">
										<table class="table table-striped">
											<tr>
												<td>Jenis Layanan</td>
												<td>PIC</td>
												<td>Nomor Kontak</td>
											</tr>
											<tr>
												<td>Nomor Kontak PIC</td>
												<td>:</td>
												<td><?= _ent($kontak_pc);?></td>
											</tr>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--/box body -->
			</div>
			<!--/box -->
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-body">
					<?php is_allowed('posko_update', function() use ($posko){?>
					<a class="btn btn-flat btn-info btn_edit btn_action" id="btn_edit" data-stype='back' title="edit posko (Ctrl+e)" href="<?= site_url('administrator/posko/edit/'.$posko->posko_id); ?>">
						<i class="fa fa-edit"></i> <?= cclang('update', ['Posko']); ?> </a>
					<?php }) ?>
					<a class="btn btn-flat btn-default btn_action" id="btn_back" title="back (Ctrl+x)" href="<?= site_url('administrator/posko/'); ?>">
						<i class="fa fa-undo"></i> <?= cclang('go_list_button', ['Posko']); ?></a>
				</div>
			</div>
		</div>
	</div>
</section>

<script>
	$(document).ready(function () {
		(function () {
			var posko_nama = $('.detail_group-posko-nama');
		})()

	});
</script>