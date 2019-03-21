<?php
/*
Plugin Name: CloudSearch
Description: CloudSearch is a flexible plugin that allows you to leverage the search index power of Amazon CloudSearch in your WordPress site.
Author: Andrea Landonio
Author URI: http://www.andrealandonio.it
Text Domain: cloud-search
Domain Path: /languages/
Version: 2.5.0
License: GPL v3

CloudSearch
Copyright (C) 2013-2019, Andrea Landonio - landonio.andrea@gmail.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// Security check
if ( ! defined( 'ABSPATH' ) ) die( 'Direct access to files not allowed' );

if ( ! defined('WP_CLI') || ! WP_CLI ) {
    return;
}

class Cloud_Search_WP_CLI extends WP_CLI_Command {
	public $write_to_file = false;
	public $file = false;
    public $current_method;
	public $current_command;

    /**
     * Create/Update index
     *
     * ## EXAMPLES
     *
     *     wp cloudsearch create_index
     *
     * @synopsis
     */
	public function create_index( ) {
		$assoc_args = wp_parse_args( $assoc_args, $defaults );

		$this->start_feedback( __FUNCTION__, $assoc_args );

		try {
			$result = acs_index_create();

			if ( count( $result->get_data()[ 'fields_with_error' ] ) > 0 && $result->get_data()[ 'fields_managed' ] == 0) {
				// Found only error fields
				$message = new ACS_Message( 'Fields with errors: %s', implode( ACS::SEPARATOR, $result->get_data()[ 'fields_with_error' ] ), ACS_Message::TYPE_ERROR );

				$this->error( $message->get_text() );
			} elseif ( count( $result->get_data()[ 'fields_with_error' ] ) > 0 && $result->get_data()[ 'fields_managed' ] > 0) {
				// Found error fields but one or more index fields created
				$message = new ACS_Message( 'Some index fields created/updated correctly, but there are fields with errors: %s', implode( ACS::SEPARATOR, $result->get_data()[ 'fields_with_error' ] ), ACS_Message::TYPE_ERROR );

				$this->error( $message->get_text() );
			} elseif ( $result->get_data()[ 'fields_managed' ] == 0 ) {
				// Schema already defined (no new index fields)
				$message = new ACS_Message( 'No index fields created/updated, all index fields are already defined', '', ACS_Message::TYPE_INFO );

				$this->success( $message->get_text() );
			} else {
				// Schema created
				$message = new ACS_Message( '%s index fields created/updated', $result->get_data()[ 'fields_managed' ], ACS_Message::TYPE_INFO );

				$this->success( $message->get_text() );
			}
		} catch ( Exception $e ) {
			$message = new ACS_Message( '%s', $e->getMessage(), ACS_Message::TYPE_ERROR );

			$this->error( $message->get_text() );
		}
	}

    /**
     * Run indexing
     *
     * ## EXAMPLES
     *
     *     wp cloudsearch run_indexing
     *
     * @synopsis
     */
	public function run_indexing() {
		// Run indexing action
		try {
			$result = acs_run_indexing();
			$message = new ACS_Message( 'Run indexing started for %s fields', $result->get_data()[ 'fields_managed' ], ACS_Message::TYPE_INFO );

			$this->success( $message->get_text() );
		} catch ( Exception $e ) {
			$message = new ACS_Message( '%s', $e->getMessage(), ACS_Message::TYPE_ERROR );

			$this->error( $message->get_text() );
		}
	}

    /**
     * Sync documents by post_type and post_status
     *
     * ## EXAMPLES
     *
     *     wp cloudsearch sync_documents --write_to_file=yes --chunk=5
     *
     * @synopsis
     */
	public function sync_documents( $args, $assoc_args ) {
		global $wpdb;

		$defaults = array(
			'write_to_file' => 'no',
			'chunk' => 1,
		);

		$assoc_args = wp_parse_args( $assoc_args, $defaults );

		if ( 'yes' == $assoc_args['write_to_file'] ) {
			$this->write_to_file = true;
		}

		$this->start_feedback( __FUNCTION__, $assoc_args );

		// Get settings option
		$settings = ACS::get_instance()->get_settings();

		$acs_schema_types = $settings->acs_schema_types;
		$acs_schema_types = $acs_schema_types ? explode( ACS::SEPARATOR, $acs_schema_types ) : array();

		$chunk = $assoc_args['chunk'];

		$query_args = array(
			'post_type'      => $acs_schema_types,
			'post_status'    => array(
				'publish',
				'pending',
				'draft',
				'auto-draft',
				'future',
				'private',
				'inherit',
				'trash',
			),
			'orderby'        => 'date',
			'sort'           => 'desc',
			'posts_per_page' => ACS::SYNC_CHUNK,
			'paged'          => $chunk
		);

		// Run an intiial query just to get total pages
		$query = new WP_Query( $query_args );
		$total_pages = $query->max_num_pages;
		
		$count = 0;

		// Iterate through the full results by page/chunk
		while ( $chunk <= $total_pages ) {
			$query_args['paged'] = $chunk;

			$this->line('Starting: Chunk ' . $chunk );

			$documents = get_posts( $query_args );

			foreach ( $documents as $post ) {
				// Read post exclusion field
				$excluded = get_post_meta( $post->ID, ACS::EXCLUDE_FIELD, true );

				// Retrieve allowed post statutes to manage
				$allowed_statuses = apply_filters( 'acs_post_transition_allowed_statuses', array( 'publish' ), $post );

				if ( in_array( $post->post_status, $allowed_statuses ) && ( ! isset( $excluded ) || empty( $excluded ) || $excluded != 1 ) ) {
					$this->line( 'Syncing: (' . $post->ID . ') ' . $post->post_title );

					// If post status is "allowed" and is not excluded, add or update it to index
					try {
		                acs_index_document( $post, true );
					}
					catch ( Exception $e ) {
						$message = $e->getMessage();
						$this->warning( $message->get_text() );
					}
				} else {
					$this->line( 'Deleting: (' . $post->ID . ') ' . $post->post_title );

					// If post is not allowed or is excluded, delete it from index
					try {
						acs_delete_document( $post );
					}
					catch ( Exception $e ) {
						$message = $e->getMessage();
						$this->warning( $message->get_text() );

					}
				}
				
				$count++;
			}

			$chunk++;
		}

		$this->success( $count . ' documents(s) synced!' );
	}

	/**
	 * Starts output and sets some class vars for use in logging
	 *
	 * @param string The name of the current function being run
	 * @param array An array of the arguments passed to WP CLI for the current fucntion
	 */
	public function start_feedback( $function, $assoc_args ) {
		$this->current_method = $function;
		$this->current_command = 'wp go_ossein ' . $this->current_method;

		foreach ( $assoc_args as $key => $value ) {
			$this->current_command .= ' --' . $key . '=' . $value;
		}

		$this->line( 'Starting: ' . $this->current_command );
	}

	/**
	 * Writes a line to output or a log file
	 *
	 * @param string Some text you want written
	 */
	public function line( $message ) {
		if ( $this->write_to_file ) {
			$this->open_file();
			$this->output .= $message . "\n";
			fwrite( $this->file, $message . "\n" );
		} else {
			WP_CLI::line( $message );
		}
	}

	/**
	 * Writes a warning line to output or a log file
	 *
	 * @param string Some text you want written
	 */
	public function warning( $message ) {
		if ( $this->write_to_file ) {
			$this->open_file();
			$this->output .= 'Warning: ' . $message . "\n";
			fwrite( $this->file, 'Warning: ' . $message . "\n" );
		} else {
			WP_CLI::warning( $message );
		}
	}

	/**
	 * Writes a success line to output or a log file
	 *
	 * @param string Some text you want written
	 */
	public function success( $message ) {
		if ( $this->write_to_file ) {
			$this->open_file();
			$this->output .= 'Success: ' . $message . "\n";
			fwrite( $this->file, 'Success: ' . $message . "\n" );
			fclose( $this->file );
		} else {
			WP_CLI::success( $message );
		}
	}

	/**
	 * Writes an error line to output or a log file AND stops the script
	 */
	public function error( $message ) {
		if ( $this->write_to_file ) {
			$this->open_file();
			$this->output .= 'Error: ' . $message . "\n";
			fwrite( $this->file, 'Error: ' . $message . "\n" );
			fclose( $this->file );
			die;
		} else {
			WP_CLI::error( $message );
		}
	}

	/**
	 * Opens a log file if needed
	 */
	public function open_file() {
		if ( ! $this->file ) {
			$this->file = fopen( ABSPATH . 'cloudsearch-wp-cli-' . str_replace( '_', '-', $this->current_method ) . '.txt', 'w+' );
		}
	}
}

WP_CLI::add_command( 'cloudsearch', 'Cloud_Search_WP_CLI' );