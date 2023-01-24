<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<title>Pharmacy Sales and Inventory System</title>


	<?php include('./header.php'); ?>
	<?php include('./db_connect.php'); ?>
	<?php
	session_start();
	if (isset($_SESSION['login_id']))
		header("location:index.php?page=home");

	?>



</head>
<style>
	body {
		width: 100%;
		height: calc(100%);
		background-image: url("assets/img/bg2.jpg");
		background-size: cover;
		/* background: #007bff; */
	}

	main#main {
		width: 100%;
		height: calc(100%);
		/* background:white; */

	}

	#login-right {
		position: absolute;
		right: 0;
		width: 40%;
		height: calc(100%);
		background: skyblue;
		display: flex;
		align-items: center;
	}

	#login-left {
		position: fixed;
		left: 330px;
		width: 60%;
		height: calc(100%);
		/* background: #ffffff; */
		display: flex;
		align-items: center;
	}

	#login-right .card {
		margin: auto
	}

	.logo {
		margin: auto;
		font-size: 8rem;
		background: white;
		padding: .5em 0.9em;
		border-radius: 50% 50%;
		color: red;
	}

	.center {

		margin-left: auto;
		margin-right: auto;
		width: 50%;
	}
</style>

<body>


	<main id="main" class="">
		<div id="login-left">
			<div class="center">
				<!-- <span class="fa fa-prescription-bottle-alt"></span> -->
				<!-- <img class="center" style="width: 125%; height:125%; " src="assets/img/logo2.png" alt="logo"> -->
				<div class="card col-md-8" style="background-color: #DFF6FF; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
					<div class="card-body">
						<form id="login-form">
							<img src="assets/img/logo3.png" alt="logo" style="width: 200px; height: 60px;">
							<hr style="background-color: #009acb;">
							<div class="form-group">
								<label for="username" class="control-label" style="color: #041520; ">Employee ID:</label>
								<input type="text" id="username" name="username" class="form-control">
							</div>
							<div class="form-group ">
								<label for="password" class="control-label" style="color: #041520; ">Password:</label>
								<input type="password" id="password" name="password" class="form-control">
								<a href="resetpass.php" class="float-left" style="font-size: small;">Forgot password</a>
							</div>
							<br>
							<button class="btn-sm btn-block btn-wave col-md-4 btn-primary" style="font-weight:bold;">Login</button>

						</form>
					</div>
				</div>
			</div>



	</main>

	<a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>


</body>
<script>
	$('#login-form').submit(function(e) {
		e.preventDefault()
		$('#login-form button[type="button"]').attr('disabled', true).html('Logging in...');
		if ($(this).find('.alert-danger').length > 0)
			$(this).find('.alert-danger').remove();
		$.ajax({
			url: 'ajax.php?action=login',
			method: 'POST',
			data: $(this).serialize(),
			error: err => {
				console.log(err)
				$('#login-form button[type="button"]').removeAttr('disabled').html('Login');

			},
			success: function(resp) {
				console.log(resp);
				if (resp == 1) {
					location.href = 'index.php?page=home';
				} else if (resp == 2) {
					location.href = 'voting.php';
				} else if (resp == 5) {
					location.href = 'verify.php';
				} else {
					$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
					$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
				}
			}
		})
	})
</script>

</html>