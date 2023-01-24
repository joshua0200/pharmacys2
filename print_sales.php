<?php include 'db_connect.php';

if (isset($_GET['id'])) {
	$sales_data = $conn->query("SELECT s.*, u.name FROM sales s INNER JOIN users u ON s.user_id = u.id where s.id=" . $_GET['id']);
	if ($sales_data->num_rows > 0) {
		$sales_data = $sales_data->fetch_assoc();
		$sales_data = $conn->query("SELECT s.*, u.name FROM sales s INNER JOIN users u ON s.user_id = u.id where s.id=" . $_GET['id'])->fetch_assoc();
		$qry = $conn->query("SELECT s.*, p.name, p.description , p.measurement FROM sales_items s INNER JOIN inventory i ON s.inventory_id = i.id INNER JOIN product_list p ON i.product_id = p.id where s.sales_id=" . $_GET['id']);
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
<div class="container-fluid" id="print-sales">
	<style>
		body * {
			font-size: 10px
		}

		table {
			border-collapse: collapse;
		}

		.bbottom {
			border-bottom: 1px solid black
		}

		td p,
		th p {
			margin: unset
		}

		.text-center {
			text-align: center
		}

		.text-right {
			text-align: right
		}

		.text-left {
			text-align: left
		}

		.clear {
			padding: 10px
		}

		#uni_modal .modal-footer {
			display: none;
		}
	</style>
	<table width="100%">

		<tr>
			<th class="text-center">
				<p><b>Med-ICT</b></p>
				<p>
					<b>Unofficial Receipt</b>
				</p>
			</th>
		</tr>
		<tr>
			<td class="clear">&nbsp;</td>
		</tr>

		<tr>
			<td class="clear">&nbsp;</td>
		</tr>
		<tr>
			<table width="100%">
				<tr>
					<th width="5%" class="wborder">Qty</th>
					<th width="30%" class="wborder text-left">Product</th>
					<th width="10%" class="wborder"></th>
					<th width="25%" class="wborder">Amount</th>
				</tr>
				<?php
				if ($qry->num_rows > 0) :
					$total = 0;
					while ($row = $qry->fetch_assoc()) :


				?>
						<tr>
							<td class="wborder text-center">
								<?php echo $row['qty'] ?>
							</td>
							<td class="wborder">
								<?php echo $row['name'] ?><sup><?php echo $row['measurement'] ?></sup> <span><small><?php echo $row['qty'] > 1 ? "(" . (number_format($row['price'], 2)) . ")" : "" ?></small></span>

							</td>
							<td class="wborder text-right"></td>
							<td class="wborder text-right"><?php echo number_format($row['price'] * $row['qty'], 2) ?></td>

						</tr>
					<?php
						$total += $row['amount'];
					endwhile; ?>
				<?php endif; ?>
				<tr>
					<th class="text-right wborder" colspan="3">Total</th>
					<th class="text-right wborder"><?php echo number_format($sales_data['total_amount'], 2) ?></th>
				</tr>
				<tr>
					<th class="text-right wborder" colspan="3">Amount Tendered</th>
					<th class="text-right wborder"><?php echo number_format($sales_data['amount_tendered'], 2) ?></th>
				</tr>
				<tr>
					<th class="text-right wborder" colspan="3">Change</th>
					<th class="text-right wborder"><?php echo number_format($sales_data['amount_change'], 2) ?></th>
				</tr>
			</table>
		</tr>
		<tr>
			<td class="clear">&nbsp;</td>
		</tr>
		<tr>
			<td>
				<table width="100%">
					<tr>
						<td width="20%" class="text-right">Tender/Cashier:</td>
						<td width="40%" class="bbottom"><?php echo $sales_data['name'] ?></td>
						<td width="20%" class="text-right">Date :</td>
						<td width="20%" class="bbottom"><?php echo date("Y-m-d", strtotime($sales_data['date_updated'])) ?></td>
					</tr>
					<tr>
						<td width="20%" class="text-right">Reference Number :</td>
						<td width="80%" class="bbottom" colspan="3"><?php echo $sales_data['ref_no'] ?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>


</div>