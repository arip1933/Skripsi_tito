<style>
	@media print {
		@page {
			size: 10.267in 10.5in;
			margin: 0;
		}

		.header-pt {
			font-weight: bold;
		}
	}

	.tbl-resi {
		font-size: 11px;
	}

	.table-wrapper {
		border: 1px solid gray;
		border-top: 4px solid gray;
		height: 580px;
		padding-top: 5px;
	}

	.border-bottom {
		border-bottom: 1px solid gray;
	}

	.border-top {
		border-top: 1px solid gray;
	}

	.border-left {
		border-left: 1px solid gray;
	}

	.img-qrcode {
		position: absolute;
		top: 0;
		right: 0;
	}

	.img-logo {
		position: absolute;
		top: 10px;
		left: 20px;
	}
</style>
<div class="content-wrapper print resi">
	<table>
		<tr>
			<td width='450' align="center" valign='top'>
				<img class="img-logo" src="<?php echo base_url("assets/images") . "/logo.jpg"; ?>" width="100" height="90" />
				<div class='header-pt'>PT. HONGKONG RAYA PRIMA</div>
				<div class='header-address'>Jl. Pegangsaan Dua</div>
				<div class="header-address">Taman Pegangsaan Indah</div>
				<div class='header-address'>blok B 18 & 19, Kelapa Gading</div>
				<div class='header-address'>Jakarta Utara 14250, Indonesia</div>
			</td>
			<td valign='top'>
				<img class="img-qrcode" src="<?php echo base_url("export") . "/" . $data->id_pengiriman . ".png" ?>" width="90" height="90" />
				<div>
					Jakarta, <?php echo date('d M Y', strtotime($data->tanggal)); ?>
				</div>
				<div class='mt10'>
					KEPADA Yth.
				</div>
				<div>
					<?php echo $data->pelanggan; ?>
				</div>
				<div class='mt10'>
					<?php echo $data->alamat; ?>
				</div>
			</td>
		</tr>
		<tr>
			<td rowspan="2">
				<div class='header-pt'>SURAT JALAN No. <?php echo $data->id_pengiriman; ?></div>
				<div class="mb10">Harap diterima dengan baik barang. Dibawah ini</div>
			</td>
		<tr>
	</table>
	<div class='table-wrapper'>
		<table style="width:100%" style="mt10" cellpadding='5' cellspacing='0'>
			<tr>
				<th class="border-bottom border-top " height="10">No</th>
				<th class="border-bottom border-top">Kode Barang</th>
				<th class="border-bottom border-top">Nama Barang</th>
				<!-- <th class="border-bottom border-top">Del No</th> -->
				<th class="border-bottom border-top">QTY</th>
				<th class="border-bottom border-top">Satuan</th>
				<!-- <th class="border-bottom border-top">Status</th> -->



			</tr>
			<tbody>
				<?php if ($data->barang != null) : ?>					
					<?php $barang = $this->pengiriman_model->get_barang_by_id_pengiriman($data->id_pengiriman); ?>
					<?php $i = 1; ?>
					<?php foreach ($barang as $br) : ?>
						<tr class="tbl-resi">
							<td align="center" height="10"><?php echo $i; ?></td>
							<td align="center"><?php echo $br['id_barang']; ?></td>
							<td align="center"><?php echo $br['nama']; ?></td>
							<td align="center"><?php echo $br['qty']; ?></td>
							<td align="center"><?php echo $br['satuan']; ?></td>
							
						</tr>
						<?php $i++; ?>
					<?php endforeach ?>

				<?php endif ?>
			</tbody>
		</table>
	</div>
	<table style="width:100%">
		<tr>
			<td valign='top' style="width:55%">
				<div class='mt10'>
					Kendaraan No. <?php echo $data->no_kendaraan; ?>
				</div>
				<div class='mt10'>
					PO No. <?php echo $data->no_po; ?>
				</div>
			</td>
			<td valign='top' style="width:30%">
				<div class='mt10'>
					Diterima Oleh:
				</div>
			</td>
			<td valign='top' style="width:15%">
				<div class='mt10'>
					Terima Kasih
					<br>
					<br>
					<br>
					<br> Hormat Kami
				</div>
			</td>
		</tr>
	</table>

</div>
<script>
	$(function() {
		window.print();
	});
</script>