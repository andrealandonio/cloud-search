<?php

/**
 * Class ACS_Message
 */
class ACS_Message {

	/**
	 * Class constants
	 */
	const TYPE_ERROR = 0;
	const TYPE_INFO = 1;

	/**
	 * Message text
	 *
	 * @var string $text
	 */
	private $text;

	/**
	 * Message value
	 *
	 * @var string $value
	 */
	private $value;

	/**
	 * Message type
	 *
	 * @var int $type
	 */
	private $type;

	/**
	 * Class constructor
	 *
	 * @param string $text
	 * @param string $value
	 * @param int $type
	 */
	public function __construct( $text, $value, $type = self::TYPE_INFO ) {
		$this->text = (string) $text;
		$this->type = (int) $type;
		$this->value = (string) $value;
	}

	/**
	 * Get text
	 *
	 * @return string
	 */
	public function get_text() {
		return $this->text;
	}

	/**
	 * Set text
	 *
	 * @param string $text
	 */
	public function set_text( $text ) {
		$this->text = $text;
	}

	/**
	 * Get value
	 *
	 * @return string
	 */
	public function get_value() {
		return $this->value;
	}

	/**
	 * Set value
	 *
	 * @param string $value
	 */
	public function set_value( $value ) {
		$this->value = $value;
	}

	/**
	 * Get type
	 *
	 * @return int
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * Set type
	 *
	 * @param int $type
	 */
	public function set_type( $type ) {
		$this->type = $type;
	}

	/**
	 * Get message
	 *
	 * @return string
	 */
	public function get_message() {
		return sprintf( $this->text, $this->value );
	}
} 