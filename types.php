<?php include('db_connect.php'); ?>

<div class="card mb-3">
	<div class="card-header">
		<button class="btn bg-danger shadow-sm text-white  float-right" onclick="$('#types-panel').addClass('d-none')" style="border-radius: 100px;"><i class="far fa-times-circle"></i></button>
		<button class="btn bg-success shadow-sm text-white float-right mr-2" onclick="$('#manage-type').toggleClass('d-none')" >Add New <i class="fas fa-plus-circle"></i></button>
		<h4>
			<b>Manage Medicine Types</b>
		</h4>

	</div>
	<div class="card-body">
		<form action="" id="manage-type" class="d-none mb-3" style="max-width: 50%;">
			<div class="card">
				<div class="card-header">
					Medicine Type Form
				</div>
				<div class="card-body">
					<input type="hidden" name="id">
					<div class="form-group">
						<label class="control-label">Type</label>
						<input type="text" class="form-control" name="name">
					</div>

				</div>

				<div class="card-footer">
					<div class="row">
						<div class="col-md-12">
							<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Save</button>
							<button class="btn btn-sm btn-default col-sm-3" type="button" onclick="$('#manage-type').get(0).reset()"> Clear</button>
						</div>
					</div>
				</div>
			</div>
		</form>
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th class="text-center">#</th>
					<th class="text-center">Name</th>
					<th class="text-center">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 1;
				$cats = $conn->query("SELECT * FROM type_list order by id asc");
				while ($row = $cats->fetch_assoc()) :
				?>
					<tr>
						<td class="text-center"><?php echo $i++ ?></td>
						<td class="">
							<?php echo $row['name'] ?>
						</td>
						<td class="text-center">
							<button class="btn btn-sm btn-primary edit_type" type="button" data-id="<?php echo $row['id'] ?>" data-name="<?php echo $row['name'] ?>">Edit</i></button>
							<button class="btn btn-sm btn-danger delete_type" type="button" data-id="<?php echo $row['id'] ?>">Delete</i></button>
						</td>
					</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
</div>
<style>
	td {
		vertical-align: middle !important;
	}
</style>
<script>
	$('#manage-type').submit(function(e) {
		e.preventDefault()
		start_load()
		$.ajax({
			url: 'ajax.php?action=save_type',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully added", 'success')
					setTimeout(function() {
						window.location.href = "?page=product&toggle=types-panel";
					}, 1500)

				} else if (resp == 2) {
					alert_toast("Data successfully updated", 'success')
					setTimeout(function() {
						window.location.href = "?page=product&toggle=types-panel";
					}, 1500)

				} else if (resp == 3) {
					alert_toast("Duplicate enrtry", 'warning')
					end_load();
					// setTimeout(function() {
					// 	window.location.href = "?page=product&toggle=categories-panel";
					// }, 1500)

				}
			},
		})
	})
	$('.edit_type').click(function() {
		start_load()
		var cat = $('#manage-type')
		cat.get(0).reset()
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='name']").val($(this).attr('data-name'))
		$('#manage-type').removeClass('d-none')
		end_load()
	})
	$('.delete_type').click(function() {
		_conf("Are you sure to delete this medicine type?", "delete_type", [$(this).attr('data-id')])
	})

	function delete_type($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_type',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function() {
						window.location.href = "?page=product&toggle=types-panel";
					}, 1500)

				}
			}
		})
	}
	$('table').dataTable()
</script>