<?php
class ModelVipVip extends Model {

	public function getVips($data = array()) {
		$salesman_id = $this->salesman->getId();

		$sql = "SELECT *,1 AS num, c.fullname AS bind_customer, c.telephone AS bind_customer_telephone FROM " . DB_PREFIX . "vip_card v LEFT JOIN " . DB_PREFIX . "customer c ON (v.customer_id = c.customer_id) WHERE v.salesman_id = '" . (int)$salesman_id . "'";

		$implode = array();

		if (!empty($data['filter_vip_card_num'])) {
			$implode[] = "v.vip_card_num LIKE '%" . $this->db->escape($data['filter_vip_card_num']) . "%'";
		}

		if (isset($data['filter_bind_status']) && !is_null($data['filter_bind_status'])) {
			$implode[] = "v.bind_status = '" . (int)$data['filter_bind_status'] . "'";
		}

		if (!empty($data['filter_date_bind_to_salesman_fr'])) {
			$implode[] = "DATE(v.date_bind_to_salesman) >= DATE('" . $this->db->escape($data['filter_date_bind_to_salesman_fr']) . "')";
		}

		if (!empty($data['filter_date_bind_to_salesman_to'])) {
			$implode[] = "DATE(v.date_bind_to_salesman) <= DATE('" . $this->db->escape($data['filter_date_bind_to_salesman_to']) . "')";
		}

		if (!empty($data['filter_date_bind_to_salesman_fr'])) {
			$implode[] = "DATE(v.date_bind_to_salesman) >= DATE('" . $this->db->escape($data['filter_date_bind_to_salesman_fr']) . "')";
		}

		if (!empty($data['filter_date_bind_to_salesman_to'])) {
			$implode[] = "DATE(v.date_bind_to_salesman) <= DATE('" . $this->db->escape($data['filter_date_bind_to_salesman_to']) . "')";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
// 			'num',
			'v.vip_card_num',
			'v.bind_status',
			'bind_customer',
			'bind_customer_telephone',
			'v.date_bind_to_salesman',
			'v.date_bind_to_customer'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY v.vip_card_num";
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

	public function getTotalVips($data = array()) {
		$salesman_id = $this->salesman->getId();

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "vip_card WHERE salesman_id = '" . (int)$salesman_id . "'";

		$implode = array();

		if (!empty($data['filter_vip_card_num'])) {
			$implode[] = "vip_card_num LIKE '%" . $this->db->escape($data['filter_vip_card_num']) . "%'";
		}

		if (isset($data['filter_bind_status']) && !is_null($data['filter_bind_status'])) {
			$implode[] = "bind_status = '" . (int)$data['filter_bind_status'] . "'";
		}

		if (!empty($data['filter_date_bind_to_salesman_fr'])) {
			$implode[] = "DATE(date_bind_to_salesman) >= DATE('" . $this->db->escape($data['filter_date_bind_to_salesman_fr']) . "')";
		}

		if (!empty($data['filter_date_bind_to_salesman_to'])) {
			$implode[] = "DATE(date_bind_to_salesman) <= DATE('" . $this->db->escape($data['filter_date_bind_to_salesman_to']) . "')";
		}

		if (!empty($data['filter_date_bind_to_salesman_fr'])) {
			$implode[] = "DATE(date_bind_to_salesman) >= DATE('" . $this->db->escape($data['filter_date_bind_to_salesman_fr']) . "')";
		}

		if (!empty($data['filter_date_bind_to_salesman_to'])) {
			$implode[] = "DATE(date_bind_to_salesman) <= DATE('" . $this->db->escape($data['filter_date_bind_to_salesman_to']) . "')";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}