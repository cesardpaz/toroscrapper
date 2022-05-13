<?php 
/**
 * Plugin Name:         toroscrapper
 * Plugin URI:          #
 * Description:         Add demo to themes of movies for Torothemes
 * Version:             1.0.0
 * Author:              César De Paz
 * Author URI:          #
 * License:             GPL2
 * License URI:         https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:         toroscrapper
 * Domain Path:         /languages
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}
global $wpdb;

define( 'TOROSCRAPPER_REALPATH_BASENAME_PLUGIN', dirname( plugin_basename( __FILE__ ) ) . '/' );
define( 'TOROSCRAPPER_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'TOROSCRAPPER_DIR_URI', plugin_dir_url( __FILE__ ) );
define( 'TOROSCRAPPER_VERSION', '1.2.8' );

require_once TOROSCRAPPER_DIR_PATH . 'includes/class-toroscrapper-master.php';

function toroscrapper_master() {
    $bc_master = new TOROSCRAPPER_Master;
    $bc_master->run();
}

toroscrapper_master();		


function convertToHoursMins($time, $format = '%02dh %02dm') {
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
}