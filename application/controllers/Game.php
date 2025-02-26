<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Game extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Game_model');
		$this->load->library('GameLogic');
		$this->load->helper('url');
	}

	/**
	 * Отображение базовой страницы
	 */
	public function index() {
		$this->load->view('game/index');
	}

	/**
	 * Создать новую игровую сессию
	 * Возвращает JSON: { session_id, balance }
	 */
	public function init_session() {
		$session_id = $this->Game_model->create_session();
		$response = [
			'status'     => 'success',
			'session_id' => $session_id,
			'balance'    => 1000
		];
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
	}

	/**
	 * Выполнить спин (POST: session_id)
	 * Возвращает JSON с итоговым состоянием:
	 *   grid, win_lines, total_win, new_balance
	 */
	public function do_spin() {
		$session_id = $this->input->post('session_id');
		if (!$session_id) {
			return $this->jsonError('No session_id provided');
		}

		$session = $this->Game_model->get_session($session_id);
		if (!$session) {
			return $this->jsonError('Invalid session_id');
		}

		$bet = 10;
		if ($session->balance < $bet) {
			return $this->jsonError('Not enough balance');
		}

		$new_balance = $session->balance - $bet;

		$spin_result = $this->gamelogic->spin();
		$win = $spin_result['total_win'];
		$new_balance += $win;

		$this->Game_model->update_balance($session_id, $new_balance);

		$response = [
			'grid'       => $spin_result['grid'],
			'win_lines'  => $spin_result['win_lines'],
			'total_win'  => $win,
			'new_balance'=> $new_balance
		];
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
	}

	private function jsonError($message) {
		$this->output
			->set_status_header(400)
			->set_content_type('application/json')
			->set_output(json_encode(['status' => 'error', 'message' => $message]));
	}
}
