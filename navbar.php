<style>
</style>
<nav id="sidebar" class='mx-lt-5' style="background-color: #06283D;">

	<div class="sidebar-list">

		<a href="index.php?page=home" class="nav-item nav-home"><span class='icon-field'><i class="fa fa-home"></i></span> Dashboard</a>

		<a href="index.php?page=supplier" class="nav-item nav-supplier"><span class='icon-field'><i class="fa fa-truck-loading"></i></span> Suppliers</a>

		<a href="index.php?page=product" class="nav-item nav-product"><span class='icon-field'><i class="fa fa-boxes"></i></span> Product Management</a>

		<a href="index.php?page=receiving" class="nav-item nav-receiving nav-manage_receiving nav-get_order"><span class='icon-field'><i class="fa fa-file-alt"></i></span> Ordering</a>
		<a href="index.php?page=pos" class="nav-item nav-pos"><span class='icon-field'><i class="fa fa-coins"></i></span> Point of Sale</a>
		<a href="index.php?page=sales_list" class="nav-item nav-sales_list nav-view-sales"><span class='icon-field'><i class="fas fa-hand-holding-usd"></i></span> Sales List</a>
		<a href="index.php?page=inventory" class="nav-item nav-inventory"><span class='icon-field'><i class="fa fa-list"></i></span> Inventory</a>
		<!-- <a href="index.php?page=categories" class="nav-item nav-categories"><span class='icon-field'><i class="fa fa-list"></i></span> Product Category</a>
				<a href="index.php?page=types" class="nav-item nav-types"><span class='icon-field'><i class="fa fa-th-list"></i></span> Product Types</a> -->


		<?php if ($_SESSION['login_type'] == 1) : ?>
			<a href="index.php?page=users" class="nav-item nav-users"><span class='icon-field'><i class="fa fa-users"></i></span> Users</a>
			<a href="index.php?page=logs" class="nav-item nav-logs"><span class='icon-field'><i class="fas fa-history"></i></span> History</a>
		<?php endif; ?>

	</div>

</nav>
<script>
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>
<?php if ($_SESSION['login_type'] != 1) : ?>
	<style>
		.nav-item {
			display: none !important;
		}

		.nav-pos,
		.nav-home,
		.nav-inventory,
		.nav-sales_list {
			display: block !important;
		}
	</style>
<?php endif ?>