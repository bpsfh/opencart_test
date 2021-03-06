<?php
class ModelSalesmanUser extends Model {
	public function addSalesman($data) {
		$this->event->trigger('pre.salesman.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "salesman SET store_id = '" . (int)$this->config->get('config_store_id') . "', fullname = '" . $this->db->escape($data['fullname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '0', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) .
				"', status = '1', approved = '1', application_status = '1', date_added = NOW(), date_first_applied = NOW(), parent_id = '0', level = '1', with_grant_opt = '1'");

		$salesman_id = $this->db->getLastId();

		$this->db->query("INSERT INTO " . DB_PREFIX . "salesman_address SET salesman_id = '" . (int)$salesman_id . "', fullname = '" . $this->db->escape($data['shipping_fullname']) . "', company = '" . $this->db->escape($data['company']) . "', address = '" . $this->db->escape($data['address'])
				. "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "'");

		$address_id = $this->db->getLastId();

		$this->db->query("UPDATE " . DB_PREFIX . "salesman SET address_id = '" . (int)$address_id . "' WHERE salesman_id = '" . (int)$salesman_id . "'");

		$this->load->language('mail/salesman');

		$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

		$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";

		$message .= $this->url->link('account/login', '', 'SSL') . "\n\n";
		$message .= $this->language->get('text_services') . "\n\n";
		$message .= $this->language->get('text_thanks') . "\n";
		$message .= $this->config->get('config_name');

		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_host');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($data['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject($subject);
		$mail->setText($message);
		$mail->send();

		// Send to main admin email if new account email is enabled
		if ($this->config->get('config_account_mail')) {
			$message  = $this->language->get('text_signup') . "\n\n";
			$message .= $this->language->get('text_website') . ' ' . $this->config->get('config_name') . "\n";
			$message .= $this->language->get('text_fullname') . ' ' . $data['fullname'] . "\n";
			//$message .= $this->language->get('text_salesman_group') . ' ' . $salesman_group_info['name'] . "\n";
			$message .= $this->language->get('text_email') . ' '  .  $data['email'] . "\n";
			$message .= $this->language->get('text_telephone') . ' ' . $data['telephone'] . "\n";

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_host');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setSubject($this->language->get('text_new_salesman'));
			$mail->setText($message);
			$mail->send();

			// Send to additional alert emails if new account email is enabled
			$emails = explode(',', $this->config->get('config_mail_alert'));

			foreach ($emails as $email) {
				if (utf8_strlen($email) > 0 && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}

		// 业务员申请履历添加
		// 业务员创建履历
		$this->db->query('INSERT INTO ' . DB_PREFIX . "salesman_apply_record SET salesman_id = '" . (int)$salesman_id . "', status = '0'"
						.", date_processed = NOW()");
		// 批准业务员申请履历
		$this->db->query('INSERT INTO ' . DB_PREFIX . "salesman_apply_record SET salesman_id = '" . (int)$salesman_id . "', status = '1'"
						.", date_processed = NOW()");

		// 业务员申请个人认证图片
		$this->load->model ( 'salesman/upload' );

		$this->model_salesman_upload->addUpload ($salesman_id, $data);

		$this->event->trigger('post.salesman.add', $salesman_id);

		return $salesman_id;
	}

	public function editSalesman($data) {
		$this->event->trigger('pre.salesman.edit', $data);

		$salesman_id = $this->salesman->getId();

		if (isset($data['sub_commission_def_percent']) && isset($data['sub_settle_suspend_days'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "salesman SET fullname = '" . $this->db->escape($data['fullname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax'])
					. "', image = '" . $this->db->escape($data['image']) . "', sub_settle_suspend_days = '" . (int)$data['sub_settle_suspend_days']
					. "', sub_commission_def_percent = '" . (int)$data['sub_commission_def_percent'] . "' WHERE salesman_id = '" . (int)$salesman_id . "'");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "salesman SET fullname = '" . $this->db->escape($data['fullname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax'])
					. "', image = '" . $this->db->escape($data['image']) . "' WHERE salesman_id = '" . (int)$salesman_id . "'");
		}

		$this->load->model ( 'salesman/address' );
		if (! empty($data['address_id'])) {
			$this->model_salesman_address->editAddress($data['address_id'], $data);

		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "salesman_address SET salesman_id = '" . (int)$salesman_id . "', fullname = '" . $this->db->escape($data['fullname']) . "', company = '" . $this->db->escape($data['company']) . "', address = '" . $this->db->escape($data['address'])
					. "', city = '" . $this->db->escape($data['city']) . "', shipping_telephone = '" . $this->db->escape($data['shipping_telephone']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "'");

			$address_id = $this->db->getLastId();

			$this->db->query("UPDATE " . DB_PREFIX . "salesman SET address_id = '" . (int)$address_id . "' WHERE salesman_id = '" . (int)$salesman_id . "'");
		}


		$this->load->model ( 'salesman/upload' );
		if (! empty($data['upload_id'])) {
			$this->model_salesman_upload->editUpload ( $data['upload_id'], $data);
		} else {
			$this->model_salesman_upload->addUpload ( $salesman_id, $data);
		}

		$this->event->trigger('post.salesman.edit', $salesman_id);
	}

	public function editPassword($email, $password) {
		$this->event->trigger('pre.salesman.edit.password');

		$this->db->query("UPDATE " . DB_PREFIX . "salesman SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		$this->event->trigger('post.salesman.edit.password');
	}

    public function editCode($email, $code) {
                $this->db->query("UPDATE `" . DB_PREFIX . "salesman` SET code = '" . $this->db->escape($code) . "' WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
        }

	public function editNewsletter($newsletter) {
		$this->event->trigger('pre.salesman.edit.newsletter');

		$this->db->query("UPDATE " . DB_PREFIX . "salesman SET newsletter = '" . (int)$newsletter . "' WHERE salesman_id = '" . (int)$this->salesman->getId() . "'");

		$this->event->trigger('post.salesman.edit.newsletter');
	}

	public function getSalesman($salesman_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "salesman WHERE salesman_id = '" . (int)$salesman_id . "'");

		return $query->row;
	}

	public function getSalesmanByEmail($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "salesman WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getSalesmanByToken($token) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "salesman WHERE token = '" . $this->db->escape($token) . "' AND token != ''");

		$this->db->query("UPDATE " . DB_PREFIX . "salesman SET token = ''");

		return $query->row;
	}

	public function getTotalSalesmansByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "salesman WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row['total'];
	}

	public function getIps($salesman_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "salesman_ip` WHERE salesman_id = '" . (int)$salesman_id . "'");

		return $query->rows;
	}

	public function isBanIp($ip) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "salesman_ban_ip` WHERE ip = '" . $this->db->escape($ip) . "'");

		return $query->num_rows;
	}

	public function addLoginAttempt($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "salesman_login WHERE email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO " . commission . "salesman_login SET email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', total = 1, date_added = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "salesman_login SET total = (total + 1), date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE salesman_login_id = '" . (int)$query->row['salesman_login_id'] . "'");
		}
	}

	public function getLoginAttempts($email) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "salesman_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function deleteLoginAttempts($email) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "salesman_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}

	/**
	 * 查看当前设置的佣金是否合理
	 * 应当小于上级给自己设置的佣金百分比
	 */
	public function getParentCommission() {
		$commission_info = $this->db->query("SELECT CASE WHEN parent_id = 0 THEN (SELECT value FROM " . DB_PREFIX . "setting M WHERE store_id = 0 AND code = 'config' AND M.key = 'config_commission_def_percent')
														ELSE (SELECT sub_commission_def_percent FROM " . DB_PREFIX . "salesman WHERE salesman_id = s.parent_id) END AS commission_def_percent FROM "
													 . DB_PREFIX . "salesman s WHERE salesman_id = '" . (int)$this->salesman->getId() . "'");

		return $commission_info->row['commission_def_percent'];
	}
}
