<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_CreateSessionsTable extends CI_Migration {

	public function up() {
		$this->dbforge->add_field([
			'id' => [
				'type'           => 'INT',
				'constraint'     => 11,
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			],
			'session_id' => [
				'type'       => 'VARCHAR',
				'constraint' => '64',
				'null'       => FALSE
			],
			'balance' => [
				'type'       => 'INT',
				'constraint' => 11,
				'default'    => 1000
			],
			'created_at' => [
				'type'    => 'TIMESTAMP',
				'null'    => FALSE,
				'default' => NULL
			]
		]);

		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('session_id');
		$this->dbforge->create_table('sessions');

		$this->db->query("ALTER TABLE sessions MODIFY created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
	}

	public function down() {
		$this->dbforge->drop_table('sessions');
	}
}
