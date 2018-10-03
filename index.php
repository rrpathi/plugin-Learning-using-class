<?php 
/*
Plugin Name:  Dropbox Plugin
Plugin URI:   https://developer.wordpress.org/plugins/the-basics/
Description:  Basic WordPress Plugin Header Comment
Version:      2.0
Author:       WordPress.org
Author URI:   https://developer.wordpress.org/
*/


class Dropbox{
	public function __construct(){
		$this->initial();
	}

	public function initial(){
		$this->pre_define();
		$this->activation_hook();
		$this->db_prefix();
		$this->action();
	}

	public function pre_define(){
		define('PLUGIN_DIR',plugin_dir_url(__FILE__));
		// http://localhost/dropbox-wordpress/wp-content/plugins/Dropbox/
	}

	public function action(){
		add_action('admin_enqueue_scripts',array($this,'script'));
	}

	public function script(){
		wp_enqueue_script('jquery');
		wp_enqueue_script('custome.js',PLUGIN_DIR.'js/custome.js');
	}

	public function db_prefix(){
		global $wpdb;
		$this->wpdb = $wpdb;
		return $this->wpdb->prefix;
	}

	public function activation_hook(){
		register_activation_hook(__FILE__,array($this,'activation_table'));
		// __FILE__  current file location (index.php)
	}

	public function activation_table(){
		$table_name  = $this->db_prefix."dropbox_details1";
		$sql = "CREATE TABLE `$table_name` (
		`app_key` varchar(15) NOT NULL,
		`app_secret` varchar(15) NOT NULL,
		`access_token` varchar(40) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;
		";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		}
		// ABSPATH is current project Directory dropbox-wordpress
	}
	


$obj = new Dropbox();
// echo PLUGIN_DIR;
// exit();
