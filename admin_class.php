<?php
session_start();
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Manila");
require __DIR__ . '/vendor/autoload.php';
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

	function login()
	{
		extract($_POST);
		$type = array("", "users", "faculty_list", "student_list");
		$type2 = array("", "admin", "faculty", "student");

		// Only allow admin users to log in
		$login = 1;

		// Query to fetch user details
		$qry = $this->db->query("SELECT *, concat(firstname, ' ', lastname) as name FROM {$type[$login]} 
                     WHERE email = '" . $email . "' AND password = '" . md5($password) . "'");

		if ($qry->num_rows > 0) {
			$row = $qry->fetch_assoc();

			// Check if the user is verified
			if ($row['isVerified'] == 1) {
				// Store user details in session, excluding sensitive fields like 'password'
				foreach ($row as $key => $value) {
					if ($key != 'password' && !is_numeric($key)) {
						$_SESSION['login_' . $key] = $value;
					}
				}
				$_SESSION['login_type'] = $login;
				$_SESSION['login_view_folder'] = $type2[$login] . '/';

				// // Fetch academic details
				// $academic = $this->db->query("SELECT * FROM academic_list WHERE is_default = 1");
				// if ($academic->num_rows > 0) {
				//     foreach ($academic->fetch_assoc() as $k => $v) {
				//         if (!is_numeric($k)) {
				//             $_SESSION['academic'][$k] = $v;
				//         }
				//     }
				// }
				return 1; // Login successful
			} else {
				return 3; // Account not verified
			}
		} else {
			return 2; // Invalid credentials
		}
	}
	function logout()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:index.php");
	}
	function login2()
	{
		extract($_POST);
		$qry = $this->db->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM students where student_code = '" . $student_code . "' ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'password' && !is_numeric($key))
					$_SESSION['rs_' . $key] = $value;
			}
			return 1;
		} else {
			return 3;
		}
	}
	function save_user()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass', 'password')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if (!empty($password)) {
			$data .= ", password=md5('$password') ";

		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";

		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO users set $data");
		} else {
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if ($save) {
			return 1;
		}
	}
	function signup()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass')) && !is_numeric($k)) {
				if ($k == 'password') {
					if (empty($v))
						continue;
					$v = md5($v);

				}
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}

		$check = $this->db->query("SELECT * FROM users where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";

		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO users set $data");

		} else {
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if ($save) {
			if (empty($id))
				$id = $this->db->insert_id;
			foreach ($_POST as $key => $value) {
				if (!in_array($key, array('id', 'cpass', 'password')) && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			$_SESSION['login_id'] = $id;
			if (isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
				$_SESSION['login_avatar'] = $fname;
			return 1;
		}
	}

	function update_user()
	{
		extract($_POST);
		$data = "";
		$type = array("", "users", "faculty_list", "student_list");
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass', 'table', 'password')) && !is_numeric($k)) {

				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$check = $this->db->query("SELECT * FROM {$type[$_SESSION['login_type']]} where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";

		}
		if (!empty($password))
			$data .= " ,password=md5('$password') ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO {$type[$_SESSION['login_type']]} set $data");
		} else {
			echo "UPDATE {$type[$_SESSION['login_type']]} set $data where id = $id";
			$save = $this->db->query("UPDATE {$type[$_SESSION['login_type']]} set $data where id = $id");
		}

		if ($save) {
			foreach ($_POST as $key => $value) {
				if ($key != 'password' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			if (isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
				$_SESSION['login_avatar'] = $fname;
			return 1;
		}
	}
	function delete_user()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = " . $id);
		if ($delete)
			return 1;
	}
	function save_system_settings()
	{
		extract($_POST);
		$data = '';
		foreach ($_POST as $k => $v) {
			if (!is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if ($_FILES['cover']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['cover']['name'];
			$move = move_uploaded_file($_FILES['cover']['tmp_name'], '../assets/uploads/' . $fname);
			$data .= ", cover_img = '$fname' ";

		}
		$chk = $this->db->query("SELECT * FROM system_settings");
		if ($chk->num_rows > 0) {
			$save = $this->db->query("UPDATE system_settings set $data where id =" . $chk->fetch_array()['id']);
		} else {
			$save = $this->db->query("INSERT INTO system_settings set $data");
		}
		if ($save) {
			foreach ($_POST as $k => $v) {
				if (!is_numeric($k)) {
					$_SESSION['system'][$k] = $v;
				}
			}
			if ($_FILES['cover']['tmp_name'] != '') {
				$_SESSION['system']['cover_img'] = $fname;
			}
			return 1;
		}
	}
	function save_image()
	{
		extract($_FILES['file']);
		if (!empty($tmp_name)) {
			$fname = strtotime(date("Y-m-d H:i")) . "_" . (str_replace(" ", "-", $name));
			$move = move_uploaded_file($tmp_name, 'assets/uploads/' . $fname);
			$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
			$hostName = $_SERVER['HTTP_HOST'];
			$path = explode('/', $_SERVER['PHP_SELF']);
			$currentPath = '/' . $path[1];
			if ($move) {
				return $protocol . '://' . $hostName . $currentPath . '/assets/uploads/' . $fname;
			}
		}
	}


	function save_academic()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM academic_list where (" . str_replace(",", 'and', $data) . ") and id != '{$id}' ")->num_rows;
		if ($chk > 0) {
			return 2;
		}
		$hasDefault = $this->db->query("SELECT * FROM academic_list where is_default = 1")->num_rows;
		if ($hasDefault == 0) {
			$data .= " , is_default = 1 ";
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO academic_list set $data");
		} else {
			$save = $this->db->query("UPDATE academic_list set $data where id = $id");
		}
		if ($save) {
			return 1;
		}
	}
	function delete_academic()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM academic_list where id = $id");
		if ($delete) {
			return 1;
		}
	}
	function make_default()
	{
		extract($_POST);
		$update = $this->db->query("UPDATE academic_list set is_default = 0");
		$update1 = $this->db->query("UPDATE academic_list set is_default = 1 where id = $id");
		$qry = $this->db->query("SELECT * FROM academic_list where id = $id")->fetch_array();
		if ($update && $update1) {
			foreach ($qry as $k => $v) {
				if (!is_numeric($k))
					$_SESSION['academic'][$k] = $v;
			}

			return 1;
		}
	}

	function save_faculty()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass', 'password')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if (!empty($password)) {
			$data .= ", password=md5('$password') ";

		}
		$check = $this->db->query("SELECT * FROM faculty_list where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		$check = $this->db->query("SELECT * FROM faculty_list where school_id ='$school_id' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 3;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";

		}
		if (isset($_FILES['signature']) && $_FILES['signature']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['signature']['name'];
			$move = move_uploaded_file($_FILES['signature']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", signature = '$fname' ";

		}

		if (empty($id)) {
			$save = $this->db->query("INSERT INTO faculty_list set $data");
		} else {
			$save = $this->db->query("UPDATE faculty_list set $data where id = $id");
		}

		if ($save) {
			return 1;
		}
	}
	function delete_faculty()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM faculty_list where id = " . $id);
		if ($delete)
			return 1;
	}

	function save_category()
	{
		extract($_POST);

		// sanitize values
		$category_name = $this->db->real_escape_string($category_name);
		$category_description = $this->db->real_escape_string($category_description);
		$tags = $this->db->real_escape_string($tags); // comma-separated tags

		if (empty($cat_id)) {
			$insert = $this->db->query("INSERT INTO categories_list 
            (category_name, category_description, tags, date_added) 
            VALUES ('$category_name', '$category_description', '$tags', NOW())");
			if ($insert)
				return 1;
		} else {
			$update = $this->db->query("UPDATE categories_list SET 
            category_name = '$category_name',
            category_description = '$category_description',
            tags = '$tags'
            WHERE cat_id = $cat_id");
			if ($update)
				return 1;
		}
	}


	function delete_category()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM categories_list where cat_id = " . $id);
		if ($delete)
			return 1;
	}

	function save_materials()
	{
		extract($_POST);

		$material_name = $this->db->real_escape_string($material_name);
		$category = intval($category);
		$initial_quantity = intval($initial_quantity);
		$unit = $this->db->real_escape_string($unit);

		if (empty($material_id)) {
			// Insert
			$insert = $this->db->query("INSERT INTO materials_list 
            (material_name, category_id, initial_quantity, unit, date_added) 
            VALUES ('$material_name', '$category', '$initial_quantity', '$unit', NOW())");

			if ($insert) {
				return 1;
			} else {
				return 0;
			}
		} else {
			// Update
			$update = $this->db->query("UPDATE materials_list SET 
            material_name = '$material_name',
            category_id = '$category',
            initial_quantity = '$initial_quantity',
            unit = '$unit'
            WHERE material_id = $material_id");

			if ($update) {
				return 1;
			} else {
				return 0;
			}
		}
	}


	function delete_material()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM materials_list where material_id = " . $id);
		if ($delete)
			return 1;
	}

	function save_employee()
	{
		$employee_id = $_POST['employee_id'] ?? '';
		$eIDno = $_POST['eIDno'];
		$empType = $_POST['empType'];
		$preName = $_POST['preName'] ?? '';
		$lName = $_POST['lName'];
		$fName = $_POST['fName'];
		$mName = $_POST['mName'] ?? '';
		$sName = $_POST['sName'] ?? '';

		if ($employee_id) {
			// Update existing employee
			$query = "UPDATE employees SET 
                    eIDno=?, empType=?, preName=?, lName=?, fName=?, mName=?, sName=?
                  WHERE employee_id=?";
			$stmt = $this->db->prepare($query);
			$stmt->bind_param("sssssssi", $eIDno, $empType, $preName, $lName, $fName, $mName, $sName, $employee_id);
		} else {
			// Insert new employee
			$query = "INSERT INTO employees (eIDno, empType, preName, lName, fName, mName, sName) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
			$stmt = $this->db->prepare($query);
			$stmt->bind_param("sssssss", $eIDno, $empType, $preName, $lName, $fName, $mName, $sName);
		}

		if ($stmt->execute()) {
			return 1;

		} else {
			echo $stmt->error;
		}
	}


	function delete_employee()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM employees where employee_id = " . $id);
		if ($delete)
			return 1;
	}

	function save_distribution()
	{
		$distribution_id = $_POST['distribution_id'] ?? null;
		$material_id = $_POST['material_id'];
		$employee_id = $_POST['employee_id'];
		$quantity = $_POST['quantity'];

		// check current stock
		$check = $this->db->query("SELECT initial_quantity FROM materials_list WHERE material_id = '$material_id'");
		$row = $check->fetch_assoc();
		$current_stock = $row['initial_quantity'];

		if ($quantity > $current_stock) {
			return 2;
		} else {
			// insert into distribution_list
			$stmt = $this->db->prepare("INSERT INTO distribution_list (material_id, employee_id, quantity, date_distributed) VALUES (?, ?, ?, NOW())");
			$stmt->bind_param("iii", $material_id, $employee_id, $quantity);
			$stmt->execute();

			// update stock in materials_list
			// $new_stock = $current_stock - $quantity;
			// $this->db->query("UPDATE materials_list SET initial_quantity = '$new_stock' WHERE material_id = '$material_id'");

			return 1;
		}
	}

	function save_restock()
	{
		$id = (int) $_POST['material_id'];
		$qty = (int) $_POST['restock_quantity'];

		if ($id && $qty > 0) {
			$stmt = $this->db->prepare("INSERT INTO restock_list(material_id, quantity) VALUES(?, ?)");
			$stmt->bind_param("ii", $id, $qty);
			if ($stmt->execute()) {
				return 1; // success
			} else {
				return 0; // error
			}
		} else {
			return 0;
		}

	}

	function get_restock_list()
	{
		$material_id = (int) $_POST['material_id'];
		$result = $this->db->query("SELECT quantity, date_added FROM restock_list WHERE material_id = $material_id ORDER BY date_added DESC");
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		echo json_encode($data);
	}

	function fetch_materials()
	{
		$month = $_POST['month'] ?? '';
		$year = $_POST['year'] ?? '';
		// Build filter for distributed quantity
		$distFilter = "";
		if ($month)
			$distFilter .= " AND MONTH(d2.date_distributed) = '$month'";
		if ($year)
			$distFilter .= " AND YEAR(d2.date_distributed) = '$year'";


		$restockFilter = "";
		if ($month)
			$restockFilter .= " AND MONTH(r.date_restocked) = '$month'";
		if ($year)
			$restockFilter .= " AND YEAR(r.date_restocked) = '$year'";

		$where = "";
		if ($month && $year) {
			$where = "AND MONTH(d.date_distributed) = '$month' AND YEAR(d.date_distributed) = '$year'";
		} elseif ($year) {
			$where = "AND YEAR(d.date_distributed) = '$year'";
		} elseif ($month) {
			$where = "AND MONTH(d.date_distributed) = '$month'";
		}

		$qry = $this->db->query("
    SELECT 
        m.*,
        c.category_name,
		 IFNULL((SELECT SUM(r.quantity) FROM restock_list r WHERE r.material_id = m.material_id $restockFilter),0) AS qty_restocked,
        (m.initial_quantity 
            + IFNULL((SELECT SUM(r.quantity) FROM restock_list r WHERE r.material_id = m.material_id), 0)
            - IFNULL((SELECT SUM(d2.quantity) FROM distribution_list d2 
                        WHERE d2.material_id = m.material_id
                        " . ($month ? "AND MONTH(d2.date_distributed) = '$month'" : "") . "
                        " . ($year ? "AND YEAR(d2.date_distributed) = '$year'" : "") . "),0)
        ) AS qty_now,
		  IFNULL((SELECT SUM(d3.quantity) FROM distribution_list d3 
                        WHERE d3.material_id = m.material_id $distFilter),0) AS qty_distributed
    FROM materials_list m
    LEFT JOIN categories_list c ON m.category_id = c.cat_id
    ORDER BY m.date_added ASC
");


		$i = 1;
		if ($qry->num_rows > 0) {
			while ($row = $qry->fetch_assoc()) {
				$initial = (int) $row['initial_quantity'];
				$now = (int) $row['qty_now'];
				$distributed = (int) $row['qty_distributed'];
				 $restocked = (int) $row['qty_restocked'];
				$percent = $initial > 0 ? ($now / $initial) * 100 : 0;

				if ($percent >= 70) {
					$level = 'High';
					$badge = 'badge-success';
				} elseif ($percent >= 30) {
					$level = 'Medium';
					$badge = 'badge-warning';
				} else {
					$level = 'Low';
					$badge = 'badge-danger';
				}

				$hashed_link = hash('sha256', $row['material_id']);
				?>
				<tr>
					<td><?= $i++ ?></td>
					<td><?= htmlspecialchars($row['material_name']) ?></td>
					<td><?= htmlspecialchars($row['category_name']) ?></td>
					<td>
						<span class="font-weight-bold"><?= $initial + $restocked ?> </span><br>

					</td>
					<td><?= $now ?></td>
					

					<td><?= $distributed ?></td> <!-- New column -->
					<td><?= htmlspecialchars($row['unit']) ?></td>
					<td><span class="badge <?= $badge ?>"><?= $level ?></span></td>

				</tr>
				<?php
			}
		} else {
			echo '<tr class="text-center"><td colspan="9">No Materials Found!</td></tr>';
		}

	}
}