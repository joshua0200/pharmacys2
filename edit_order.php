<?php include 'db_connect.php';
if (isset($_GET['id'])) {
	$qry = $conn->query("SELECT o.*, s.id as supid, s.supplier_name FROM orders o INNER JOIN supplier_list s ON o.supplier_id = s.id where o.id=" . $_GET['id'])->fetch_array();
	$inv = $conn->query("SELECT *, o.id as item_id FROM order_details o INNER JOIN product_list p ON o.product_id = p.id WHERE order_id = " . $_GET['id']);
}

?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="card mt-3">
			<div class="card-header">
				<a href="?page=receiving" class="btn btn-success float-right"><i class="fas fa-undo mr-2"></i>Back</a>
				<h4><b>Edit Order / Ref #<?= $qry['ref_no'] ?></b></h4>
			</div>
			<div class="card-body">
				<form action="" id="manage-order">
					<input type="hidden" name="id" value="<?php echo $qry['id'] ?>">
					<input type="hidden" name="ref_no" value="<?php echo $qry['ref_no'] ?>"></td>
					<div class="col-md-12">
						<div class="row">
							<div class="form-group col-md-5">
								<label class="control-label">Supplier</label>
								<select name="supplier_id" id="" class="custom-select browser-default select2" required>
									<option value=""></option>
									<?php
									$supplier = $conn->query("SELECT * FROM supplier_list order by supplier_name asc");
									while ($row = $supplier->fetch_assoc()) :
									?>
										<option value="<?php echo $row['id'] ?>" <?= ($qry['supid'] == $row['id'] ? 'selected' : '') ?>><?php echo $row['supplier_name'] ?></option>
									<?php endwhile; ?>
								</select>
							</div>
						</div>
						<hr>
						<div class="row mb-3">
							<div class="col-md-4">
								<label class="control-label">Product</label>
								<select name="" id="product" class="custom-select browser-default select2">
									<option value=""></option>
									<?php
									$product = $conn->query("SELECT * FROM product_list  order by name asc");
									while ($row = $product->fetch_assoc()) :
										$prod[$row['id']] = $row;
									?>
										<option value="<?php echo $row['id'] ?>" data-mease="<?= $row['measurement'] ?>" data-mode="<?= $row['selling_mode'] ?>" data-name="<?php echo $row['name'] ?>" data-description="<?php echo $row['description'] ?>" data-price="<?= $row['price'] ?>"><?php echo $row['name'] . ' • ' . $row['measurement'] . ' • [' . $row['sku'] . ']' . ' • by ' . $row['selling_mode'] . '' ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="col-md-2">
								<label class="control-label">Qty</label>
								<input type="number" class="form-control text-right" step="any" id="qty">
							</div>
							<!-- <div class="col-md-3">
								<label class="control-label">Price</label>
								<input type="number" class="form-control text-right" step="any" id="price">
							</div> -->
							<div class="col-md-3">
								<label class="control-label">&nbsp</label>
								<button class="btn btn-block btn-sm btn-primary" type="button" id="add_list"><i class="fa fa-plus"></i> Add to List</button>
							</div>

						</div>
						<div class="row">
							<table class="table table-bordered" id="list">
								<colgroup>
									<!-- <col width="15%"> -->
									<col width="25%">
									<col width="10%">
									<col width="20%">
									<col width="20%">
									<col width="10%">
								</colgroup>
								<thead>
									<tr>
										<!-- <th class="text-center">ID</th>
										<th class="text-center">Reference Number</th> -->
										<!-- <th class="text-center">Expiry Date</th> -->
										<th class="text-center">Product</th>
										<th class="text-center">Qty</th>
										<th class="text-center">Price</th>
										<th class="text-center">Amount</th>
										<th class="text-center"></th>
									</tr>
								</thead>
								<tbody>
									<?php
									if (isset($_GET['id']) && $inv->num_rows > 0) :
										while ($row = $inv->fetch_assoc()) :
									?>
											<tr class="item-row" data-id="<?= $row['id'] ?>">
												<td>
													<input type="hidden" name="product_id[]" value="<?php echo $row['id'] ?>">
													<p class="pname">Name: <b><?php echo $row['name'] ?></b><sup><?= $row['measurement'] ?></sup> </p>
												</td>
												<td>
													<div class="row-5">
														<div class="col">

															<input type="number" min="1" step="any" name="qty[]" value="<?= $row['qty'] ?>" class="text-center form-control">
														</div>
														<div class="col">
															<p class="pdesc"><small><b><?= $row['selling_mode'] ?></b>/s</small></p>
														</div>
													</div>
												</td>
												<td>
													<input type="number" min="1" step="any" name="price[]" value="<?php echo $row['price'] ?>" class="text-right">
												</td>
												<td>
													<p class="amount text-right"><?= $row['price'] ?></p>
												</td>
												<td class="text-center">
													<buttob class="btn btn-sm btn-danger" onclick="rem_list($(this))"><i class="fa fa-trash"></i></buttob>
												</td>
											</tr>
										<?php endwhile; ?>
									<?php endif; ?>
								</tbody>
								<tfoot>
									<tr>
										<th class="text-right" colspan="3">Total</th>
										<th class="text-right tamount"></th>
										<th><input type="hidden" name="tamount" value=""></th>
									</tr>
								</tfoot>
							</table>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<button class="btn btn-primary col-sm-3 btn-sm btn-block float-right .col-md-3">Save & Print</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div id="tr_clone">
	<table>
		<tr class="item-row">
			<!-- <td>
				<input type="date" name="expiry_date[]" class="text-right" value="" required>
			</td> -->
			<td>
				<!-- <input type="hidden" name="inv_id[]" value=""> -->
				<input type="hidden" name="product_id[]" value="">
				<p class="pname">Name: <b>product</b> <sup></sup></p>
			</td>
			<td>
				<div class="row">
					<div class="col">

						<input type="number" min="1" step="any" name="qty[]" value="" class="text-center form-control">
					</div>
					<div class="col">
						<p class="pdesc"><small><b></b>/s</small></p>
					</div>
				</div>

			</td>
			<td>
				<input type="number" min="1" step="any" name="price[]" value="" class="text-right" readonly>
			</td>
			<td>
				<p class="amount text-right"></p>
			</td>
			<td class="text-center">
				<buttob class="btn btn-sm btn-danger" onclick="rem_list($(this))"><i class="fa fa-trash"></i></buttob>
			</td>
		</tr>
	</table>
</div>
<style type="text/css">
	#tr_clone {
		display: none;
	}

	td {
		vertical-align: middle !important;
		justify-content: center;
	}

	td>input {
		margin: auto;
	}

	td p {
		margin: unset;
	}

	td input {
		height: calc(100%);
		width: calc(100%);
		border: unset;
	}

	td input:focus {
		border: unset;
		outline-width: inherit;
	}

	input[type=number]::-webkit-inner-spin-button,
	input[type=number]::-webkit-outer-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}
</style>
<script>
	calculate_total();
	$('.select2').select2({
		placeholder: "Please select here",
		width: "100%"
	})
	$(document).ready(function() {
		if ('<?php echo isset($id) ?>' == 1) {
			$('[name="supplier_id"]').val('<?php echo isset($supplier_id) ? $supplier_id : '' ?>').select2({
				placeholder: "Please select here",
				width: "100%"
			})
			calculate_total()
		}
	})

	function rem_list(_this) {
		_this.closest('tr').remove()
	}
	$(document).on('keyup', '[name="qty[]"],[name="price[]"]', function() {
		calculate_total()
	});
	// $('[name="qty[]"],[name="price[]"]').keyup(function() {
	// 		calculate_total()
	// 	})
	$('#add_list').click(function() {
		// alert("TEST");
		// return false;
		var tr = $('#tr_clone tr.item-row').clone();
		var product = $('#product').val(),
			qty = $('#qty').val(),
			price = $("#product option[value='" + product + "']").attr('data-price');
		if ($('#list').find('tr[data-id="' + product + '"]').length > 0) {
			alert_toast("Product already on the list", 'danger')
			return false;
		}
		if (product == '' || qty == '') {
			alert_toast("Please complete the fields first", 'danger')
			return false;
		}
		tr.attr('data-id', product)
		tr.find('.pname b').html($("#product option[value='" + product + "']").attr('data-name'))
		tr.find('.pname sup').html($("#product option[value='" + product + "']").attr('data-mease'))
		tr.find('.pdesc b').html($("#product option[value='" + product + "']").attr('data-mode'))
		tr.find('[name="product_id[]"]').val(product)
		tr.find('[name="qty[]"]').val(qty)
		tr.find('[name="price[]"]').val(price)
		var amount = parseFloat(price) * parseFloat(qty);
		tr.find('.amount').html(parseFloat(amount).toLocaleString('en-US', {
			style: 'decimal',
			maximumFractionDigits: 2,
			minimumFractionDigits: 2
		}))
		$('#list tbody').append(tr)
		calculate_total()
		$('#product').val('').select2({
			placeholder: "Please select here",
			width: "100%"
		})
		$('#qty').val('')
	})

	function calculate_total() {
		var total = 0;
		$('#list tbody').find('.item-row').each(function() {
			var _this = $(this).closest('tr')
			var amount = parseFloat(_this.find('[name="qty[]"]').val()) * parseFloat(_this.find('[name="price[]"]').val());
			amount = amount > 0 ? amount : 0;
			_this.find('p.amount').html(parseFloat(amount).toLocaleString('en-US', {
				style: 'decimal',
				maximumFractionDigits: 2,
				minimumFractionDigits: 2
			}))
			total += parseFloat(amount);
		})
		$('#list [name="tamount"]').val(total)
		$('#list .tamount').html('₱ ' + parseFloat(total).toLocaleString('en-US', {
			style: 'decimal',
			maximumFractionDigits: 2,
			minimumFractionDigits: 2
		}))
	}
	$('#manage-order').submit(function(e) {
		e.preventDefault()
		start_load()
		if ($("#list .item-row").length <= 0) {
			alert_toast("Please insert atleast 1 item first.", 'danger');
			end_load();
			return false;
		}
		$.ajax({
			url: 'ajax.php?action=update_order',
			method: 'POST',
			data: $(this).serialize(),
			success: function(resp) {
				console.log(resp);
				if (resp) {
					alert_toast("Data successfully updated", 'success')
					var nw = window.open("print_order.php?id=" + resp, "_blank", "height=700,width=900")
					nw.print()
					setTimeout(function() {
						nw.close()
						location.href = "index.php?page=receiving"
					}, 1500)
				}
			}
		})
	})
</script>