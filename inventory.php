<?php include 'db_connect.php' ?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-md-12">
				<div class="card my-3">
					<div class="card-header">
						<h4><b>Inventory</b></h4>
					</div>
					<div class="card-body">
						<table class="table table-bordered" id="inventory">
							<thead>
								<th class="text-center">#</th>
								<th class="text-center">Product Name</th>
								<th class="text-center">Price</th>
								<th class="text-center">Batch</th>
								<th class="text-center">Expiration</th>
								<th class="text-center">Stock Available</th>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$product = $conn->query("select p.id, p.name, p.measurement, p.price, sum(i.qty) as total from product_list p right join inventory i ON p.id = i.product_id WHERE i.expiry_date > '" . date('Y-m-d') . "' group by i.product_id");
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
									<tr class="bg-light">
										<td class="text-center"><?php echo $i++ ?></td>
										<td class="font-weight-bold"><?php echo $row['name'] ?> <sup><?php echo $row['measurement'] ?></sup> </td>
										<td>
											<span class="float-right">₱ <?= number_format($row['price'], 2) ?></span>
										</td>
										<td></td>
										<td></td>
										<td class="text-right font-weight-bold"><?php echo $str . $row['total'] ?></td>
									</tr>
									<?php
									while ($row2 = $inv->fetch_assoc()) {
									?>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td>
												<div class="h7 text-center">#<?= $row2['batch_no'] ?></div>
											</td>
											<td class="text-center h7"><?php echo date('F d, Y', strtotime($row2['expiry_date'])) ?></td>
											<td class="text-right h7"><?php echo $row2['qty'] ?></td>
										</tr>
									<?php
									}
									?>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
				<hr>
				<div class="card">
					<div class="card-header">
						<h4><b>Expired Products</b></h4>
					</div>
					<div class="card-body">
						<table class="table table-bordered" id="expired">
							<thead>
								<th class="text-center">#</th>
								<th class="text-center">Batch No</th>
								<th class="text-center">Product Name</th>
								<th class="text-center">Quantity</th>
								<th class="text-center">Date Expired</th>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$product = $conn->query("SELECT * FROM inventory i INNER JOIN product_list p ON i.product_id = p.id WHERE expiry_date <= '" . date('y-m-d') . "' order by date(expiry_date) desc");
								while ($row = $product->fetch_assoc()) :

								?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>
										<td class="text-center"><?php echo $row['batch_no'] ?></td>
										<td class=""><?php echo $row['name'] ?> <sup><?php echo $row['measurement'] ?></sup> </td>
										<td class="text-center"><?php echo $row['qty'] ?></td>
										<td class="text-center"><?php echo date('F d, Y', strtotime($row['expiry_date'])) ?></td>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('#inventory').dataTable({
			"ordering": false
		});
		$('#expired').dataTable({});
	})
	console.log('test');
	$('#new_receiving').click(function() {
		location.href = "index.php?page=manage_receiving"
	})
	$('.delete_receiving').click(function() {
		_conf("Are you sure to delete this data?", "delete_receiving", [$(this).attr('data-id')])
	})

	function delete_receiving($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_receiving',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)
				}
			}
		})
	}
</script>