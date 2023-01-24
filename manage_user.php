<?php
include('db_connect.php');
if (isset($_GET['id'])) {
	$user = $conn->query("SELECT * FROM users where id =" . $_GET['id']);
	foreach ($user->fetch_array() as $k => $v) {
		$meta[$k] = $v;
	}
}
?>
<div class="container-fluid">

	<form action="" id="manage-user">
		<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id'] : '' ?>">
		<div class="form-group">
			<label for="name">Name</label>
			<input type="text" name="name" id="name" class="form-control" value="<?php echo isset($meta['name']) ? $meta['name'] : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="username">Employee ID</label>
			<input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username'] : '' ?>" required>
			<?php

			if (!isset($meta['id'])) {
			?>
				<p style="font-size: 13px" class="text-muted">This will be used as temporary password</p>
			<?php
			}
			?>

		</div>
		<div class="form-group">
			<label for="username">Email</label>
			<input type="email" name="email" id="email" class="form-control" value="<?php echo isset($meta['email']) ? $meta['email'] : '' ?>" required <?= (isset($meta['id'])) ? 'readonly' : '' ?>>
		</div>
		<?php

		if (isset($meta['id'])) {
		?>
			<hr>
			<div class="h6 font-weight-bold text-dark">Change Password</div>
			<p style="font-size: 13px" class="text-muted">Leave empty to retain old password.</p>
			<div class="form-group">
				<label for="password">New Password</label>
				<input type="password" name="password" id="password" class="form-control">
			</div>
			<div class="form-group">
				<label for="confirm">Confirm Password</label>
				<input type="password" name="confirm" id="confirm" class="form-control">
			</div>
		<?php
		}
		?>

		<?php if (!isset($_GET['mtype'])) : ?>
			<div class="form-group">
				<label for="type">User Type</label>
				<select name="type" id="type" class="custom-select">
					<option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected' : '' ?>>Admin</option>
					<option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected' : '' ?>>Cashier</option>
				</select>
			</div>
		<?php endif; ?>
	</form>
</div>
<script>
	$('#manage-user').submit(function(e) {
		e.preventDefault();
		start_load()
		$.ajax({
			url: 'ajax.php?action=save_user',
			method: 'POST',
			data: $(this).serialize(),
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully saved.", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)
				} else if (resp == 3) {
					alert_toast("Email already in used. Use a different one", 'danger');
					end_load();
				} else if (resp == 6) {
					alert_toast("Password did not match", 'danger');
					end_load();
				}
			}
		})
	})
</script>