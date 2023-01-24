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
	if (isset($_SESSION['login_id'])) {
		header("location:index.php?page=home");
	}
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
							<div class="h6 font-weight-bold">Account verification</div>
							<hr style="background-color: #009acb;">
							<div class="form-group">
								<label for="email" class="control-label" style="color: #041520; ">Email:</label>
								<input type="text" id="email" name="email" class="form-control" placeholder="Email address">
								<p style="font-size: 13px;">Code will be sent to your email</p>
							</div>
							<button id="sendbtn" class="btn-sm btn-block btn-wave btn-primary" style="font-weight:bold;">Send</button>
						</form>
						<form id="verify-form" class="d-none">
							<div class="form-group">
								<label for="code" class="control-label mt-2" style="color: #041520; ">Code:</label>
								<input type="text" id="code" name="code" class="form-control" placeholder="XXXXXXX">
								<p style="font-size: 13px;">Enter code here</p>
							</div>
							<button id="verifybtn" class="btn-sm btn-block btn-wave btn-primary" style="font-weight:bold;">Verify</button>
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
		$('#sendbtn').attr('disabled', true).html('Sending email...');
		if ($(this).find('.alert-danger').length > 0)
			$(this).find('.alert-danger').remove();
		$.ajax({
			url: 'ajax.php?action=sendVerify',
			method: 'POST',
			data: $(this).serialize(),
			error: err => {
				console.log(err)
				$('#sendbtn').removeAttr('disabled').html('Login');

			},
			success: function(resp) {
				console.log(resp);
				if (resp == 1) {
					$("#code-error").remove();
					$('#verify-form').prepend('<div id="code-error"  class="alert alert-success">Code sent.</div>')
					$('#sendbtn').html('Sent').addClass('d-none');
					$("#verify-form").removeClass('d-none');
					$("#login-form").addClass('d-none');
				} else if (resp == 2) {
					$("#code-error").remove();
					$('#login-form').prepend('<div id="code-error"  class="alert alert-danger">Account not found</div>')
					$('#sendbtn').removeAttr('disabled').html('Send');
				} else {
					$("#code-error").remove();
					$('#login-form').prepend('<div id="code-error"  class="alert alert-danger">Failed. Try again</div>')
					$('#sendbtn').removeAttr('disabled').html('Send');
				}
			}
		})
	})

	$('#verify-form').submit(function(e) {
		e.preventDefault()
		$('#verifybtn').attr('disabled', true).html('Verifying...');
		if ($(this).find('.alert-danger').length > 0)
			$(this).find('.alert-danger').remove();
		$.ajax({
			url: 'ajax.php?action=codeVerify',
			method: 'POST',
			data: {
				email: $("#email").val(),
				code: $("#code").val()
			},
			success: function(resp) {
				console.log(resp);
				if (resp == 1) {
					$("#code-error").remove();
					$('#verify-form').prepend('<div id="code-error" class="alert alert-success">Verified</div>');

					setTimeout(function() {
						location.href = "login.php";
					}, 2000);
				} else if (resp == 2) {
					$("#code-error").remove();
					$('#verify-form').prepend('<div  id="code-error" class="alert alert-danger">Incorrect Code</div>')
					$('#verifybtn').removeAttr('disabled').html('Verify');
				} else {
					$("#code-error").remove();
					$('#verify-form').prepend('<div id="code-error"  class="alert alert-danger">Failed. Try again</div>')
					$('#verifybtn').removeAttr('disabled').html('Verify');
				}
			}
		})
	})
</script>

</html>