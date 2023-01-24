<?php
session_start();
require 'sendmail.php';
ini_set('display_errors', 1);
class Action
{
	private $db;

	public function __construct()
	{
		ob_start();
		include 'db_connect.php';

		$this->db = $conn;
	}
	function __destruct()
	{
		$this->db->close();
		ob_end_flush();
	}
	function sendVerify()
	{
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where email = '$email'");
		if ($qry->num_rows > 0) {
			$code = rand(100000, 9999999);
			$data = $qry->fetch_assoc();
			$this->db->query("UPDATE users SET code = $code WHERE id = " . $data['id']);
			sendMail('Verify Account', 'Hello ' . $data['username'] . ', this is your verification code.<br>' . $code, $data['email'], $data['name']);
			return 1;
		} else {
			return 2;
		}
	}
	function codeVerify()
	{
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where email = '$email' AND code = $code");
		if ($qry->num_rows > 0) {
			$data = $qry->fetch_assoc();
			$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Verification','User verified','" . $data['id'] . "', '" . date('Y-m-d h:i:s') . "')");

			$this->db->query("UPDATE users SET status = 1 WHERE id = " . $data['id']);
			return 1;
		} else {
			return 2;
		}
	}

	function sendReset()
	{
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where email = '$email'");
		if ($qry->num_rows > 0) {
			$code = rand(100000, 9999999);
			$data = $qry->fetch_assoc();
			$this->db->query("UPDATE users SET code = $code WHERE id = " . $data['id']);
			sendMail('Password Reset', 'Hello ' . $data['username'] . ', this is your reset code.<br>' . $code, $data['email'], $data['name']);
			return 1;
		} else {
			return 2;
		}
	}
	function verifyReset()
	{
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where email = '$email' AND code = $code");
		if ($qry->num_rows > 0) {
			$data = $qry->fetch_assoc();
			$code = rand(100000, 9999999);
			$this->db->query("UPDATE users SET password='$code' WHERE id = " . $data['id']);
			$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Password Reset','Rest successful','" . $data['id'] . "', '" . date('Y-m-d h:i:s') . "')");

			sendMail('New Password', 'Hello ' . $data['username'] . ', this is your new password. Change it immediately.<br>' . $code, $data['email'], $data['name']);
			return 1;
		} else {
			return 2;
		}
	}
	function login()
	{
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '" . $username . "' and password = '" . $password . "' ");
		if ($qry->num_rows > 0) {
			$data = $qry->fetch_assoc();
			if ($data['status'] == 0) {
				return 5;
			}
			$qry = $this->db->query("SELECT * FROM users where id = " . $data['id']);
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'password' && !is_numeric($key)) {
					$_SESSION['login_' . $key] = $value;
				}
			}
			$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Auth:Login','User login','" . $_SESSION['login_id'] . "', '" . date('Y-m-d h:i:s') . "')");
			return 1;
		} else {
			return 3;
		}
	}
	function login2()
	{
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM user_info where email = '" . $email . "' and password = '" . md5($password) . "' ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			$ip = (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
			$this->db->query("UPDATE cart set user_id = '" . $_SESSION['login_user_id'] . "' where client_ip ='$ip' ");
			return 1;
		} else {
			return 3;
		}
	}
	function logout()
	{
		session_destroy();
		$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Auth:Logout','User logout','" . $_SESSION['login_id'] . "', '" . date('Y-m-d h:i:s') . "')");
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}

		header("location:login.php");
	}
	function logout2()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user()
	{
		extract($_POST);

		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		if (isset($type))
			$data .= ", type = '$type' ";
		if (empty($id)) {

			$check = $this->db->query("SELECT * FROM users WHERE email = '$email'");
			if ($check->num_rows > 0) {
				return 3;
			}

			$save = $this->db->query("INSERT INTO users set " . $data . " , password = '$username', email = '$email' , code = '0', status = 0");
			$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Create','User has been added: $name','" . $_SESSION['login_id'] . "', '" . date('Y-m-d h:i:s') . "')");
		} else {
			$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Update','User has been updated: $name','" . $_SESSION['login_id'] . "', '" . date('Y-m-d h:i:s') . "')");

			if ($password != '') {
				if ($password == $confirm) {
					$data .= ", password = '$password' ";
				} else {
					return 6;
				}
			}
			$save = $this->db->query("UPDATE users set " . $data . " where id = " . $id);
		}
		if ($save) {
			return 1;
		}
	}
	function signup()
	{
		extract($_POST);
		$data = " first_name = '$first_name' ";
		$data .= ", last_name = '$last_name' ";
		$data .= ", mobile = '$mobile' ";
		$data .= ", address = '$address' ";
		$data .= ", email = '$email' ";
		$data .= ", password = '" . md5($password) . "' ";
		$chk = $this->db->query("SELECT * FROM user_info where email = '$email' ")->num_rows;
		if ($chk > 0) {
			return 2;
			exit;
		}
		$save = $this->db->query("INSERT INTO user_info set " . $data);
		if ($save) {
			$login = $this->login2();
			return 1;
		}
	}

	function save_settings()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '" . htmlentities(str_replace("'", "&#x2019;", $about)) . "' ";
		if ($_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], '../assets/img/' . $fname);
			$data .= ", cover_img = '$fname' ";
		}

		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if ($chk->num_rows > 0) {
			$save = $this->db->query("UPDATE system_settings set " . $data . " where id =" . $chk->fetch_array()['id']);
		} else {
			$save = $this->db->query("INSERT INTO system_settings set " . $data);
		}
		if ($save) {
			$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
			foreach ($query as $key => $value) {
				if (!is_numeric($key))
					$_SESSION['setting_' . $key] = $value;
			}

			return 1;
		}
	}


	function save_category()
	{
		extract($_POST);
		$data = " name = '$name' ";

		$exception = ($id) ? ' AND id != ' . $id : '';

		$check_duplicate = $this->db->query("SELECT * FROM category_list WHERE `name` = '$name' $exception");
		if ($check_duplicate->num_rows > 0) {
			return 3;
		} else {
			if (empty($id)) {
				$save = $this->db->query("INSERT INTO category_list set " . $data);
				$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Create','New category added: $name','" . $_SESSION['login_id'] . "', '" . date('Y-m-d h:i:s') . "')");
				return 1;
			} else {

				$save = $this->db->query("UPDATE category_list set " . $data . " where id=" . $id);
				$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Update','Category updated: $name','" . $_SESSION['login_id'] . "', '" . date('Y-m-d h:i:s') . "')");
				return 2;
			}
		}
	}
	function delete_category()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM category_list where id = " . $id);
		$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Delete','Category deleted','" . $_SESSION['login_id'] . "', '" . date('Y-m-d h:i:s') . "')");
		if ($delete)
			return 1;
	}
	function save_type()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$exception = ($id) ? ' AND id != ' . $id : '';
		$check_duplicate = $this->db->query("SELECT `name` FROM type_list WHERE `name` = '$name' $exception");
		if ($check_duplicate->num_rows > 0) {
			return 3;
		} else {
			if (empty($id)) {
				$save = $this->db->query("INSERT INTO type_list set " . $data);
				$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Create','New type added: $name','" . $_SESSION['login_id'] . "', '" . date('Y-m-d h:i:s') . "')");
				return 1;
			} else {
				$save = $this->db->query("UPDATE type_list set " . $data . " where id=" . $id);
				$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Update','Type has been updated: $name','" . $_SESSION['login_id'] . "', '" . date('Y-m-d h:i:s') . "')");
				return 2;
			}
		}
	}
	function delete_type()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM type_list where id = " . $id);
		$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Delete','Type deleted','" . $_SESSION['login_id'] . "', '" . date('Y-m-d h:i:s') . "')");
		if ($delete)
			return 1;
	}
	function save_supplier()
	{
		extract($_POST);
		$data = " supplier_name = '$name' ";
		$data .= ", contact = '$contact' ";
		$data .= ", address = '$address' ";
		$data .= ", email = '$email' ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO supplier_list set " . $data);
			$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Create','Supplier added: $name','" . $_SESSION['login_id'] . "', '" . date('Y-m-d h:i:s') . "')");
		} else {
			$save = $this->db->query("UPDATE supplier_list set " . $data . " where id=" . $id);
			$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Update','Supplier has been updated: $name','" . $_SESSION['login_id'] . "', '" . date('Y-m-d h:i:s') . "')");
		}
		if ($save)
			return 1;
	}
	function delete_supplier()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM supplier_list where id = " . $id);
		$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Delete','Supplier deleted','" . $_SESSION['login_id'] . "', '" . date('Y-m-d h:i:s') . "')");
		if ($delete)
			return 1;
	}
	function save_product()
	{
		extract($_POST);
		if (empty($sku)) {
			$sku = mt_rand(1, 99999999);
			$sku = sprintf("%'08d\n", $sku);
			$i = 1;
			while ($i == 1) {
				$chk = $this->db->query("SELECT * FROM product_list where sku ='$sku'")->num_rows;
				if ($chk > 0) {
					$sku = mt_rand(1, 99999999);
					$sku = sprintf("%'08d\n", $sku);
				} else {
					$i = 0;
				}
			}
		}
		$data = " name = '$name' ";
		$data .= ", sku = '$sku' ";
		$data .= ", category_id = '" . implode(",", $category_id) . "' ";
		$data .= ", type_id = '$type_id' ";
		$data .= ", measurement = '$measurement $unit' ";
		$data .= ", description = '$description' ";
		$data .= ", price = '$price' ";
		$data .= ", selling_mode = '$mode' ";

		if (isset($prescription)) {
			$data .= ", prescription = 1 ";
		} else {
			$data .= ", prescription = 0 ";
		}


		$exception = ($id) ? ' AND id != ' . $id : '';
		$check_duplicate = $this->db->query("SELECT * FROM product_list WHERE `name` = '$name' $exception");
		if ($check_duplicate->num_rows > 0) {
			return 3;
		} else {
			if (empty($id)) {
				$save = $this->db->query("INSERT INTO product_list set " . $data);
				$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Create','New product added: $name','" . $_SESSION['login_id'] . "', '" . date('Y-m-d h:i:s') . "')");
				return 1;
			} else {
				$save = $this->db->query("UPDATE product_list set " . $data . " where id=" . $id);
				$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Update','Product has been updated: $name','" . $_SESSION['login_id'] . "', '" . date('Y-m-d h:i:s') . "')");
				return 2;
			}
		}
	}

	function delete_product()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM product_list where id = " . $id);
		$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Delete','Product deleted','" . $_SESSION['login_id'] . "', '" . date('Y-m-d h:i:s') . "')");
		if ($delete)
			return 1;
	}
	function delete_user()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = " . $id);
		$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Delete','User deleted','" . $_SESSION['login_id'] . "', '" . date('Y-m-d h:i:s') . "')");
		if ($delete)
			return 1;
	}


	//Save Receiving

	function save_receiving()
	{
		extract($_POST);
		// print_r($_POST);
		if (isset($id)) {
			$this->db->query("UPDATE orders set status = 2, batch_no = '$batch' WHERE id = $id");
			foreach ($product_id as $k => $v) {

				$data = " product_id = '$product_id[$k]' ";
				$data .= ", batch_no = '$batch' ";
				$data .= ", qty = '$qty[$k]' ";
				$data .= ", expiry_date = '$expiry_date[$k]' ";
				$this->db->query("UPDATE order_details set expiry_date = '" . $expiry_date[$k] . "' WHERE id = " . $item_id[$k]);
				$save2[] = $this->db->query("INSERT INTO inventory set " . $data);
			}
			if (isset($save2)) {
				$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Create','Received Order Batch: $batch','" . $_SESSION['login_id'] . "', '" . date('Y-m-d h:i:s') . "')");
				return 1;
			}
		} else {
			$save = $this->db->query("UPDATE receiving_list set " . $data . " where id =" . $id);
			$ids = implode(",", $inv_id);
			$this->db->query("DELETE FROM inventory where type = 1 and form_id ='$id' and id NOT IN (" . $ids . ") ");
			foreach ($product_id as $k => $v) {
				$data = " form_id = '$id' ";
				$data .= ", product_id = '$product_id[$k]' ";
				$data .= ", qty = '$qty[$k]' ";
				$data .= ", type = '1' ";
				$data .= ", stock_from = 'receiving' ";
				$details = json_encode(array('price' => $price[$k], 'qty' => $qty[$k]));
				$data .= ", other_details = '$details' ";
				$data .= ", remarks = '$ref_no' ";
				if (!empty($inv_id[$k])) {
					$save2[] = $this->db->query("UPDATE inventory set " . $data . " where id=" . $inv_id[$k]);
				} else {
					$save2[] = $this->db->query("INSERT INTO inventory set " . $data);
				}
			}
			if (isset($save2)) {
				$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Update','Order details updated','" . $_SESSION['login_id'] . "', '" . date('Y-m-d h:i:s') . "')");
				return 1;
			}
		}
	}

	// SAVE ORDERS
	function save_order()
	{
		extract($_POST);
		$data = " supplier_id = '$supplier_id' ";
		$data .= ", total_amount = '$tamount' ";
		$data .= ", status = '1' ";
		// if (empty($id)) {
		$ref_no = mt_rand(1, 99999999);
		$ref_no = sprintf("%'08d\n", $ref_no);
		$i = 1;

		while ($i == 1) {
			$chk = $this->db->query("SELECT * FROM orders where ref_no ='$ref_no'")->num_rows;
			if ($chk > 0) {
				$ref_no = mt_rand(1, 99999999);
				$ref_no = sprintf("%'.08d\n", $ref_no);
			} else {
				$i = 0;
			}
		}
		$data .= ", ref_no = '$ref_no' ";
		$data .= ", batch_no = null ";
		$save = $this->db->query("INSERT INTO orders set " . $data);
		$id = $this->db->insert_id;
		foreach ($product_id as $k => $v) {
			$data = " order_id = '$id' ";
			$data .= ", product_id = '$product_id[$k]' ";
			$data .= ", qty = '$qty[$k]' ";
			$data .= ", expiry_date = null ";


			$save2[] = $this->db->query("INSERT INTO order_details set " . $data);
		}
		if (isset($save2)) {
			$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Create','Order Created - Ref #: $ref_no','" . $_SESSION['login_id'] . "', '" . date('Y-m-d h:i:s') . "')");
			return $id;
		}
		// } else {

		// 	$save = $this->db->query("UPDATE order_list set " . $data . " where id =" . $id);
		// 	$ids = implode(",", $inv_id);
		// 	$this->db->query("DELETE FROM order_details where type = 1 and form_id ='$id' and id NOT IN (" . $ids . ") ");
		// 	foreach ($product_id as $k => $v) {
		// 		$data = " form_id = '$id' ";
		// 		$data .= ", product_id = '$product_id[$k]' ";
		// 		$data .= ", qty = '$qty[$k]' ";
		// 		$data .= ", type = '1' ";
		// 		$data .= ", stock_from = 'receiving' ";
		// 		$details = json_encode(array('price' => $price[$k], 'qty' => $qty[$k]));
		// 		$data .= ", other_details = '$details' ";
		// 		$data .= ", remarks = '$ref_no' ";
		// 		if (!empty($inv_id[$k])) {
		// 			$save2[] = $this->db->query("UPDATE order_details set " . $data . " where id=" . $inv_id[$k]);
		// 		} else {
		// 			$save2[] = $this->db->query("INSERT INTO order_details set " . $data);
		// 		}
		// 		return $id;
		// 	}
		// 	if (isset($save2)) {

		// 		return $id;
		// 	}
		// }
	}
	function update_order()
	{
		extract($_POST);
		$this->db->query("DELETE FROM orders WHERE id = $id");
		$data = " supplier_id = '$supplier_id' ";
		$data .= ", total_amount = '$tamount' ";
		$data .= ", status = '1' ";
		$data .= ", ref_no = '$ref_no' ";
		$data .= ", batch_no = null ";
		$save = $this->db->query("INSERT INTO orders set " . $data);
		$id = $this->db->insert_id;
		foreach ($product_id as $k => $v) {
			$data = " order_id = '$id' ";
			$data .= ", product_id = '$product_id[$k]' ";
			$data .= ", qty = '$qty[$k]' ";
			$data .= ", expiry_date = null ";


			$save2[] = $this->db->query("INSERT INTO order_details set " . $data);
		}
		if (isset($save2)) {
			$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Update','Updated Order - Ref #: $ref_no','" . $_SESSION['login_id'] . "', '" . date('Y-m-d h:i:s') . "')");
			return $id;
		}
	}
	// END
	// DELETE ORDER
	function delete_order()
	{
		extract($_POST);
		$del1 = $this->db->query("DELETE FROM orders where id = $id ");
		$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Delete','Order deleted','" . $_SESSION['login_id'] . "', '" . date('Y-m-d h:i:s') . "')");
		if ($del1)
			return 1;
	}
	// END



	function chk_prod_availability()
	{
		extract($_POST);
		$data = $this->db->query("SELECT i.qty, p.price FROM inventory i INNER JOIN product_list p ON i.product_id = p.id where i.id = " . $id)->fetch_assoc();
		return json_encode(array('available' => $data['qty'], 'price' => $data['price']));
	}

	function save_sales()
	{
		extract($_POST);

		$data = " user_id = " . $_SESSION['login_id'];
		$data .= ", total_amount = '$tamount' ";
		$data .= ", amount_tendered = '$amount_tendered' ";
		$data .= ", amount_change = '$change' ";

		if (empty($id)) {
			$ref_no = mt_rand(1, 99999999);
			$ref_no = sprintf("%'.08d\n", $ref_no);
			$i = 1;

			while ($i == 1) {
				$chk = $this->db->query("SELECT * FROM sales where ref_no ='$ref_no'")->num_rows;
				if ($chk > 0) {
					$ref_no = mt_rand(1, 99999999);
					$ref_no = sprintf("%'.08d\n", $ref_no);
				} else {
					$i = 0;
				}
			}
			$data .= ", ref_no = '$ref_no' ";
			$save = $this->db->query("INSERT INTO sales set " . $data);
			// return "INSERT INTO sales set " . $data;
			$id = $this->db->insert_id;
			foreach ($product_id as $k => $v) {
				// decrease qty
				$this->db->query("UPDATE inventory SET qty = qty - " . $qty[$k] . " WHERE id = " . $product_id[$k]);
				$this->db->query("INSERT INTO `sales_items`( `sales_id`,`ref_no`, `inventory_id`, `qty`, `price`, `amount`) VALUES ('$id','$ref_no','" . $product_id[$k] . "','" . $qty[$k] . "','" . $price[$k] . "','" . ($qty[$k] * $price[$k]) . "')");
			}
			if (isset($save)) {
				$this->db->query("INSERT INTO `logs`( `type`, `message`, `user_id`, `date_updated`) VALUES ('Create','New Sales Ref #: $ref_no','" . $_SESSION['login_id'] . "', '" . date('Y-m-d h:i:s') . "')");
				return $id;
			}
		} else {
			$save = $this->db->query("UPDATE sales set " . $data . " where id=" . $id);
			$ids = implode(",", $inv_id);
			$this->db->query("DELETE FROM inventory where type = 1 and form_id ='$id' and id NOT IN (" . $ids . ") ");
			foreach ($product_id as $k => $v) {
				$data = " form_id = '$id' ";
				$data .= ", product_id = '$product_id[$k]' ";
				$data .= ", qty = '$qty[$k]' ";
				$data .= ", type = '2' ";
				$data .= ", stock_from = 'Sales' ";
				$details = json_encode(array('price' => $price[$k], 'qty' => $qty[$k]));
				$data .= ", other_details = '$details' ";
				$data .= ", remarks = 'Stock out from Sales-" . $ref_no . "' ";

				if (!empty($inv_id[$k])) {
					$save2[] = $this->db->query("UPDATE inventory set " . $data . " where id=" . $inv_id[$k]);
				} else {
					$save2[] = $this->db->query("INSERT INTO inventory set " . $data);
				}
			}
			if (isset($save2)) {
				return $id;
			}
		}
	}
}
