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
			<td><?= join_multi_select($item->barang_id, 'barang', 'id_barang', 'nama_barang');?></td>
			<td><?= $item->stok_posko_total.'&nbsp;'.join_multi_select(join_multi_select($item->barang_id, 'barang', 'id_barang', 'satuan'), 'satuan', 'id_satuan', 'nama_satuan');?></td>
		</tr>
	<?php
		}
	?>
	</tbody>
</table>