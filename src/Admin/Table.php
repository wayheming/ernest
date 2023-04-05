<?php

namespace Ernest\Admin;

use WP_List_Table;

if ( ! class_exists( 'WP_List_Table', false ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Class Table.
 */
class Table extends WP_List_Table {
	/**
	 * Data.
	 *
	 * @var array
	 */
	private array $data;

	/**
	 * Constructor.
	 *
	 * @param array $data Data.
	 */
	public function __construct( array $data ) {
		$this->data = $data;

		parent::__construct( [
			'ajax' => false
		] );
	}

	/**
	 * Default column.
	 *
	 * @param array $item Item.
	 * @param string $column_name Column name.
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ): mixed {
		return match ( $column_name ) {
			'id', 'fname', 'lname', 'email' => $item[ $column_name ],
			'date' => date( 'Y/m/d H:i:s', $item[ $column_name ] ),
			default => print_r( $item, true ),
		};
	}

	/**
	 * Get columns.
	 *
	 * @return array
	 */
	public function get_columns(): array {
		return $this->get_headers();
	}

	/**
	 * Prepare items.
	 */
	public function prepare_items() {
		$rows = $this->data['rows'];

		$this->_column_headers = [ $this->get_headers(), [], [] ];
		$this->items           = $rows;
	}

	/**
	 * Get headers.
	 *
	 * @return array
	 */
	private function get_headers(): array {
		$raw_headers = $this->data['headers'];
		$headers     = [];

		foreach ( $raw_headers as $header ) {
			switch ( $header ) {
				case 'First Name':
					$headers['fname'] = $header;
					break;
				case 'Last Name':
					$headers['lname'] = $header;
					break;
				default:
					$headers[ strtolower( $header ) ] = $header;
					break;
			}
		}

		return $headers;
	}
}
