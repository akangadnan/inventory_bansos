<table class="table table-bordered table-striped dataTable">
	<thead>
		<tr>
			<th>No.</th>
			<th>Nama Barang</th>
			<th>Jumlah Stok</th>
	</thead>
	<tbody>
		</tr>
	<?php
		$no = 1;
		foreach ($data as $item) {
	?>
		<tr>
			<td><?= $no++;?></td>
			<td><?= $item->nama_barang;?></td>
			<td><?= $item->jumlah_masuk.'&nbsp;'.$item->nama_satuan;?></td>
		</tr>
	<?php
		}
	?>
	</tbody>
</table>