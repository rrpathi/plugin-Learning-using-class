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
		$this->hooks();
		$this->db_prefix();
		$this->action();
		$this->apply_filter();
	}
	public function pre_define(){
		define('PLUGIN_DIR_URL',plugin_dir_url(__FILE__));
		define('PLUGIN_DIR_PATH',plugin_dir_path(__FILE__));
		// http://localhost/dropbox-wordpress/wp-content/plugins/Dropbox/
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
		add_action('wp_ajax_shot_code_register',array($this,'add_new_shotcode'));
		add_action('wp_ajax_edit_short_code',array($this,'edit_short_code'));
		add_filter('shot-code',array($this,'shot_code_callback'),10,1);
	}

	public function edit_short_code(){
		global $wpdb;
		$table_name = $this->db_prefix().'custome_form';
		$edit_short_code = $wpdb->get_results("SELECT * FROM $table_name WHERE id ='".$_POST['short_code_id']."'",ARRAY_A)[0];
		echo "<pre>";
		print_r($edit_short_code);
		
	}
	public function credentials(){
		unset($_POST['action']);
		global $wpdb;
		function example_callback($string){
			return array('app_key' =>'2qc3n2l4gqwb7uc','app_secret'=>'v7k9uce68lf85ch','access_token'=>'pUmE_WvIvEAAAAAAAAABHrijcymlbi0ed8vh5m3U5ua9pQhgGVVLkv23YlZAvDeD');
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

	public function add_new_shotcode(){
		// echo "Hello World";
		 global $wpdb;
		 $shot_code = json_decode(stripslashes($_POST['shot_code']));
		 $shortcode_name = $shot_code->shortcode_name;
		 $new_label_data['label_name'] = $shot_code->label_name;
		 $new_field_data['field_name'] = $shot_code->field_name;
		 foreach ($new_label_data as $key => $value) {
		 	foreach ($value as $key => $label_value) {
		 		$newlabel_value[] = $label_value; 
		 	}
		 }
		  foreach ($new_field_data as $key => $value) {
		 	foreach ($value as $key => $field_value) {
		 		$newfield_value[] = $field_value; 
		 	}
		 }
		 
		 $form_array = serialize(array_combine($newlabel_value,$newfield_value));
		 $column_values = array('form_id'=>$shortcode_name,'string'=>$form_array);
		 $shotcode = $wpdb->insert('wp_custome_form',$column_values);
		 if($shotcode){
		 	echo "1";
		 }else{
		 	echo "0";
		 }

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
		add_menu_page('Dropbox Page','Dropbox Upload','manage_options','dropbox_view');
		add_submenu_page('dropbox_view','File Upload','Dropbox Upload','manage_options','dropbox_view',array($this,'dropbox_view'));
		add_submenu_page('dropbox_view','Create Form','Add Shot Code','manage_options','create-form',array($this,'custome_form'));
		add_submenu_page('dropbox_view','List Shot Code','List Shot Code','manage_options','list-shot-code',array($this,'list_shot_code'));

	}
	public function custome_form(){
		include PLUGIN_DIR_PATH.'view/custome_form.php';
	}
	public function list_shot_code(){
		include PLUGIN_DIR_PATH.'view/list_shot_code.php';
	}

	public function dropbox_view(){
		include PLUGIN_DIR_PATH.'view/upload.php';
	}
	public function script(){
		wp_enqueue_script('jquery');
		wp_enqueue_script('custome.js',PLUGIN_DIR_URL.'js/custome.js');
		wp_enqueue_script('form-js',PLUGIN_DIR_URL.'js/form.js');
	}
	public function db_prefix(){
		global $wpdb;
		$this->wpdb = $wpdb;
		return $this->wpdb->prefix;
	}

	public function shot_code_callback($value){
		foreach ($value as $key => $value) {
			$shortcode[$value['form_id']] = unserialize($value['string']);
		}
		return $shortcode;
	}
	public function apply_filter(){
		global $wpdb;
		$table_name  = $this->db_prefix()."custome_form";
		$value =  $wpdb->get_results("SELECT * FROM $table_name ",ARRAY_A);
		$apply_filter = apply_filters('shot-code',$value);
		foreach ($apply_filter as  $shortcode_name => $shortcode_value) {
			add_shortcode($shortcode_name,function() use ($shortcode_value){
				foreach($shortcode_value as $key =>$value){
					echo "$key:<input type='text' value='".$value."'><br>";
				}
			});
		}
	}


	public function hooks(){
		register_activation_hook(__FILE__,array($this,'activation_table'));
		register_deactivation_hook( __FILE__,array($this,'deactivation_hook'));
		// __FILE__  current file location (index.php)
	}

	public function deactivation_hook(){
		global $wpdb;
		$table_name  = $this->db_prefix()."dropbox_details";
		$wpdb->query("TRUNCATE TABLE $table_name ");

	}

	public function activation_table(){
		$table_name  = $this->db_prefix()."dropbox_details";
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
