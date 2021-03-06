<?php
class ControllerCommonDashboard extends Controller {
	public function index() {
		if (!$this->salesman->isLogged()) {
			return new Action ('common/login');
		}

		$this->load->language('common/dashboard');
		
		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_sale'] = $this->language->get('text_sale');
		$data['text_map'] = $this->language->get('text_map');
		$data['text_activity'] = $this->language->get('text_activity');
		$data['text_recent'] = $this->language->get('text_recent');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		// Check install directory exists
		if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
			$data['error_install'] = $this->language->get('error_install');
		} else {
			$data['error_install'] = '';
		}
		
		$data['entry_period_from'] = $this->language->get('entry_period_from');
		$data['entry_period_to'] = $this->language->get('entry_period_to');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		// Filter
		if (isset($this->request->get['filter_period_from'])) {
			$filter_period_from = $this->request->get['filter_period_from'];
		} else {
			$filter_period_from= null;  //date("Y-m-d", strtotime("-1 months"));
		}
		
		if (isset($this->request->get['filter_period_to'])) {
			$filter_period_to = $this->request->get['filter_period_to'];
		} else {
			$filter_period_to = null;    //date("Y-m-d");
		}

		$data['filter_period_from'] = $filter_period_from;
		$data['filter_period_to'] = $filter_period_to;
		
		// Authority
		$data['isAuthorized'] = $this->salesman->isAuthorized();
		$data['application_status'] = $this->salesman->getApplicationStatus();
	
		// links	
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['chart'] = $this->load->controller('dashboard/chart');
		$data['achievement'] = $this->load->controller('dashboard/achievement');
		$data['footer'] = $this->load->controller('common/footer');
	
		// message	
		$data['basic_info'] = $this->url->link('salesman/user/edit', 'token=' . $this->session->data['token'], 'SSL');
		$data['bank_info'] = $this->url->link('salesman/bank_account/edit', 'token=' . $this->session->data['token'], 'SSL');

		$data['application_status_message'] = sprintf($this->language->get('text_welcome_msg'),
				 $this->language->get('text_application_status_' . $data['application_status'])); 
		$data['application_status_message'] .= sprintf($this->language->get('text_go_on_msg'), $data['basic_info'], $data['bank_info']); 

		$this->response->setOutput($this->load->view('common/dashboard.tpl', $data));
	}
}
