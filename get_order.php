<?php include 'db_connect.php';

if (isset($_GET['id'])) {

	$qry = $conn->query("SELECT * FROM orders o INNER JOIN supplier_list s ON o.supplier_id = s.id where o.id=" . $_GET['id'])->fetch_array();

	$inv = $conn->query("SELECT *, o.id as item_id FROM order_details o INNER JOIN product_list p ON o.product_id = p.id WHERE order_id = " . $_GET['id']);

	// $conn->query("SELECT * FROM order_details where type=1 and ref_no=".$_GET['ref_no']);
}



?>
<div class="container-fluid">
	<div class="col-lg-12 mt-3">
		<div class="card">
			<div class="card-header">
				<a href="?page=receiving" class="btn btn-success float-right"><i class="fas fa-undo mr-2"></i>Back</a>
				<button class="btn btn-primary float-right mr-2" onclick="printOrder(<?= $_GET['id'] ?>)"><i class="fas fa-print mr-2"></i>Print</button>
				<h4><b>View Order</b></h4>
			</div>
			<div class="card-body">
				<form id="manage-receiving">
					<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">

					<div class="col-md-12">
						<div class="row">
							<div class="form-group col-md-5">
								<label class="control-label">Supplier:</label>
								<div class="h5 font-weight-bold text-dark"><u><?= $qry['supplier_name'] ?></u></div>
								<label class="control-label">Reference No:</label>
								<div class="h5 font-weight-bold text-dark"><u>#<?= $qry['ref_no'] ?></u></div>
								<label class="control-label">Batch No:</label>
								<div class="h5 font-weight-bold text-dark"><u>#<?= $qry['batch_no'] ?></u></div>
								<label class="control-label">Remarks:</label>
								<div class="h5 font-weight-bold text-dark"><u>Received</u></div>
							</div>
						</div>
						<hr>

						<div class="row mx-1">
							<table class="table table-bordered" id="list">
								<colgroup>
									<col width="15%">
									<col width="25%">
									<col width="10%">
									<col width="20%">
									<col width="20%">
								</colgroup>
								<thead>
									<tr>
										<th class="text-center">Expiry Date</th>
										<th class="text-center">Product</th>
										<th class="text-center">Qty</th>
										<th class="text-center">Price</th>
										<th class="text-center">Amount</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$total = 0;
									while ($row = $inv->fetch_assoc()) :

									?>
										<tr class="item-row">
											<input type="hidden" name="item_id[]" value="<?= $row['item_id'] ?>">
											<!--	<td><input type="text" name="ref_no" value="<?php echo isset($ref_no) ? $ref_no : '' ?>"></td> -->
											<td class="text-center">
												<?= ($row['expiry_date'] != null) ? date("F d, Y", strtotime($row['expiry_date'])) : '' ?>
											</td>
											<td>
												<!-- <input type="hidden" name="inv_id[]" value="<?php echo $row['id'] ?>"> -->
												<input type="hidden" name="product_id[]" value="<?php echo $row['product_id'] ?>">
												<p class="pname">Name: <b><?php echo $row['name'] ?></b></p>
												<p class="pdesc"><small><i>Description: <b><?php echo $row['description'] ?></b></i></small></p>
											</td>
											<td class="text-center">
												<?php echo $row['qty'] . ' ' . $row['selling_mode'] ?>/s
											</td>
											<td>
												<input type="number" min="1" step="any" name="price[]" value="<?php echo $row['price'] ?>" class="text-right" readonly>
											</td>
											<td>
												<?php $sub = $row['qty'] * $row['price'];
												$total += $sub ?>
												<p class="amount text-right"><?php echo  $sub ?></p>
											</td>
										</tr>
									<?php endwhile; ?>
								</tbody>
								<tfoot>
									<tr>
										<th class="text-right" colspan="4">Total</th>
										<th class="text-right tamount">₱ <?= $total ?></th>
									</tr>
								</tfoot>
							</table>
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
			<td>
				<input type="date" name="expiry_date[]" class="text-right" value="" required>
			</td>
			<td>
				<input type="hidden" name="inv_id[]" value="">
				<input type="hidden" name="product_id[]" value="">
				<p class="pname">Name: <b>product</b></p>
				<p class="pdesc"><small><i>Description: <b>Description</b></i></small></p>
			</td>
			<td>
				<input type="number" min="1" step="any" name="qty[]" value="" class="text-right">
			</td>
			<td>
				<input type="number" min="1" step="any" name="price[]" value="" class="text-right">
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
	$('#add_list').click(function() {
		// alert("TEST");
		// return false;

		var tr = $('#tr_clone tr.item-row').clone();
		var product = $('#product').val(),
			qty = $('#qty').val(),
			price = $('#price').val();
		if ($('#list').find('tr[data-id="' + product + '"]').length > 0) {
			alert_toast("Product already on the list", 'danger')
			return false;
		}
		if (product == '' || qty == '' || price == '') {
			alert_toast("Please complete the fields first", 'danger')
			return false;
		}
		tr.attr('data-id', product)
		tr.find('.pname b').html($("#product option[value='" + product + "']").attr('data-name'))
		tr.find('.pdesc b').html($("#product option[value='" + product + "']").attr('data-description'))
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
		$('[name="qty[]"],[name="price[]"]').keyup(function() {
			calculate_total()
		})
		$('#product').val('').select2({
			placeholder: "Please select here",
			width: "100%"
		})
		$('#qty').val('')
		$('#price').val('')
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
		$('#list .tamount').html(parseFloat(total).toLocaleString('en-US', {
			style: 'decimal',
			maximumFractionDigits: 2,
			minimumFractionDigits: 2
		}))
	}
	//Save Receiving



	$('#manage-receiving').submit(function(e) {
		e.preventDefault()
		start_load()
		if ($("#list .item-row").length <= 0) {
			alert_toast("Please insert atleast 1 item first.", 'danger');
			end_load();
			return false;
		}
		$.ajax({
			url: 'ajax.php?action=save_receiving',
			method: 'POST',
			data: $(this).serialize(),
			success: function(resp) {
				console.log(resp)
				if (resp == 1) {
					alert_toast("Data successfully added", 'success')
					setTimeout(function() {
						location.href = "index.php?page=receiving"
					}, 1500)

				}

			}
		})
	})

	function printOrder(id) {
		var nw = window.open("print_order.php?id=" + id, "_blank", "height=700,width=900")
		nw.print()
		setTimeout(function() {
			nw.close()
		}, 1500)
	}
</script>