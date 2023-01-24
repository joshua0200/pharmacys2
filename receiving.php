<?php include 'db_connect.php' ?>
<div class="container-fluid">

	<!-- Pending Order -->
	<div class="row">
		<div class="col-md-12 mt-3">
			<div class="card">
				<div class="card-header">
					<b style="font-size: 20px;">Pending Order</b>
					<button class="col-md-2 float-right btn btn-success btn-sm" id="new_order"><i class="fa fa-plus"></i> New Order</button>
				</div>
				<div class="card-body">
					<table class="table table-bordered">
						<thead>
							<th class="text-center">#</th>
							<th class="text-center">Date Ordered</th>
							<th class="text-center">Reference #</th>
							<th class="text-center">Supplier</th>
							<th class="text-center">Action</th>
						</thead>
						<tbody>
							<?php
							$orders = $conn->query("SELECT o.*, s.supplier_name FROM orders o INNER JOIN supplier_list s ON o.supplier_id = s.id WHERE o.status = 1 order by date(o.date_added) desc");
							if ($orders->num_rows > 0) {
								$i = 1;
								while ($row = $orders->fetch_assoc()) :
							?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>
										<td class=""><?php echo date("M d, Y", strtotime($row['date_added'])) ?></td>
										<td class="text-center"><?php echo $row['ref_no'] ?></td>
										<td class=""><?php echo $row['supplier_name'] ?></td>
										<td class="text-center">
											<a class="btn btn-sm btn-success" href="index.php?page=manage_receiving&id=<?php echo $row['id'] ?>">Receive</a>

											<a class="btn btn-sm btn-primary" href="index.php?page=edit_order&id=<?php echo $row['id'] ?>">Edit / Print</a>
											<a class="btn btn-sm btn-danger delete_order" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
										</td>
									</tr>
							<?php endwhile;
							} ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div>
		<hr style="background-color: #041520;">
	</div>

	<!-- Received Orders -->

	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<b style="font-size: 20px;">Received Orders</b>
					<!-- <button class="col-md-2 float-right btn btn-primary btn-sm" id="new_receiving"><i class="fa fa-plus"></i> New Order</button> -->
				</div>
				<div class="card-body">
					<table class="table table-bordered">
						<thead>
							<th class="text-center">#</th>
							<th class="text-center">Date Received</th>
							<th class="text-center">Reference #</th>
							<th class="text-center">Supplier</th>
							<th class="text-center">Batch #</th>
							<th class="text-center">Action</th>
						</thead>
						<tbody>
							<?php
							$orders = $conn->query("SELECT o.*, s.supplier_name FROM orders o INNER JOIN supplier_list s ON o.supplier_id = s.id WHERE o.status = 2 order by date(o.date_added) desc");
							if ($orders->num_rows > 0) {
								$i = 1;
								while ($row = $orders->fetch_assoc()) :
							?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>
										<td class=""><?php echo date("M d, Y", strtotime($row['date_added'])) ?></td>
										<td class="text-center"><?php echo $row['ref_no'] ?></td>
										<td class=""><?php echo $row['supplier_name'] ?></td>
										<td class="text-center"><?php echo $row['batch_no'] ?></td>
										<td class="text-center">
											<a class="btn btn-sm btn-primary" href="index.php?page=get_order&id=<?php echo $row['id'] ?>">View</a>

											<!-- <a class="btn btn-sm btn-primary" href="index.php?page=edit_order&id=<?php echo $row['id'] ?>">Edit / Print</a>
											<a class="btn btn-sm btn-danger delete_order" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a> -->
										</td>
									</tr>
							<?php endwhile;
							} ?>
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
	$('#new_receiving').click(function() {
		location.href = "index.php?page=manage_receiving"
	})
	$('#new_order').click(function() {
		location.href = "index.php?page=manage_order"
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
	$('.delete_order').click(function() {
		_conf("Are you sure to delete this data?", "delete_order", [$(this).attr('data-id')])
	})

	function delete_order($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_order',
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