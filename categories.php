<!-- <?php include('db_connect.php'); ?> -->


<div class="card mb-3">


	<div class="card-header">

		<button class="btn text-white bg-danger shadow-sm  float-right" onclick="$('#categories-panel').addClass('d-none')" style="border-radius: 100px;"><i class="far fa-times-circle"></i></button>
		<button class="btn bg-success text-white shadow-sm	 float-right mr-2" onclick="$('#manage-category').toggleClass('d-none')" >Add New <i class="fas fa-plus-circle"></i></button>
		<h4>
			<b>Manage Categories</b>
		</h4>

	</div>
	<div class="card-body">
		<form action="" id="manage-category" style="max-width: 50%" class="d-none">
			<div class="card mb-3">
				<div class="card-header">
					Medicine Category Form
				</div>
				<div class="card-body">
					<input type="hidden" name="id">
					<div class="form-group">
						<label class="control-label">Category</label>
						<input type="text" class="form-control" name="name">
					</div>

				</div>

				<div class="card-footer">
					<div class="row">
						<div class="col-md-12">
							<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Save</button>
							<button class="btn btn-sm btn-default col-sm-3" type="button" onclick="$('#manage-category').get(0).reset()"> Clear</button>
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
				$cats = $conn->query("SELECT * FROM category_list order by id asc");
				while ($row = $cats->fetch_assoc()) :
				?>
					<tr>
						<td class="text-center"><?php echo $i++ ?></td>
						<td class="">
							<?php echo $row['name'] ?>
						</td>
						<td class="text-center">
							<button class="btn btn-sm btn-primary edit_cat" type="button" data-id="<?php echo $row['id'] ?>" data-name="<?php echo $row['name'] ?>">Edit</button>
							<button class="btn btn-sm btn-danger delete_cat" type="button" data-id="<?php echo $row['id'] ?>">Delete</i></button>
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
	$('#manage-category').submit(function(e) {
		e.preventDefault()
		start_load()
		$.ajax({
			url: 'ajax.php?action=save_category',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function(resp) {
				console.log(resp)
				if (resp == 1) {
					alert_toast("Data successfully added", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				} else if (resp == 2) {
					alert_toast("Data successfully updated", 'success')
					setTimeout(function() {
						window.location.href = "?page=product&toggle=categories-panel";
					}, 1500)

				} else if (resp == 3) {
					alert_toast("Duplicate enrtry", 'warning')
					end_load();
					// setTimeout(function() {
					// 	window.location.href = "?page=product&toggle=categories-panel";
					// }, 1500)

				}
			}
		})
	})
	$('.edit_cat').click(function() {
		start_load()
		var cat = $('#manage-category')
		cat.get(0).reset()
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='name']").val($(this).attr('data-name'))
		$('#manage-category').removeClass('d-none')
		end_load()
	})
	$('.delete_cat').click(function() {
		_conf("Are you sure to delete this category?", "delete_cat", [$(this).attr('data-id')])
	})

	function delete_cat($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_category',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function() {
						window.location.href = "?page=product&toggle=categories-panel";
					}, 1500)

				}
			}
		})
	}
	$('table').dataTable()
</script>