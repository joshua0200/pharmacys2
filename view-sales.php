<?php include 'db_connect.php';

if (isset($_GET['id'])) {
	$sales_data = $conn->query("SELECT s.*, u.name FROM sales s INNER JOIN users u ON s.user_id = u.id where s.id=" . $_GET['id']);
	if ($sales_data->num_rows > 0) {
		$sales_data = $sales_data->fetch_assoc();
		$sales_data = $conn->query("SELECT s.*, u.name FROM sales s INNER JOIN users u ON s.user_id = u.id where s.id=" . $_GET['id'])->fetch_assoc();
		$qry = $conn->query("SELECT s.*, p.name, p.description  FROM sales_items s INNER JOIN inventory i ON s.inventory_id = i.id INNER JOIN product_list p ON i.product_id = p.id where s.sales_id=" . $_GET['id']);
	} else {
		echo "<script>
	window.location.href = '?page=sales_list';
</script>";
	}
} else {
	echo "<script>
	window.location.href = '?page=sales_list';
</script>";
}

?>

<div class="container-fluid">
	<div class="col-lg-12">
		<div class="card mt-3">
			<div class="card-header">
				<a href="?page=sales_list" class="btn btn-success float-right"><i class="fas fa-undo mr-2"></i>Back</a>
				<button type="button" class="btn btn-primary float-right mr-2" onclick="printSales(<?= $sales_data['id'] ?>)"><i class="fas fa-print mr-2"></i>Print</button>
				<h4><b>Sales List / <span class="text-dark">Ref #: <?= $sales_data['ref_no'] ?></span></b></h4>
			</div>
			<div class="card-body">
				<div class="h6 font-weight-bold mb-1">
					<b>Tender/Cashier:</b>
				</div>
				<div class="h5 mb-3 text-dark">
					<?= $sales_data['name']  ?>
				</div>
				<div class="h6 font-weight-bold mb-1">
					<b>Date:</b>
				</div>
				<div class="h5 mb-3 text-dark">
					<?= date('F d, Y', strtotime($sales_data['date_updated']))  ?>
				</div>
				<div class="h6 font-weight-bold mb-1">
					<b>Time:</b>
				</div>
				<div class="h5 text-dark mb-3">
					<?= date('h:i A', strtotime($sales_data['date_updated']))  ?>
				</div>
				<div class="col-md-12">


					<div class="row">
						<table class="table table-bordered" id="list">
							<colgroup>
								<col width="30%">
								<col width="10%">
								<col width="25%">
								<col width="25%">
							</colgroup>
							<thead>
								<tr>
									<th class="text-center">Product</th>
									<th class="text-center">Qty</th>
									<th class="text-center">Price</th>
									<th class="text-center">Amount</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if ($qry->num_rows > 0) :
									$total = 0;
									while ($row = $qry->fetch_assoc()) :

								?>
										<tr class="item-row">
											<td>
												<p class="pname">Name: <b><?php echo $row['name'] ?></b></p>
												<p class="pdesc"><small><i>Description: <b><?php echo $row['description'] ?></b></i></small></p>
											</td>
											<td>
												<p class="text-center"><?php echo $row['qty'] ?></p>

											</td>
											<td>
												<p class="text-right"><?php echo $row['price'] ?></p>
											</td>
											<td>
												<p class="amount text-right"><?php echo $row['amount'] ?></p>
											</td>
										</tr>
									<?php
										$total += $row['amount'];
									endwhile; ?>
								<?php endif; ?>
							</tbody>
							<tfoot>
								<tr>
									<th class="text-right" colspan="3">Total</th>
									<th class="text-right tamount">₱ <?= $total ?></th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>

			</div>

		</div>
	</div>
</div>
<style type="text/css">
	#tr_clone {
		display: none;
	}

	td {
		vertical-align: middle;
	}

	td p {
		margin: unset;
	}

	td input[type='number'] {
		height: calc(100%);
		width: calc(100%);

	}

	input[type=number]::-webkit-inner-spin-button,
	input[type=number]::-webkit-outer-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}
</style>

<script>
	function printSales(id) {
		var nw = window.open("print_sales.php?id=" + id, "_blank", "height=700,width=900")
		nw.print()
		setTimeout(function() {
			nw.close()
			location.reload()
		}, 700)
	}
</script>