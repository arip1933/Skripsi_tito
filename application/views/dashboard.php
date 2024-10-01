<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<?php echo $title ?>
		</h1>
	</section>
	<section class="content">
	<div style="width: 90%; margin: 0 auto; padding: 20px;">
                <div style="display: flex; flex-wrap: wrap; margin: -10px;">
                    <div style="flex: 1; padding: 10px; min-width: 200px;">
                        <div style="background-color: #FAEBD7; border: 1px solid #dddddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); text-align: center; padding: 20px; margin: 10px;">
                            <h5 style="font-size: 1.2em; margin-bottom: 10px; color: #333333;">Total Barang</h5>
                            <p style="font-size: 2.5em; font-weight: bold; color: #007bff;"><?php echo $barang_count; ?></p>
                        </div>
                    </div>
                    <div style="flex: 1; padding: 10px; min-width: 200px;">
                        <div style="background-color: #7FFFD4; border: 1px solid #dddddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); text-align: center; padding: 20px; margin: 10px;">
                            <h5 style="font-size: 1.2em; margin-bottom: 10px; color: #333333;">Total Pengiriman</h5>
                            <p style="font-size: 2.5em; font-weight: bold; color: #007bff;"><?php echo $detail_pengiriman_count; ?></p>
                        </div>
                    </div>
                    <div style="flex: 1; padding: 10px; min-width: 200px;">
                        <div style="background-color: #90EE90; border: 1px solid #dddddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); text-align: center; padding: 20px; margin: 10px;">
                            <h5 style="font-size: 1.2em; margin-bottom: 10px; color: #333333;">Total Pelanggan</h5>
                            <p style="font-size: 2.5em; font-weight: bold; color: #007bff;"><?php echo $pelanggan_count; ?></p>
                        </div>
                    </div>
                    <div style="flex: 1; padding: 10px; min-width: 200px;">
                        <div style="background-color: #FFD700; border: 1px solid #dddddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); text-align: center; padding: 20px; margin: 10px;">
                            <h5 style="font-size: 1.2em; margin-bottom: 10px; color: #333333;">Total Kurir</h5>
                            <p style="font-size: 2.5em; font-weight: bold; color: #007bff;"><?php echo $kurir_count; ?></p>
                        </div>
                    </div>
                </div>
            </div>
	</section>
</div>