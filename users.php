<?php

?>

<div class="container-fluid">

	<div class="row">
		<div class="col-lg-12 mt-3">
			<div class="card">


				<div class="card-header">
					<button class="btn btn-primary float-right" id="new_user"><i class="fa fa-plus"></i> New user</button>
					<h4><b>Users</b></h4>
				</div>
				<div class="card-body">
					<table class="table table-striped  ">
						<thead>
							<tr>
								<th class="text-center">#</th>
								<th class="text-center">Name</th>
								<th class="text-center">Employee ID</th>
								<th class="text-center">Type</th>
								<th>Email</th>
								<th>Status</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							include 'db_connect.php';
							$users = $conn->query("SELECT * FROM users order by type,name asc");
							$i = 1;
							while ($row = $users->fetch_assoc()) :
							?>
								<tr>
									<td>
										<?php echo $i++ ?>
									</td>
									<td>
										<?php echo $row['name'] ?>
									</td>
									<td>
										<?php echo $row['username'] ?>
									</td>
									<td>
										<?php

										switch ($row['type']) {
											case 1:
												echo 'Admin';
												break;
											case 2:
												echo 'Cashier';
												break;
										}


										?>
									</td>
									<td><?= $row['email'] ?></td>
									<td>
										<?php

										if ($row['status'] == 1) {
											echo '<span class="badge badge-success">Verified</span>';
										} else {
											echo '<span class="badge badge-danger">Not Verified</span>';
										}

										?>

									</td>
									<td align="center">
										<?php

										if ($row['type'] != 1) {
										?>
											<button type="button" class="btn btn-white " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<i class="fas fa-ellipsis-v"></i>
											</button>
											<div class="dropdown-menu">
												<a class="dropdown-item edit_user" href="javascript:void(0)" data-id='<?php echo $row['id'] ?>'>Edit</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item delete_user" href="javascript:void(0)" data-id='<?php echo $row['id'] ?>'>Delete</a>
											</div>
										<?php
										}

										?>

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
<script>
	$('table').dataTable()
	$('#new_user').click(function() {
		uni_modal('New User', 'manage_user.php')
	})
	$('.edit_user').click(function() {
		uni_modal('Edit User', 'manage_user.php?id=' + $(this).attr('data-id'))
	})
	$('.delete_user').click(function() {
		_conf("Are you sure to delete this user?", "delete_user", [$(this).attr('data-id')])
	})

	function delete_user($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_user',
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