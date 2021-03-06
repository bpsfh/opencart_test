<?php
class ControllerDashboardSale extends Controller {
	public function index() {
		$this->load->language('dashboard/sale');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_view'] = $this->language->get('text_view');

		$data['token'] = $this->session->data['token'];

		$this->load->model('vip/order');

		$sale_total= $this->model_vip_order->getTotalSalesAmount(array('salesman_id' => $this->salesman->getId()));
		/*
		if ($sale_total > 1000000000000) {
			$data['total'] = round($sale_total / 1000000000000, 1) . 'T';
		} elseif ($sale_total > 1000000000) {
			$data['total'] = round($sale_total / 1000000000, 1) . 'B';
		} elseif ($sale_total > 1000000) {
			$data['total'] = round($sale_total / 1000000, 1) . 'M';
		} elseif ($sale_total > 1000) {
			$data['total'] = round($sale_total / 1000, 1) . 'K';
		} else {
			$data['total'] = round($sale_total);
		}
		*/

		$data['total_formated'] = $this->currency->format($sale_total, $this->config->get('currency_code'));

		$data['sale'] = $this->url->link('vip/order', 'token=' . $this->session->data['token'], 'SSL');

		return $this->load->view('dashboard/sale.tpl', $data);
	}
}
