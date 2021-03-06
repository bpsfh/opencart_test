<?php
/**
 * @author HU
 */
class ModelSalesmanUser extends Model {

	/**
	 * 根据筛选条件查找已批准的业务员
	 * @param $data 筛选条件
	 */
	public function getSalesmans($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "salesman WHERE application_status = '2'";

		// 查询条件作成
		$implode = array();

		// 业务员名称
		if (!empty($data['filter_name'])) {
			$implode[] = "fullname LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		// 业务员邮箱号
		if (!empty($data['filter_email'])) {
			$implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		// 创建日期
		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		// 批准日期
		if (!empty($data['filter_date_approved'])) {
			$implode[] = "DATE(date_approved) = DATE('" . $this->db->escape($data['filter_date_approved']) . "')";
		}

		// 状态
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "status = '" . (int)$data['filter_status'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
				'salesman_id',
				'fullname',
				'email',
				'date_added',
				'date_approved',
				'status'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY salesman_id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/**
	 * 查找某一业务员相关信息
	 * @param $salesman_id 业务员id
	 */
	public function getSalesman($salesman_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "salesman WHERE salesman_id = '" . (int)$salesman_id . "'");

		return $query->row;
	}

	/**
	 * 符合条件的数量
	 * @param $data 筛选条件
	 */
	public function getTotalSalesmans($data = array()) {
		$sql = "SELECT COUNT(*) AS TOTAL FROM " . DB_PREFIX . "salesman WHERE application_status = '2'";

		// 查询条件作成
		$implode = array();

		// 业务员名称
		if (!empty($data['filter_name'])) {
			$implode[] = "fullname LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		// 业务员邮箱号
		if (!empty($data['filter_email'])) {
			$implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		// 创建日期
		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		// 批准日期
		if (!empty($data['filter_date_approved'])) {
			$implode[] = "DATE(date_approved) = DATE('" . $this->db->escape($data['filter_date_approved']) . "')";
		}

		// 状态
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "status = '" . (int)$data['filter_status'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['TOTAL'];
	}

	/**
	 * 根据筛选条件查找没有审核通过的业务员
	 * @param $data 筛选条件
	 */
	public function getApplications($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "salesman ";

		// 查询条件作成
		$implode = array();

		// 业务员名称
		if (!empty($data['filter_name'])) {
			$implode[] = "fullname LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		// 业务员邮箱号
		if (!empty($data['filter_email'])) {
			$implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		// 申请状态
		if (!empty($data['filter_status'])) {
			if ($data['filter_status'] == '5') {
				$implode[] = "application_status IN ('1', '4')";
			} else {
				$implode[] = "application_status = '" . (int)$data['filter_status'] . "'";
			}
		} else {
			$implode[] = "application_status IN ('1', '2', '3', '4')";
		}

		// 首次申请时间
		if (!empty($data['filter_date_first_applied'])) {
			$implode[] = "DATE(date_first_applied) = DATE('" . $this->db->escape($data['filter_date_first_applied']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
				'fullname',
				'email',
				'date_first_applied',
				'application_status'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY fullname";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/**
	 * 符合条件的数量
	 * @param $data
	 */
	public function getTotalApplications($data = array()) {
		$sql = "SELECT COUNT(*) AS TOTAL FROM " . DB_PREFIX . "salesman ";

		// 查询条件作成
		$implode = array();

		// 业务员名称
		if (!empty($data['filter_name'])) {
			$implode[] = "fullname LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		// 业务员邮箱号
		if (!empty($data['filter_email'])) {
			$implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		// 申请状态
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			if ($data['filter_status'] == '5') {
				$implode[] = "application_status IN ('1', '4')";
			} else {
				$implode[] = "application_status = '" . (int)$data['filter_status'] . "'";;
			}
		} else {
			$implode[] = "application_status IN ('1', '2', '3', '4')";
		}

		// 首次申请时间
		if (!empty($data['filter_date_first_applied'])) {
			$implode[] = "DATE(date_first_applied) = DATE('" . $this->db->escape($data['filter_date_first_applied']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['TOTAL'];
	}

	/**
	 * 取出相关用户的申请信息
	 */
	public function getApplication($salesman_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "salesman WHERE salesman_id = '" . (int)$salesman_id . "'");

		return $query->row;
	}

	/**
	 * 批准申请为业务员
	 */
	public function approve($data) {
		$this->db->query("UPDATE " . DB_PREFIX . "salesman SET application_status = '" . (int)$data['status'] . "', date_approved = NOW() WHERE salesman_id = '" . (int)$data['salesman_id'] . "'");
	}

	/**
	 * 拒绝申请为业务员
	 */
	public function reject($data) {
		$this->db->query("UPDATE " . DB_PREFIX . "salesman SET application_status = '" . (int)$data['status'] . "' WHERE salesman_id = '" . (int)$data['salesman_id'] . "'");
	}

	/**
	 * 添加新业务员
	 * @param $data 业务员信息
	 */
	public function addSalesman($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "salesman SET fullname = '" . $this->db->escape($data['fullname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', image = '" . $this->db->escape($data['image'])
				. "', newsletter = '" . (int)$data['newsletter'] . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', status = '" . (int)$data['status'] . "', approved = '" . (int)$data['approved'] . "', safe = '" . (int)$data['safe']
				. "', application_status = '2'" . ", date_added = NOW()" . ", date_approved = NOW()" . ", date_first_applied = NOW(), parent_id = '0', level = '1', with_grant_opt = '1'");

		$salesman_id = $this->db->getLastId();

		$this->db->query("INSERT INTO " . DB_PREFIX . "salesman_address SET salesman_id = '" . (int)$salesman_id . "', fullname = '" . $this->db->escape($data['shipping_fullname']) . "', company = '" . $this->db->escape($data['company']) . "', address = '" . $this->db->escape($data['address'])
				. "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "'");

		$address_id = $this->db->getLastId();

		$this->db->query("UPDATE " . DB_PREFIX . "salesman SET address_id = '" . (int)$address_id . "' WHERE salesman_id = '" . (int)$salesman_id . "'");

		// 业务员申请履历添加
		// 业务员创建履历
		$this->db->query('INSERT INTO ' . DB_PREFIX . "salesman_apply_record SET salesman_id = '" . (int)$salesman_id . "', status = '0'"
				.", date_processed = NOW()");
		// 批准业务员申请履历
		$this->db->query('INSERT INTO ' . DB_PREFIX . "salesman_apply_record SET salesman_id = '" . (int)$salesman_id . "', status = '2'"
				.", date_processed = NOW()");
		// 业务员申请个人认证图片
		// $this->load->model ( 'salesman/upload' );

		// $this->model_salesman_upload->addUpload ($salesman_id, $data);
	}

	/**
	 * 更新业务员信息
	 * @param $salesman_id 业务员id
	 * @param $data 业务员信息
	 */
	public function editSalesman($salesman_id, $data) {

		$this->db->query("UPDATE " . DB_PREFIX . "salesman SET fullname = '" . $this->db->escape($data['fullname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', image = '" . $this->db->escape($data['image'])
				. "', newsletter = '" . (int)$data['newsletter'] . "', status = '" . (int)$data['status'] . "', approved = '" . (int)$data['approved'] . "', safe = '" . (int)$data['safe'] . "' WHERE salesman_id = '" . (int)$salesman_id . "'");

		if ($data['password']) {
			$this->db->query("UPDATE " . DB_PREFIX . "salesman SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE salesman_id = '" . (int)$salesman_id . "'");
		}

		if (! empty($data['address_id'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "salesman_address SET fullname = '" . $this->db->escape($data['shipping_fullname']) . "',  address = '" . $this->db->escape($data['address']) . "',  company = '" . $this->db->escape($data['company']). "' , postcode = '" . $this->db->escape($data['postcode']) . "', city = '" . $this->db->escape($data['city']) . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" . (int)$data['country_id'] . "', shipping_telephone = '" . $this->db->escape($data['shipping_telephone']) . "' WHERE address_id  = '" . (int)$data['address_id']. "'");

		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "salesman_address SET salesman_id = '" . (int)$salesman_id . "', fullname = '" . $this->db->escape($data['shipping_fullname']) . "', company = '" . $this->db->escape($data['company']) . "', address = '" . $this->db->escape($data['address'])
					. "', city = '" . $this->db->escape($data['city']) . "', shipping_telephone = '" . $this->db->escape($data['shipping_telephone']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "'");

			$address_id = $this->db->getLastId();

			$this->db->query("UPDATE " . DB_PREFIX . "salesman SET address_id = '" . (int)$address_id . "' WHERE salesman_id = '" . (int)$salesman_id . "'");
		}
		// 业务员申请个人认证图片
		// $this->load->model ( 'salesman/upload' );

		// $this->model_salesman_upload->editUpload ( $data['upload_id'], $data);
	}

	/**
	 * 通过输入的email查找相关的业务员信息
	 * @param $email 业务员email
	 */
	public function getSalesmanByEmail($email) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "salesman WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	/**
	 * 取得地址详细信息
	 * @param $address_id
	 * @return 某条地址信息
	 */
	public function getAddress($address_id) {
	$address_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "salesman_address WHERE address_id = '" . (int)$address_id . "'");

		if ($address_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$address_data = array(
				'address_id'     => $address_query->row['address_id'],
				'fullname'       => $address_query->row['fullname'],
				'company'        => $address_query->row['company'],
				'address'        => $address_query->row['address'],
				'postcode'       => $address_query->row['postcode'],
				'shipping_telephone'       => $address_query->row['shipping_telephone'],
				'city'           => $address_query->row['city'],
				'zone_id'        => $address_query->row['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $address_query->row['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format
			);

			return $address_data;
		} else {
			return false;
		}
	}

	/**
	 * 取得某业务员的所有地址信息
	 * @param $salesman_id 业务员id
	 * @return 所有地址id
	 */
// 	public function getAddresses($salesman_id) {
// 		$address_data = array();

// 		$query = $this->db->query("SELECT address_id FROM " . DB_PREFIX . "salesman_address WHERE salesman_id = '" . (int)$salesman_id . "'");

// 		foreach ($query->rows as $result) {
// 			$address_info = $this->getAddress($result['address_id']);

// 			if ($address_info) {
// 				$address_data[$result['address_id']] = $address_info;
// 			}
// 		}

// 		return $address_data;
// 	}
}