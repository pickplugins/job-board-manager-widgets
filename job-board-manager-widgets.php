<?php
/*
Plugin Name: Job Board Manager - Widgets
Plugin URI: http://pickplugins.com
Description: Widgets for Job Board Manager.
Version: 1.0.2
Author: pickplugins
Author URI: http://pickplugins.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


class JobBoardManagerWidgets{
	public function __construct(){

        define('job_bm_widget_plugin_url', plugins_url('/', __FILE__)  );
        define('job_bm_widget_plugin_dir', plugin_dir_path( __FILE__ ) );

		// Class
		require_once( plugin_dir_path( __FILE__ ) . 'includes/class-widget-latest-job.php');	
		require_once( plugin_dir_path( __FILE__ ) . 'includes/class-widget-featured-job.php');	
		require_once( plugin_dir_path( __FILE__ ) . 'includes/class-widget-expired-today.php');	
		
		add_action( 'wp_enqueue_scripts', array( $this, 'job_bm_widget_front_scripts' ) );
		add_action( 'widgets_init', array( $this, 'job_bm_job_bm_widget_load_widget' ) );
		
		add_action( 'plugins_loaded', array( $this, 'job_bm_widget_load_textdomain' ));
	}
	
	
	public function job_bm_widget_load_textdomain() {

        $locale = apply_filters( 'plugin_locale', get_locale(), 'job-board-manager-widgets' );
        load_textdomain('job-board-manager-widgets', WP_LANG_DIR .'/job-board-manager-widgets/job-board-manager-widgets-'. $locale .'.mo' );

	  load_plugin_textdomain( 'job-board-manager-widgets', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
	}
	

	
	function job_bm_job_bm_widget_load_widget(){
		register_widget( 'WidgetLatestJob' );
		register_widget( 'WidgetFeaturedJob' );
		register_widget( 'WidgetExpiredToday' );
	}
	
	public function job_bm_widget_front_scripts(){
        wp_register_style('job-bm-widgets', job_bm_widget_plugin_url.'assets/front/css/job-bm-widgets.css');
	}
		
} new JobBoardManagerWidgets();