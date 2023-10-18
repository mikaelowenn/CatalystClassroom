<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Course;

use MasterStudy\Lms\Routing\Swagger\Fields\PostStatus;
use MasterStudy\Lms\Routing\Swagger\RequestInterface;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

class UpdateAccessSettings extends Route implements RequestInterface, ResponseInterface {

	public function request(): array {
		return array(
			'expiration' => array(
				'type'     => 'boolean',
				'required' => true,
			),
			'end_time'   => array(
				'type'        => 'integer',
				'nullable'    => true,
				'description' => 'Required if expiration is true.',
				'minimum'     => 1,
			),
			'shareware'  => array(
				'type' => 'boolean',
			),
		);
	}

	public function response(): array {
		return array(
			'status' => array(
				'type'    => 'string',
				'example' => 'ok',
			),
		);
	}

	public function get_summary(): string {
		return 'Update course access settings';
	}

	public function get_description(): string {
		return 'Updates course access settings';
	}
}
