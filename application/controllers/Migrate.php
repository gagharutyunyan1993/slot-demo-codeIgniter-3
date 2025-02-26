<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller {

	public function __construct() {
		parent::__construct();
		// Проверяем, что запрос идет из командной строки
		if (!$this->input->is_cli_request()) {
			exit('Командная строка доступна только для CLI' . PHP_EOL);
		}
		$this->load->library('migration');
	}

	public function index() {
		if ($this->migration->current() === FALSE) {
			echo 'Ошибка миграции: ' . $this->migration->error_string() . PHP_EOL;
		} else {
			echo 'Миграция успешно применена!' . PHP_EOL;
		}
	}
}
