<?php include 'db_connect.php' ?>
<style>
	table.table-hover tr {
		cursor: pointer !important;
	}

	#uni_modal .modal-footer {
		display: none;
	}

	#uni_modal .modal-footer.display {
		display: block;
	}

	.item-click:hover .hand {
		display: inline-block !important;
	}

	.item-click:hover {
		background-color: lightcyan !important;
	}
</style>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<div class="form-group col-md-4 ofsset-md-4">
				<small>Seach for product</small>
				<input type="text" class="input-sm form-control" id="search">
			</div>
		</div>
		<div class="row">
			<table class="table table-condensed table-hover table-bordered" id="plist">
				<colgroup>
					<col width="31%">
					<col width="16%">
					<col width="21%">

					<col width="16%">
					<col width="16%">
				</colgroup>
				<thead>
					<th class="">Product Name</th>
					<th class="text-center">Need Prescription</th>
					<th class="">Price</th>
					<th class="text-center">Batch<br><span class="h7 font-weight-light">Select below</span></th>
					<th class="text-center">Available</th>

				</thead>
				<tbody>
					<?php
					$i = 1;
					$product = $conn->query("select p.id, p.prescription, p.name, p.price, p.measurement, sum(i.qty) as total, c.name as cat from product_list p right join inventory i ON p.id = i.product_id INNER JOIN category_list c ON p.category_id = c.id WHERE i.expiry_date > '" . date('Y-m-d') . "' group by i.product_id");
					while ($row = $product->fetch_assoc()) :
						$inv = $conn->query("SELECT * FROM inventory WHERE expiry_date > '" . date('Y-m-d') . "' AND qty > 0 AND product_id = " . $row['id']);
						$inv_count = $inv->num_rows;
					?>
						<?php
						$str = '';
						if ($row['total'] == 0) {
							$str = '<span class="badge bg-danger text-white mr-3"><i class="fas fa-exclamation-circle"></i> Out of stock</span>';
						} elseif ($row['total'] <= 200) {
							$str = '<span class="badge bg-danger text-white mr-3"><i class="fas fa-exclamation-circle"></i> Low</span>';
						} else {
						}
						?>
						<tr class="bg-light item" data-prod="<?= $row['name'] ?>" style="cursor: default !important;">

							<td class="font-weight-bold"><?php echo $row['name'] ?> <sup><?php echo $row['measurement'] ?></sup><br><span class="h7"><?= $row['cat'] ?></span></td>
							<td class="text-center"><?= ($row['prescription']) ? '<i class="fas fa-check-circle text-primary"></i>' : '<i class="far fa-check-circle text-info"></i>' ?></td>
							<td class="text-right">₱ <?= number_format($row['price'], 2) ?></td>
							<td></td>
							<td></td>

						</tr>

						<?php
						while ($row2 = $inv->fetch_assoc()) {
						?>
							<tr class="item item-click" data-id="<?= $row2['id'] ?>" data-prod="<?= $row['name'] ?>">

								<td colspan="3" class="text-right"><i class="fas fa-arrow-circle-right hand" style="display: none;"></i></td>
								<td>
									<div class="h6 text-center">#<?= $row2['batch_no'] ?></div>
								</td>

								<!-- <td class="text-right h7"><?php echo date('F d, Y', strtotime($row2['expiry_date'])) ?></td> -->
								<td class="text-center h6"><?php echo $row2['qty'] ?></td>

							</tr>
						<?php
						}

						?>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="modal-footer display">
	<div class="col-lg-12">
		<button class="btn btn-secondary float-right" type="button" data-dismiss="modal">Close</button>
	</div>
</div>
<script>
	$('#search').keyup(function() {
		var txt = $(this).val()
		$('#plist tbody tr.item').each(function() {
			if ($(this).attr('data-prod').toLowerCase().includes(txt.toLowerCase()) == true) {
				$(this).toggle(true)
			} else {
				$(this).toggle(false)
			}
		})
	})
	$('#plist tbody tr.item-click').click(function() {
		select_prod($(this).attr('data-id'))
	})
	$(document).ready(function() {
		$('#search').trigger('click')
	})
</script>