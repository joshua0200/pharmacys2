<?php include 'db_connect.php' ?>

<hr>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
		</div>
		<div class="row">
			<div class="col-md-12 mt-3">
				<div class="card">
					<div class="card-header">
						<h4><b>Sales List</b></h4>

						<button class="col-md-2 float-right btn btn-primary btn-sm" id="new_sales"><i class="fa fa-plus"></i> Add Transaction</button>
					</div>
					<div class="card-body">
						<table class="table table-bordered">
							<thead>
								<th class="text-center">#</th>
								<th class="text-center">Date</th>
								<th class="text-center">Time</th>
								<th class="text-center">Reference #</th>
								<th class="text-center">Customer</th>
								<th class="text-center">Action</th>
							</thead>
							<tbody>
								<?php
								$customer = $conn->query("SELECT * FROM customer_list order by name asc");
								while ($row = $customer->fetch_assoc()) :
									$cus_arr[$row['id']] = $row['name'];
								endwhile;
								$cus_arr[0] = "GUEST";

								$i = 1;
								$sales = $conn->query("SELECT * FROM sales_list  order by date(date_updated) desc");
								while ($row = $sales->fetch_assoc()) :
									$user = $conn->query("SELECT * FROM users WHERE id = " . $SESSION['login_id'] . "");

								?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>
										<td class=""><?php echo date("M d, Y", strtotime($row['date_updated'])) ?></td>
										<td class=""><?php echo date("H:i:s", strtotime($row['date_updated'])) ?></td>
										<td class=""><?php echo $row['ref_no'] ?></td>
										<td class=""><?php echo isset($cus_arr[$row['customer_id']]) ? $cus_arr[$row['customer_id']] : 'N/A' ?></td>
										<td class="text-center">
											<a class="btn btn-sm btn-primary" href="index.php?page=pos&id=<?php echo $row['id'] ?>">Edit</a>
											<a class="btn btn-sm btn-danger delete_sales" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
										</td>
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
	$('table').dataTable()
	$('#new_sales').click(function() {
		location.href = "index.php?page=pos"
	})
	$('.delete_sales').click(function() {
		_conf("Are you sure to delete this data?", "delete_sales", [$(this).attr('data-id')])
	})

	function delete_sales($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_sales',
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