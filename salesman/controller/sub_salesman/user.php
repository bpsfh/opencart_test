<?php
/**
 * @author HU
 */
class ControllerSubSalesmanUser extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sub_salesman/user');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sub_salesman/user');

		$this->getList();
	}

	/**
	 * 增加下级业务员
	 */
	public function add() {
		$this->load->language('sub_salesman/user');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sub_salesman/user');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sub_salesman_user->addSubSalesman($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_with_grant_opt'])) {
				$url .= '&filter_with_grant_opt=' . $this->request->get['filter_with_grant_opt'];
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

			// 检查“下级业务员佣金默认百分比”等值是否已经设置
			$this->load->model('salesman/user');

			$salesman_info = $this->model_salesman_user->getSalesman($this->salesman->getId());
			if ((!empty($salesman_info)) &&
					($salesman_info['sub_settle_suspend_days'] == 0 || $salesman_info['sub_commission_def_percent'] == 0)) {
				$this->session->data['warning'] = $this->language->get('warning_commission');
			}

			$this->response->redirect($this->url->link('sub_salesman/user', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	/**
	 * 编辑业务员信息
	 */
	public function edit() {
		$this->load->language('sub_salesman/user');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sub_salesman/user');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sub_salesman_user->editSubSalesman($this->request->get['salesman_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_with_grant_opt'])) {
				$url .= '&filter_with_grant_opt=' . $this->request->get['filter_with_grant_opt'];
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

			$this->response->redirect($this->url->link('sub_salesman/user', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	/**
	 * 下级业务员一览
	 */
	protected function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['filter_with_grant_opt'])) {
			$filter_with_grant_opt = $this->request->get['filter_with_grant_opt'];
		} else {
			$filter_with_grant_opt = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'salesman_id';
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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_with_grant_opt'])) {
			$url .= '&filter_with_grant_opt=' . $this->request->get['filter_with_grant_opt'];
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
			'href' => $this->url->link('sub_salesman/user', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('sub_salesman/user/add', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['salesmans'] = array();

		$filter_data = array(
			'filter_name'              => $filter_name,
			'filter_email'             => $filter_email,
			'filter_date_added'        => $filter_date_added,
			'filter_with_grant_opt'    => $filter_with_grant_opt,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                    => $this->config->get('config_limit_admin')
		);

		$salesman_total = $this->model_sub_salesman_user->getTotalSubSalesmans($filter_data);

		$results = $this->model_sub_salesman_user->getSubSalesmans($filter_data);

		foreach ($results as $result) {

			$data['salesmans'][] = array(
				'salesman_id'    => $result['salesman_id'],
				'name'       	 => $result['fullname'],
				'email'          => $result['email'],
				'date_added'     => $result['date_added'],
				'with_grant_opt' => ($result['with_grant_opt'] ? $this->language->get('text_with_grant_opt_1') : $this->language->get('text_with_grant_opt_0')),
				'edit'           => $this->url->link('sub_salesman/user/edit', 'token=' . $this->session->data['token'] . '&salesman_id=' . $result['salesman_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_with_grant_opt_0'] = $this->language->get('text_with_grant_opt_0');
		$data['text_with_grant_opt_1'] = $this->language->get('text_with_grant_opt_1');
		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_salesman_id'] = $this->language->get('column_salesman_id');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_email'] = $this->language->get('column_email');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_with_grant_opt'] = $this->language->get('column_with_grant_opt');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_with_grant_opt'] = $this->language->get('entry_with_grant_opt');
		$data['entry_approved'] = $this->language->get('entry_approved');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

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

		if (isset($this->session->data['warning'])) {
			$data['warning'] = $this->session->data['warning'];
			$data['warning_commission_set'] = $this->language->get('warning_commission_set');
			$data['edit_salesman'] = $this->url->link('salesman/user/edit', 'token=' . $this->session->data['token'], 'SSL');

			unset($this->session->data['warning']);
		} else {
			$data['warning'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_with_grant_opt'])) {
			$url .= '&filter_with_grant_opt=' . $this->request->get['filter_with_grant_opt'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}


		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_salesman_id'] = $this->url->link('sub_salesman/user', 'token=' . $this->session->data['token'] . '&sort=salesman_id' . $url, 'SSL');
		$data['sort_name'] = $this->url->link('sub_salesman/user', 'token=' . $this->session->data['token'] . '&sort=fullname' . $url, 'SSL');
		$data['sort_email'] = $this->url->link('sub_salesman/user', 'token=' . $this->session->data['token'] . '&sort=email' . $url, 'SSL');
		$data['sort_with_grant_opt'] = $this->url->link('sub_salesman/user', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('sub_salesman/user', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_with_grant_opt'])) {
			$url .= '&filter_with_grant_opt=' . $this->request->get['filter_with_grant_opt'];
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

		$pagination = new Pagination();
		$pagination->total = $salesman_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sub_salesman/user', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($salesman_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($salesman_total - $this->config->get('config_limit_admin'))) ? $salesman_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $salesman_total, ceil($salesman_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_email'] = $filter_email;
		$data['filter_with_grant_opt'] = $filter_with_grant_opt;
		$data['filter_date_added'] = $filter_date_added;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sub_salesman/salesman_list.tpl', $data));
	}

	/**
	 * 业务员相关编辑画面
	 */
	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['salesman_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_with_grant_opt_0'] = $this->language->get('text_with_grant_opt_0');
		$data['text_with_grant_opt_1'] = $this->language->get('text_with_grant_opt_1');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['text_shipping_info'] = $this->language->get('text_shipping_info');

		$data['entry_fullname'] = $this->language->get('entry_fullname');
		$data['entry_shipping_fullname'] = $this->language->get('entry_shipping_fullname');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_shipping_telephone'] = $this->language->get('entry_shipping_telephone');

		$data['entry_fax'] = $this->language->get('entry_fax');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_confirm'] = $this->language->get('entry_confirm');
		$data['entry_newsletter'] = $this->language->get('entry_newsletter');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_approved'] = $this->language->get('entry_approved');
		$data['entry_safe'] = $this->language->get('entry_safe');
		$data['entry_company'] = $this->language->get('entry_company');
		$data['entry_address'] = $this->language->get('entry_address');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_zone'] = $this->language->get('entry_zone');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['entry_default'] = $this->language->get('entry_default');
		$data['entry_with_grant_opt'] = $this->language->get('entry_with_grant_opt');
		$data ['entry_identity'] = $this->language->get('entry_identity');
		$data ['entry_identity_img'] = $this->language->get('entry_identity_img');

		// use?
		$data['help_safe'] = $this->language->get('help_safe');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_address_add'] = $this->language->get('button_address_add');
		$data['button_download'] = $this->language->get('button_download');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_address'] = $this->language->get('tab_address');

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['salesman_id'])) {
			$data['salesman_id'] = $this->request->get['salesman_id'];
		} else {
			$data['salesman_id'] = "";
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['fullname'])) {
			$data['error_fullname'] = $this->error['fullname'];
		} else {
			$data['error_fullname'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['telephone'])) {
			$data['error_telephone'] = $this->error['telephone'];
		} else {
			$data['error_telephone'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['confirm'])) {
			$data['error_confirm'] = $this->error['confirm'];
		} else {
			$data['error_confirm'] = '';
		}

		if (isset ( $this->error ['shipping_telephone'] )) {
			$data ['error_shipping_telephone'] = $this->error ['shipping_telephone'];
		} else {
			$data ['error_shipping_telephone'] = '';
		}

		if (isset ( $this->error ['address'] )) {
			$data ['error_address'] = $this->error ['address'];
		} else {
			$data ['error_address'] = '';
		}

		if (isset ( $this->error ['city'] )) {
			$data ['error_city'] = $this->error ['city'];
		} else {
			$data ['error_city'] = '';
		}

		if (isset ( $this->error ['postcode'] )) {
			$data ['error_postcode'] = $this->error ['postcode'];
		} else {
			$data ['error_postcode'] = '';
		}

		if (isset ( $this->error ['country'] )) {
			$data ['error_country'] = $this->error ['country'];
		} else {
			$data ['error_country'] = '';
		}

		if (isset ( $this->error ['zone'] )) {
			$data ['error_zone'] = $this->error ['zone'];
		} else {
			$data ['error_zone'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_approved'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_approved'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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
				'href' => $this->url->link('salesman/user', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['salesman_id'])) {
			$data['action'] = $this->url->link('sub_salesman/user/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('sub_salesman/user/edit', 'token=' . $this->session->data['token'] . '&salesman_id=' . $this->request->get['salesman_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('sub_salesman/user', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['salesman_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$salesman_info = $this->model_sub_salesman_user->getSubSalesman($this->request->get['salesman_id']);

			$this->load->model ( 'salesman/address' );
			$address_info = $this->model_salesman_address->getAddress ( $salesman_info['address_id'] );
		}

		if (isset($this->request->post['fullname'])) {
			$data['fullname'] = $this->request->post['fullname'];
		} elseif (!empty($salesman_info)) {
			$data['fullname'] = $salesman_info['fullname'];
		} else {
			$data['fullname'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} elseif (!empty($salesman_info)) {
			$data['email'] = $salesman_info['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($salesman_info)) {
			$data['telephone'] = $salesman_info['telephone'];
		} else {
			$data['telephone'] = '';
		}

		if (isset($this->request->post['fax'])) {
			$data['fax'] = $this->request->post['fax'];
		} elseif (!empty($salesman_info)) {
			$data['fax'] = $salesman_info['fax'];
		} else {
			$data['fax'] = '';
		}

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($salesman_info)) {
			$data['image'] = $salesman_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($salesman_info) && $salesman_info['image'] && is_file(DIR_IMAGE . $salesman_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($salesman_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['newsletter'])) {
			$data['newsletter'] = $this->request->post['newsletter'];
		} elseif (!empty($salesman_info)) {
			$data['newsletter'] = $salesman_info['newsletter'];
		} else {
			$data['newsletter'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($salesman_info)) {
			$data['status'] = $salesman_info['status'];
		} else {
			$data['status'] = true;
		}

		if (isset($this->request->post['with_grant_opt'])) {
			$data['with_grant_opt'] = $this->request->post['with_grant_opt'];
		} elseif (!empty($salesman_info)) {
			$data['with_grant_opt'] = $salesman_info['with_grant_opt'];
		} else {
			$data['with_grant_opt'] = false;
		}

		if (isset($this->request->post['approved'])) {
			$data['approved'] = $this->request->post['approved'];
		} elseif (!empty($salesman_info)) {
			$data['approved'] = $salesman_info['approved'];
		} else {
			$data['approved'] = true;
		}

		if (isset($this->request->post['safe'])) {
			$data['safe'] = $this->request->post['safe'];
		} elseif (!empty($salesman_info)) {
			$data['safe'] = $salesman_info['safe'];
		} else {
			$data['safe'] = 0;
		}

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		if (isset($this->request->post['confirm'])) {
			$data['confirm'] = $this->request->post['confirm'];
		} else {
			$data['confirm'] = '';
		}

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		if (isset ( $this->request->post ['address_id'] )) {
			$data ['address_id'] = $this->request->post ['address_id'];
		} elseif (! empty ( $address_info )) {
			$data ['address_id'] = $address_info ['address_id'];
		} else {
			$data ['address_id'] = 0;
		}

		if (isset ( $this->request->post ['shipping_fullname'] )) {
			$data ['shipping_fullname'] = $this->request->post ['shipping_fullname'];
		} elseif (! empty ( $address_info )) {
			$data ['shipping_fullname'] = $address_info ['fullname'];
		} else {
			$data ['shipping_fullname'] = '';
		}

		if (isset ( $this->request->post ['address'] )) {
			$data ['address'] = $this->request->post ['address'];
		} elseif (! empty ( $address_info )) {
			$data ['address'] = $address_info ['address'];
		} else {
			$data ['address'] = '';
		}

		if (isset ( $this->request->post ['postcode'] )) {
			$data ['postcode'] = $this->request->post ['postcode'];
		} elseif (! empty ( $address_info )) {
			$data ['postcode'] = $address_info ['postcode'];
		} else {
			$data ['postcode'] = '';
		}

		if (isset ( $this->request->post ['company'] )) {
			$data ['company'] = $this->request->post ['company'];
		} elseif (! empty ( $address_info )) {
			$data ['company'] = $address_info ['company'];
		} else {
			$data ['company'] = '';
		}

		if (isset ( $this->request->post ['shipping_telephone'] )) {
			$data ['shipping_telephone'] = $this->request->post ['shipping_telephone'];
		} elseif (! empty ( $address_info )) {
			$data ['shipping_telephone'] = $address_info ['shipping_telephone'];
		} else {
			$data ['shipping_telephone'] = '';
		}

		if (isset ( $this->request->post ['city'] )) {
			$data ['city'] = $this->request->post ['city'];
		} elseif (! empty ( $address_info )) {
			$data ['city'] = $address_info ['city'];
		} else {
			$data ['city'] = '';
		}

		if (isset ( $this->request->post ['country_id'] )) {
			$data ['country_id'] = $this->request->post ['country_id'];
		} elseif (! empty ( $address_info )) {
			$data ['country_id'] = $address_info ['country_id'];
		} else {
			$data ['country_id'] = $this->config->get ( 'config_country_id' );
		}

		if (isset ( $this->request->post ['zone_id'] )) {
			$data ['zone_id'] = $this->request->post ['zone_id'];
		} elseif (! empty ( $address_info )) {
			$data ['zone_id'] = $address_info ['zone_id'];
		} else {
			$data ['zone_id'] = '';
		}

		$this->load->model ( 'salesman/upload' );

		if(isset($this->request->get ['salesman_id'])) {
			$user_identity_img_info = $this->model_salesman_upload->getSalesmanImgUpload ( $this->request->get ['salesman_id'] , '1' );
		}

		if (isset($this->request->post['upload_id'])) {
			$data ['upload_id'] = $this->request->post['upload_id'];
		} elseif (! empty ( $user_identity_img_info )) {
			$data ['upload_id'] = $user_identity_img_info ['upload_id'];
		} else {
			$data ['upload_id'] = '';
		}

		if (isset($this->request->post['salesman_upload_description'])) {
			$data['salesman_upload_description'] = $this->request->post['salesman_upload_description'];
		} elseif (! empty ( $user_identity_img_info )) {
			$data ['salesman_upload_description'] = $this->model_salesman_upload->getUploadDescriptions($user_identity_img_info['upload_id']);
		} else {
			$data['salesman_upload_description'] = array();
		}

		if (isset($this->request->post['filename'])) {
			$data['filename'] = $this->request->post['filename'];
		} elseif (! empty ( $user_identity_img_info )) {
			$data ['filename'] = $user_identity_img_info ['filename'];
		} else {
			$data['filename'] = '';
		}

		if (isset($this->request->post['mask'])) {
			$data['mask'] = $this->request->post['mask'];
		} elseif (! empty ( $user_identity_img_info )) {
			$data ['mask'] = $user_identity_img_info ['mask'];
		} else {
			$data['mask'] = '';
		}

		if (isset($this->request->post['category'])) {
			$data['category'] = $this->request->post['category'];
		} elseif (! empty ( $user_identity_img_info )) {
			$data ['category'] = $user_identity_img_info ['category'];
		} else {
			$data['category'] = 1;
		}

		$data['download'] = $this->url->link('salesman/upload/download', 'token=' . $this->session->data['token'] . '&mask=' . $data['mask'] . $url, 'SSL');


		$this->load->model('localisation/language');

		$data['languages'] = array(1);    // $this->model_localisation_language->getLanguages();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sub_salesman/salesman_form.tpl', $data));
	}

	/**
	 * 自动查找
	 */
	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email'])) {
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_email'])) {
				$filter_email = $this->request->get['filter_email'];
			} else {
				$filter_email = '';
			}

			$this->load->model('sub_salesman/user');

			$filter_data = array(
					'filter_name'  => $filter_name,
					'filter_email' => $filter_email,
					'start'        => 0,
					'limit'        => 5
			);

			$results = $this->model_sub_salesman_user->getSubSalesmans($filter_data);

			foreach ($results as $result) {
				$json[] = array(
						'salesman_id'       => $result['salesman_id'],
						'name'              => strip_tags(html_entity_decode($result['fullname'], ENT_QUOTES, 'UTF-8')),
						'email'             => $result['email'],
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * 验证下级业务员信息的合理性
	 * @return boolean
	 */
	protected function validateForm() {
		// 验证业务员名
		if ((utf8_strlen($this->request->post['fullname']) < 1) || (utf8_strlen(trim($this->request->post['fullname'])) > 32)) {
			$this->error['fullname'] = $this->language->get('error_fullname');
		}

		// 验证email是否合理
		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}

		// 验证email是否除修改业务员本人之外其他业务员使用
		$salesman_info = $this->model_sub_salesman_user->getSalesmanByEmail($this->request->post['email']);

		if (!isset($this->request->get['salesman_id'])) {
			if ($salesman_info) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		} else {
			if ($salesman_info && ($this->request->get['salesman_id'] != $salesman_info['salesman_id'])) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		}

		// 电话号码
		if (!preg_match('/1[123456789]{1}\d{9}$/', $this->request->post['telephone'])) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		// 验证密码
		if ($this->request->post['password'] || (!isset($this->request->get['salesman_id']))) {
			if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
				$this->error['password'] = $this->language->get('error_password');
			}

			if ($this->request->post['password'] != $this->request->post['confirm']) {
				$this->error['confirm'] = $this->language->get('error_confirm');
			}
		}

		// 地址的合理性
		if (($this->request->post ['shipping_fullname'] !== '') && (utf8_strlen ( trim ( $this->request->post ['shipping_fullname'] ) ) < 2) || (utf8_strlen ( trim ( $this->request->post ['shipping_fullname'] ) ) > 32)) {
			$this->error ['shipping_fullname'] = $this->language->get ( 'error_shipping_fullname' );
		}

		if (($this->request->post['shipping_telephone'] !== '') && (utf8_strlen ( trim ( $this->request->post ['shipping_telephone'] ) ) < 8) || (utf8_strlen ( trim ( $this->request->post ['shipping_telephone'] ) ) > 13)) {
			$this->error ['shipping_telephone'] = $this->language->get ( 'error_shipping_telephone' );
		}

		if (($this->request->post ['address'] !== '') && (utf8_strlen ( trim ( $this->request->post ['address'] ) ) < 3) || (utf8_strlen ( trim ( $this->request->post ['address'] ) ) > 128)) {
			$this->error ['address'] = $this->language->get ( 'error_address' );
		}

		if (($this->request->post ['city'] !== '') && (utf8_strlen ( trim ( $this->request->post ['city'] ) ) < 2) || (utf8_strlen ( trim ( $this->request->post ['city'] ) ) > 128)) {
			$this->error ['city'] = $this->language->get ( 'error_city' );
		}

		$this->load->model ( 'localisation/country' );

		$country_info = $this->model_localisation_country->getCountry ( $this->request->post ['country_id'] );

		if ($country_info && $country_info ['postcode_required'] && (utf8_strlen ( trim ( $this->request->post ['postcode'] ) ) < 2 || utf8_strlen ( trim ( $this->request->post ['postcode'] ) ) > 10)) {
			$this->error ['postcode'] = $this->language->get ( 'error_postcode' );
		}

		if ( ($this->request->post ['country_id'] !== '') && (utf8_strlen ( trim ( $this->request->post ['country_id'] ) ) < 2 || utf8_strlen ( trim ( $this->request->post ['country_id'] ) ) > 128)) {
			$this->error ['country'] = $this->language->get ( 'error_country' );
		}

		if ((utf8_strlen ( trim ( $this->request->post ['country_id'] ) ) > 2 && utf8_strlen ( trim ( $this->request->post ['country_id'] ) ) < 128) && $this->request->post ['zone_id'] == '') {
			$this->error ['zone'] = $this->language->get ( 'error_zone' );
		}


		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

}
