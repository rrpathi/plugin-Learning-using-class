<?php 
/*
Plugin Name:  Dropbox Plugin
Plugin URI:   https://developer.wordpress.org/plugins/the-basics/
Description:  Basic WordPress Plugin Header Comment
Version:      2.0
Author:       WordPress.org
Author URI:   https://developer.wordpress.org/
*/

include_once 'vendor/autoload.php';
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\DropboxFile;
use Kunnu\Dropbox\Exceptions\DropboxClientException;
class DropboxUpload{
	public $folder =  WP_CONTENT_DIR.'/to_upload';
	public function __construct(){
		$this->initial();
	}

	public function initial(){
		$this->pre_define();
		$this->activation_hook();
		$this->db_prefix();
		$this->action();
		$this->fillters();
	}



	public function pre_define(){

		define('PLUGIN_DIR_URL',plugin_dir_url(__FILE__));
		define('PLUGIN_DIR_PATH',plugin_dir_path(__FILE__));
		// http://localhost/dropbox-wordpress/wp-content/plugins/Dropbox/
	}

	public function fillters(){
		add_filter('own_filter',array($this,'call_back'));
		apply_filters('own_filter','ragupathi');
	}

	public function call_back(){
		 file_put_contents(WP_CONTENT_DIR . "/test-ragu.txt",var_export($_POST), FILE_APPEND);
		 file_put_contents("test-ragu.txt","hello world", FILE_APPEND);

		// return "Hello World";
	}

	public function action(){
		add_action('admin_enqueue_scripts',array($this,'script'));
		add_action('admin_menu',array($this,'menu'));
		add_action('wp_ajax_add_dropbox_account_details',array($this,'credentials'));
		add_action('wp_ajax_my_ajax_function',array($this,'dropbox_sdk'));
	}

	public function credentials(){
		unset($_POST['action']);
		global $wpdb;
		function example_callback($string){
			return array('app_key' =>'ragupathi','app_secret'=>'10000','access_token'=>'sdjfgsdjf');
		}
		add_filter( 'example_filter', 'example_callback',10,1);
		$sample = apply_filters( 'example_filter', $_POST);

		$insert = $wpdb->insert('wp_dropbox_details', $sample);
		if ($insert) {
			echo '1';
		} else {
			echo '0';
		}
		die();
	}
	public function dropbox_sdk(){
		$dir  = $this->folder;
		$option =$this->folder;
		return $this->recursiveScan($dir,$option); 
	}

	public function recursiveScan($dir,$option){
		global $wpdb;
		$table_name = $this->db_prefix().'dropbox_details';
		$data = $wpdb->get_results("SELECT * FROM $table_name",ARRAY_A)[0];
		$app = new DropboxApp($data['app_key'],$data['app_secret'],$data['access_token']);
		$dropbox = new Dropbox($app);
		$tree = glob(rtrim($dir, '/') . '/*');
		if (is_array($tree)) {
			foreach($tree as $file) {
				if(is_file($file)){
					$folder_file_name = str_replace($option, '', $file);
					$file = new DropboxFile($file);
					$uploadedFile = $dropbox->upload($file,$folder_file_name);
					if($uploadedFile){
					}
				}elseif (is_dir($file)) {
					$this->recursiveScan($file,$option);
				}
			}
		}


	}

	public function menu(){
		add_menu_page('Dropbox Page','Dropbox Menu','manage_options','dropbox_view',array($this,'dropbox_view'));
	}

	public function dropbox_view(){
		include PLUGIN_DIR_PATH.'view/upload.php';
	}

	public function script(){
		wp_enqueue_script('jquery');
		wp_enqueue_script('custome.js',PLUGIN_DIR_URL.'js/custome.js');
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
		`access_token` varchar(80) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;
		";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
		// ABSPATH is current project Directory dropbox-wordpress
}
	


$obj = new DropboxUpload();
// echo PLUGIN_DIR;
// exit();
