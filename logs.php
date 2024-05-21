<?php include 'db_connect.php' ?>

<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="card mt-3">
					<div class="card-header">
						<!-- <button class="col-md-2 float-right btn btn-primary btn-sm" id="new_sales"><i class="fa fa-plus"></i> Add Transaction</button> -->
						<h4><b>System Logs</b></h4>
					</div>
					<div class="card-body">
						<div class="h6 font-weight-bold">Filter</div>
						<?php
						if (isset($_POST['filter'])) {
							extract($_POST);
						}
						?>
						<form action="" method="post">
							<div class="d-flex">
								<div class="mr-2">
									<div class="h7">User:</div>
									<select name="tender" id="" class="form-control mb-3" required>
										<option value="0">All</option>
										<?php
										$res = $conn->query("SELECT * FROM users ");
										if ($res->num_rows > 0) {
											while ($row = $res->fetch_assoc()) {
										?>
												<option value="<?= $row['id'] ?>" <?= (isset($_POST['filter']) && $_POST['tender'] == $row['id']) ? 'selected' : '' ?>><?= $row['name'] ?></option>
										<?php
											}
										}

										?>
									</select>
								</div>
								<div class="mr-2">
									<div class="h7">From</div>
									<input type="date" name="from" id="from" class="form-control mb-3" value="<?= (isset($_POST['filter'])) ? $_POST['from'] : date('Y-m-01') ?>" required>
								</div>
								<div class="mr-2">
									<div class="h7">To</div>
									<input type="date" name="to" id="to" class="form-control mb-3" value="<?= (isset($_POST['filter'])) ? $_POST['to'] : date('Y-m-d') ?>" required>
								</div>
								<div class="mr-2">
									<div class="h7">.</div>
									<button type="submit" name="filter" class="btn btn-primary">Fetch</button>
								</div>
								<div>
									<!-- GET Current User -->
									<div class="h7">Export</div>
									<button type="button" name="filter" class="btn btn-success" onclick="toPDF()">PDF</button>
								</div>
							</div>
						</form>
						<table class="table table-bordered">
							<thead>
								<th class="text-center">#</th>
								<th class="text-center">Type</th>
								<th class="text-center">Message</th>
								<th class="text-center">User</th>
								<th class="text-center">Time</th>
							</thead>
							<tbody>
								<?php
								$i = 1;
								if (isset($_POST['filter'])) {
									if ($_POST['tender'] != 0) {
										$tender = "AND user_id = " . $_POST['tender'];
									} else {
										$tender = "";
									}

									$datetime = new DateTime($from);
									$from = $datetime->format('Y-m-d');
									$datetime = new DateTime($to . " 12:59:00");
									$datetime->modify('+1 day');
									$to = $datetime->format('Y-m-d H:i:s');
								} else {
									$tender = "";
									$datetime = new DateTime(date('Y-m-01'));
									$from = $datetime->format('Y-m-d H:i:s');
									$datetime =  new DateTime(date('Y-m-d h:i:s'));
									$datetime->modify('+1 day');
									$to = $datetime->format('Y-m-d H:i:s');
								}
								$qry = "SELECT *, l.type as logtype FROM logs l INNER JOIN users u ON l.user_id = u.id WHERE (l.date_updated BETWEEN '$from' AND '$to') $tender order by l.id desc";
								$sales = $conn->query($qry);

								while ($row = $sales->fetch_assoc()) :
								?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>
										<td class="text-center"><?php echo $row['logtype'] ?></td>
										<td class="text-"><?php echo $row['message'] ?></td>
										<td class="text-center"><?php echo $row['name'] ?></td>
										<td class="text-center"><?php echo date("M d, Y h:i A", strtotime($row['date_updated'])) ?></td>

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


	function toPDF() {
		window.location.href = "logs_pdf.php?tender=" + $("<?$_SESSION['login_id']?>").val() + "&from=" + $("#from").val() + "&to=" + $("#to").val();
	}
</script>