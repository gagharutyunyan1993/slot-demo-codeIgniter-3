<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Game_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	/**
	 * Создать новую игровую сессию, вернуть уникальный session_id
	 */
	public function create_session() {
		$session_id = bin2hex(random_bytes(16));
		$data = [
			'session_id' => $session_id,
			'balance'    => 1000
		];
		$this->db->insert('sessions', $data);
		return $session_id;
	}

	/**
	 * Получить данные сессии по session_id
	 */
	public function get_session($session_id) {
		return $this->db->get_where('sessions', ['session_id' => $session_id])->row();
	}

	/**
	 * Обновить баланс в сессии
	 */
	public function update_balance($session_id, $new_balance) {
		$this->db->where('session_id', $session_id)
			->update('sessions', ['balance' => $new_balance]);
	}
}
