<?php
/**
 * Compi Template Hooks
 *
 * Action/filter hooks used for Compi functions/templates
 *
 * @since    1.0.0
 * @author wpdots
 * @category Core
 * @package  Compi
 * @license  GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Adds a generator tag in the header.
add_action( 'wp_head', 'generator' );

?>
