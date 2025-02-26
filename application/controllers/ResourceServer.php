<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ResourceServer extends CI_Controller {

	public function get($filePath = '') {
		$basePath = FCPATH . 'resources/';
		$fullPath = realpath($basePath . $filePath);

		if (!$fullPath || strpos($fullPath, realpath($basePath)) !== 0) {
			show_404();
			return;
		}

		if (file_exists($fullPath)) {
			$mime = mime_content_type($fullPath);
			header("Content-Type: $mime");
			readfile($fullPath);
		} else {
			show_404();
		}
	}
}
