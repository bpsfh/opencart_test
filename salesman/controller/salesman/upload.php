<?php
class ControllerSalesmanUpload extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('salesman/upload');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('salesman/upload');

		$this->getList();
	}

	public function add() {
		$this->load->language('salesman/upload');

		$this->document->setTitle($this->language->get('heading_title'));

		$salesman_id = $this->salesman->getId ();

		$this->load->model('salesman/upload');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_salesman_upload->addUpload($salesman_id, $this->request->post);

			$this->session->data['success'] = $this->language->get('text_upload');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('salesman/upload', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('salesman/upload');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('salesman/upload');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_salesman_upload->editUpload($this->request->get['upload_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('salesman/upload', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('salesman/upload');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('salesman/upload');

		if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $upload_id) {
				$this->model_salesman_upload->deleteUpload($upload_id);
			}

			$this->session->data['success'] = $this->language->get('text_success_delete');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('salesman/upload', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_filename'])) {
			$filter_filename = $this->request->get['filter_filename'];
		} else {
			$filter_filename = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'dd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_filename'])) {
			$url .= '&filter_filename=' . urlencode(html_entity_decode($this->request->get['filter_filename'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('salesman/upload', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('salesman/upload/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('salesman/upload/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['uploads'] = array();

		$filter_data = array(
			'filter_name'	    => $filter_name,
			'filter_filename'   => $filter_filename,
			'filter_date_added'	=> $filter_date_added,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$upload_total = $this->model_salesman_upload->getTotalUploads($filter_data);

		$results = $this->model_salesman_upload->getUploads($filter_data);

		foreach ($results as $result) {
			$data['uploads'][] = array(
				'upload_id'   => $result['upload_id'],
				'name'        => $result['name'],
				'filename'    => $result['filename'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'edit'        => $this->url->link('salesman/upload/edit', 'token=' . $this->session->data['token'] . '&upload_id=' . $result['upload_id'] . $url, 'SSL'),
				'download'    => $this->url->link('salesman/upload/download', 'token=' . $this->session->data['token'] . '&mask=' . $result['mask'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_filename'] = $this->language->get('column_filename');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_filename'] = $this->language->get('entry_filename');
		$data['entry_date_added'] = $this->language->get('entry_date_added');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_download'] = $this->language->get('button_download');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('salesman/upload', 'token=' . $this->session->data['token'] . '&sort=dd.name' . $url, 'SSL');
		$data['sort_filename'] = $this->url->link('salesman/upload', 'token=' . $this->session->data['token'] . '&sort=filename' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('salesman/upload', 'token=' . $this->session->data['token'] . '&sort=d.date_added' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_filename'])) {
			$url .= '&filter_filename=' . urlencode(html_entity_decode($this->request->get['filter_filename'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		$data['token'] = $this->session->data['token'];

		$pagination = new Pagination();
		$pagination->total = $upload_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('salesman/upload', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($upload_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($upload_total - $this->config->get('config_limit_admin'))) ? $upload_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $upload_total, ceil($upload_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['filter_name'] = $filter_name;
		$data['filter_filename'] = $filter_filename;
		$data['filter_date_added'] = $filter_date_added;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('salesman/upload_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['upload_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_edit'] = $this->language->get('text_edit');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_filename'] = $this->language->get('entry_filename');
		$data['entry_mask'] = $this->language->get('entry_mask');

		$data['help_filename'] = $this->language->get('help_filename');
		$data['help_mask'] = $this->language->get('help_mask');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_upload'] = $this->language->get('button_upload');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		if (isset($this->error['filename'])) {
			$data['error_filename'] = $this->error['filename'];
		} else {
			$data['error_filename'] = '';
		}

		if (isset($this->error['mask'])) {
			$data['error_mask'] = $this->error['mask'];
		} else {
			$data['error_mask'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('salesman/upload', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['upload_id'])) {
			$data['action'] = $this->url->link('salesman/upload/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('salesman/upload/edit', 'token=' . $this->session->data['token'] . '&upload_id=' . $this->request->get['upload_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('salesman/upload', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->get['upload_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$upload_info = $this->model_salesman_upload->getUpload($this->request->get['upload_id']);
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['upload_id'])) {
			$data['upload_id'] = $this->request->get['upload_id'];
		} else {
			$data['upload_id'] = 0;
		}

		if (isset($this->request->post['salesman_upload_description'])) {
			$data['salesman_upload_description'] = $this->request->post['salesman_upload_description'];
		} elseif (isset($this->request->get['upload_id'])) {
			$data['salesman_upload_description'] = $this->model_salesman_upload->getUploadDescriptions($this->request->get['upload_id']);
		} else {
			$data['salesman_upload_description'] = array();
		}

		if (isset($this->request->post['filename'])) {
			$data['filename'] = $this->request->post['filename'];
		} elseif (!empty($upload_info)) {
			$data['filename'] = $upload_info['filename'];
		} else {
			$data['filename'] = '';
		}

		if (isset($this->request->post['mask'])) {
			$data['mask'] = $this->request->post['mask'];
		} elseif (!empty($upload_info)) {
			$data['mask'] = $upload_info['mask'];
		} else {
			$data['mask'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('salesman/upload_form.tpl', $data));
	}

	protected function validateForm() {

		foreach ($this->request->post['salesman_upload_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 64)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		if ((utf8_strlen($this->request->post['filename']) < 3) || (utf8_strlen($this->request->post['filename']) > 128)) {
			$this->error['filename'] = $this->language->get('error_filename');
		}

		if (!is_file(DIR_UPLOAD . $this->request->post['filename'])) {
			$this->error['filename'] = $this->language->get('error_exists');
		}

		if ((utf8_strlen($this->request->post['mask']) < 3) || (utf8_strlen($this->request->post['mask']) > 128)) {
			$this->error['mask'] = $this->language->get('error_mask');
		}

		return !$this->error;
	}

	public function download() {
		$this->load->model('salesman/upload');

		if (isset($this->request->get['mask'])) {
			$mask = $this->request->get['mask'];
		} else {
			$mask = 0;
		}

		$upload_info = $this->model_salesman_upload->getUploadByMask($mask);

		if ($upload_info) {
			$file = DIR_UPLOAD . $upload_info['filename'];
			if (!headers_sent()) {
				if (is_file($file)) {
					header('Content-Type: application/octet-stream');
					header('Content-Description: File Transfer');
					header('Content-Disposition: attachment; filename="' . ($mask ? $mask : basename($file)) . '"');
					header('Content-Transfer-Encoding: binary');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					header('Content-Length: ' . filesize($file));

					readfile($file, 'rb');
					exit;
				} else {
					exit('Error: Could not find file ' . $file . '!');
				}
			} else {
				exit('Error: Headers already sent out!');
			}
		} else {
			$this->load->language('error/not_found');

			$this->document->setTitle($this->language->get('heading_title'));

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_not_found'] = $this->language->get('text_not_found');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_home'),
					'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
			);

			$data['breadcrumbs'][] = array(
					'text' => $this->language->get('heading_title'),
					'href' => $this->url->link('error/not_found', 'token=' . $this->session->data['token'], 'SSL')
			);

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('error/not_found.tpl', $data));
		}
	}

	public function upload() {
		$this->load->language('salesman/upload');

		$json = array();

		if (!$json) {
			if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
				// Sanitize the filename
				$filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));

				// Validate the filename length
				if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 128)) {
					$json['error'] = $this->language->get('error_filename');
				}

				// Allowed file extension types
				$allowed = array();

				$extension_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_ext_allowed'));

				$filetypes = explode("\n", $extension_allowed);

				foreach ($filetypes as $filetype) {
					$allowed[] = trim($filetype);
				}

				if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
					$json['error'] = $this->language->get('error_filetype');
				}

				// Allowed file mime types
				$allowed = array();

				$mime_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_mime_allowed'));

				$filetypes = explode("\n", $mime_allowed);

				foreach ($filetypes as $filetype) {
					$allowed[] = trim($filetype);
				}

				if (!in_array($this->request->files['file']['type'], $allowed)) {
					$json['error'] = $this->language->get('error_filetype');
				}

				// Check to see if any PHP files are trying to be uploaded
				$content = file_get_contents($this->request->files['file']['tmp_name']);

				if (preg_match('/\<\?php/i', $content)) {
					$json['error'] = $this->language->get('error_filetype');
				}

				// Return any upload error
				if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
				}
			} else {
				$json['error'] = $this->language->get('error_upload');
			}
		}

		if (!$json) {
			$file = $filename . '.' . md5(mt_rand());

			move_uploaded_file($this->request->files['file']['tmp_name'], DIR_UPLOAD . $file);

			$this->load->model('salesman/upload');

			$json['filename'] = $file;
			$json['mask'] = $filename;

			$json['success'] = $this->language->get('text_upload');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_filename'])) {
			$this->load->model('salesman/upload');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'filter_filename' => $this->request->get['filter_filename'],
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_salesman_upload->getUploads($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'upload_id' => $result['upload_id'],
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'filename'        => strip_tags(html_entity_decode($result['filename'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
			$sort_order[$key] = $value['filename'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}