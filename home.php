<style>
	.ct-label {
		word-wrap: break-word;
		font-weight: bold;
	}
</style>
<div class="container-fluid">

	<div class="row mt-3">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-4 col-sm-12">
					<div class="card mb-3" style="border-radius: 20px; background-color:#041520;">
						<div class="card-body" style="color:#DFF6FF;">
							<p><b>
									<large>Total Sales Today</large>
								</b></p>
							<hr style="background-color:#009acb;">
							<p class="text-right h2"><b>
									<large>₱ <?php

												if ($_SESSION['login_type'] == 1) {
													$str = "";
												} else {
													$str = "user_id = " . $_SESSION['login_id'] . " AND";
												}
												include 'db_connect.php';
												$sales = $conn->query("SELECT SUM(total_amount) as amount FROM sales  where $str date(date_updated)= '" . date('Y-m-d') . "'");
												echo $sales->num_rows > 0 ? number_format($sales->fetch_array()['amount'], 2) : "0.00";
												?></large>
								</b></p>
						</div>
					</div>
					<div class="card" style="border-radius: 20px; background-color:#009acb; ">
						<div class="card-body text-white">
							<p><b>
									<large>Total Transactions Today</large>
								</b></p>
							<hr style="background-color: #041520;">
							<p class="text-right h2"><b>
									<large><?php
											include 'db_connect.php';
											$sales = $conn->query("SELECT * FROM sales where $str date(date_updated) = '" . date('Y-m-d') . "'");
											echo $sales->num_rows > 0 ? number_format($sales->num_rows) : "0";
											?></large>
								</b></p>
						</div>
					</div>
				</div>
				<div class="col-md-8 col-sm-12">
					<div class="card shadow-sm">
						<div class="card-header text-light" style="background-color: #041520;">
							<div class="d-flex">
								<div class=" w-100">
									<b>Sales:</b>
									<div class="h6 mb-0">Month of <span id="sales-month"></span></div>
									<div class="h7">₱ <span id="total-sales"></span></div>
								</div>
								<div class=" ">
									<select name="" id="sales-chart" class="form-control float-right mr-2" value="<?= date('m') ?>" onchange="loadSales($(this).val(), $('#sales-chart-year').val())" style="width: 150px">
										<option value="1">January</option>
										<option value="2">February</option>
										<option value="3">March</option>
										<option value="4">April</option>
										<option value="5">May</option>
										<option value="6">June</option>
										<option value="7">July</option>
										<option value="8">August</option>
										<option value="9">September</option>
										<option value="10">October</option>
										<option value="11">November</option>
										<option value="12">December</option>
									</select>
								</div>
								<div class="">
									<select name="" id="sales-chart-year" class="form-control" style="width: 100px" onchange="loadSales($('#sales-chart').val(), $(this).val())">
										<?php
										$currentYear = date("Y");
										for ($i = $currentYear; $i >= 2022; $i--) {
											echo "<option value='$i'>$i</option>";
										}
										?>
									</select>
								</div>
							</div>


						</div>
						<div class="card-body">

							<div id="sales" class="ct-chart ct-major-eleventh"></div>


						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-md-4 col-sm-12">
			<div class="card shadow-sm mb-3">
				<div class="card-header text-light" style="background-color: #041520;">
					<div class="row">
						<div class="col-12">
							<b>In-Demand Products:</b>
							<div class="h6">Month of <span id="demand-month"></span></div>
						</div>
						<div class="col-6">
							<select name="" id="demand-chart" class="form-control form-control-sm float-right" value="<?= date('m') ?>" onchange="loadDemand($(this).val(),$('#demand-chart-year').val())">
								<option value="1">January</option>
								<option value="2">February</option>
								<option value="3">March</option>
								<option value="4">April</option>
								<option value="5">May</option>
								<option value="6">June</option>
								<option value="7">July</option>
								<option value="8">August</option>
								<option value="9">September</option>
								<option value="10">October</option>
								<option value="11">November</option>
								<option value="12">December</option>
							</select>

						</div>
						<div class="col-6">
							<select name="" id="demand-chart-year" class="form-control form-control-sm" style="width: 100px" onchange="loadDemand($('#demand-chart').val(), $(this).val())">
								<?php
								$currentYear = date("Y");
								for ($i = $currentYear; $i >= 2022; $i--) {
									echo "<option value='$i'>$i</option>";
								}
								?>
							</select>
							<a class="float-left text-primary m-2" style="cursor: pointer;" onclick="exportDemand()">Export PDF</a>
						</div>
						
					</div>

				</div>
				<div class="card-body">

					<div id="demand" class="ct-chart ct-perfect-fourth"></div>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-12">
			<div class="card shadow-sm mb-3">
				<div class="card-header text-light" style="background-color: #041520;">

					<b>Predicted in-demand products next Month:</b>
					<div class="h6">Based from historical data</div>


				</div>
				<div class="card-body">

					<div id="predict" class="ct-chart ct-perfect-fourth"></div>
				</div>
			</div>
		</div>
		<!-- EXPIRING -->
		<div class="col-md-4">
			<div class="card shadow-sm">
				<div class="card-header" style="color: #041520;">
					<b>Expiring Products Section:</b>
				</div>
				<div class="card-body">
					<div class="h7 text-muted mb-3">Products expiring 2 weeks from now are displayed.</div>
					<ul class="list-group " style="height: 400px; overflow-y: auto;">
						<?php
						$d = 14;
						$date = date_add(date_create(), date_interval_create_from_date_string("14 Days"));
						$ex = $conn->query("SELECT * FROM inventory i INNER JOIN product_list p ON i.product_id = p.id WHERE expiry_date > '" . date('y-m-d') . "'");
						// while($row= $ex->fetch_array()):
						// $dif= $conn->query("SELECT i.*,p.name,p.measurement,p.sku FROM inventory i inner join product_list p on p.id = i.product_id DATEDIFF(day, date(i.expiry_date), date(Y-m-d))");
						$expiring = 0;
						if ($ex->num_rows > 0) {
							while ($row = $ex->fetch_array()) :
								$ndate = date_create();
								$edate = date_add(date_create($row['expiry_date']), date_interval_create_from_date_string("1 Days"));
								$diff = date_diff($ndate, $edate);
								if ($diff->format("%a") <= 12) {
									$expiring++;
						?>
									<li class="list-group-item text-dark bg-light" style="margin-bottom: 5px;">
										<div class="h6 float-right badge badge-danger">
											Expires in: <?php echo $diff->format("%a day(s)") ?>
										</div>
										<div class="h7">Barcode: <b><?= $row['sku'] ?></b></div>
										<div class="h7">Batch #: <b><?= $row['batch_no'] ?></b></div>
										<div class="h6">
											Product: <b><?php echo $row['name'] ?> <sup><?php echo $row['measurement'] ?></sup></b>
										</div>
										<div class="h7">Quantity: <b><?= $row['qty'] ?></b></div>
									</li>
							<?php }
							endwhile;
						}
						if ($expiring == 0) {
							?>
							<li class="list-group-item text-dark bg-light" style="margin-bottom: 5px;">
								<div class="h6">No expiring products at this moment</div>
							</li>
						<?php
						} ?>
					</ul>
				</div>
			</div>
		</div>
		<!-- LOW STOCK -->
		<div class="col-md-4">
			<div class="card shadow-sm">
				<div class="card-header" style="color: #041520;">
					<a href="?page=inventory" class="float-right">Check Inventory <i class="fas fa-arrow-circle-right"></i></a>
					<b>CRITICAL STOCK:</b>
				</div>
				<div class="card-body">
					<ul class="list-group" style="height: 400px; overflow-y: auto;">
						<?php
						$i = 1;
						$product = $conn->query("select p.id, p.name, p.measurement, sum(i.qty) as total from product_list p right join inventory i ON p.id = i.product_id WHERE i.expiry_date > '" . date('Y-m-d') . "' group by i.product_id");
						if ($product->num_rows > 0) {
							while ($row = $product->fetch_assoc()) :
								if ($row['total'] <= 200) {
						?>
									<li class="list-group-item bg-light text-dark" style="margin-bottom: 5px;">
										<b class="text-danger"><?php echo $row['name'] ?> <sup><?php echo $row['measurement'] ?></sup></b>
										<p class="h7">Remaining stock: <?php echo $row['total'] ?></p>
										<?php
										if ($_SESSION['login_type'] == 1) {
										?>
											<hr>
											<a href="index.php?page=manage_order&id=<?php echo $row['id'] ?>" class="btn badge  float-right badge-primary text-black">Order Now</a>
										<?php
										}
										?>
									</li>
							<?php
								}
							endwhile;
						} else {
							?><li class="list-group-item text-dark bg-light" style="margin-bottom: 5px;">
								<div class="h6">Inventory is looking good.</div>
							</li>
						<?php
						} ?>
					</ul>
				</div>
			</div>
		</div>
	</div>

</div>
<script>
	loadSales($("#sales-chart").val(), $("#sales-chart-year").val());
	loadDemand($("#demand-chart").val(), $("#demand-chart-year").val());

	function loadSales(month, year) {
		console.log(month, year);
		$.post('charts/sales.php', {
			month,
			year
		}, function(response) {
			response = JSON.parse(response);
			$("#sales-month").text(response.monthName);
			$("#total-sales").text(response.total);
			new Chartist.Line('#sales', {
				labels: response.days,
				series: [response.data]
			}, {

				fullWidth: true,
				chartPadding: {
					right: 40
				},
				axisY: {
					// Lets offset the chart a bit from the labels
					offset: 60,
					// The label interpolation function enables you to modify the values
					// used for the labels on each axis. Here we are converting the
					// values into million pound.
					labelInterpolationFnc: function(value) {
						return '₱ ' + value;
					}
				}
			});
		});
	}

	function loadDemand(month, year) {
		$.post('charts/demand.php', {
			month,
			year
		}, function(response) {

			response = JSON.parse(response);
			$("#demand-month").text(response.monthName);
			new Chartist.Bar('#demand', {
				labels: response.products,
				series: [response.data]
			}, {
				seriesBarDistance: 10,
				reverseData: true,
				horizontalBars: true,
				// axisY: {
				// 	offset: 70
				// },
				chartPadding: {
					top: 20,
					right: 40,
					bottom: 20,
					left: 60
				}
			});
		});
	}

	$.post('charts/predict.php', {

	}, function(response) {
		response = JSON.parse(response);
		console.log(response);
		let total = 0;
		response.data.forEach(number => total += number);
		// response.data.forEach(number => total += number);
		let percentages = response.data.map(function(num) {
			return ((num / total) * 100).toFixed(2);
		});
		var data = {
			labels: response.products,
			series: percentages
		};
		for (var i = 0; i < response.products.length; i++) {
			response.products[i] += ' ' + percentages[i] + '%';
		}
		console.log('Percentages', percentages)

		var options = {
			labelInterpolationFnc: function(value) {
				return value[0]
			}
		};
		var responsiveOptions = [
			['screen and (min-width: 640px)', {
				chartPadding: 20,
				labelOffset: 10,
				labelDirection: 'neutral',
				labelInterpolationFnc: function(value) {
					return value;
				}
			}],
			['screen and (min-width: 1024px)', {
				labelOffset: 50,
				chartPadding: 50
			}]
		];


		new Chartist.Pie('#predict', data, options, responsiveOptions);
	});



	function exportDemand() {
		var id = $("#demand-chart").val();
		window.location.href = "demand_pdf.php?id=" + id;
	}
</script>