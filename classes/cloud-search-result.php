<?php

/**
 * Class ACS_Result
 */
class ACS_Result {

	/**
	 * Result code
	 *
	 * @var string $code
	 */
	private $code;

	/**
	 * Result actions type
	 *
	 * @var array $message
	 */
	private $actions;

	/**
	 * Result response message
	 *
	 * @var string $message
	 */
	private $message;

	/**
	 * Result data items
	 *
	 * @var array $data
	 */
	private $data;

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->code = 'ok';
		$this->message = 'ok';
		$this->actions = array();
		$this->data = array();
	}

	/**
	 * Get code
	 *
	 * @return string
	 */
	public function get_code() {
		return $this->code;
	}

	/**
	 * Set code
	 *
	 * @param string $code
	 */
	public function set_code( $code ) {
		$this->code = $code;
	}

	/**
	 * Get actions
	 *
	 * @return array
	 */
	public function get_actions() {
		return $this->actions;
	}

	/**
	 * Set actions
	 *
	 * @param array $actions
	 */
	public function set_actions( $actions ) {
		$this->actions = $actions;
	}

	/**
	 * Add action
	 *
	 * @param string $action
	 */
	public function add_action( $action ) {
		array_push( $this->actions, $action );
	}

	/**
	 * Get message
	 *
	 * @return string
	 */
	public function get_message() {
		return $this->message;
	}

	/**
	 * Set message
	 *
	 * @param string $message
	 */
	public function set_message( $message ) {
		$this->message = $message;
	}

	/**
	 * Get data
	 *
	 * @return array
	 */
	public function get_data() {
		return $this->data;
	}

	/**
	 * Set data
	 *
	 * @param array $data
	 */
	public function set_data( $data ) {
		$this->data = $data;
	}

	/**
	 * Return JSON or XML response
	 *
	 * @return mixed|null
	 */
	public function format_response() {
		if ( ! empty( $this->code ) ) {
			// Read format parameter
			$format = ( ! empty( $_GET[ 'format' ] ) ) ? filter_var ( $_GET[ 'format' ], FILTER_SANITIZE_STRING ) : 'json';

			if ( strtolower( $format )  == 'xml' ) {
				// XML response
				header( 'Content-type: text/xml; charset=utf-8' );

				$response = '<?xml version="1.0" encoding="utf-8"?>';
				$response .= '<response>';
                $response .= '<code>' . $this->code . '</code>';
                $response .= '<actions>';
                foreach ( $this->actions as $action_item ) {
                    $response .= '<action>' . $action_item . '</action>';
                }
                $response .= '</actions>';
                $response .= '<message>' . $this->message . '</message>';
                $response .= '<data>';
                foreach ( $this->data as $data_item_key => $data_item_value ) {
                    $response .= '<' . $data_item_key . '>' . $data_item_value . '</' . $data_item_key . '>';
                }
                $response .= '</data>';
                $response .= '</response>';

				return $response;
			}
			else {
				// JSON response
				header( 'Content-Type: application/json' );

                $response = json_encode( array(
					'code' => $this->code,
					'actions' => $this->actions,
					'message' => $this->message,
					'data' => $this->data
				) );

                return $response;
			}
		}

		return null;
	}
} 