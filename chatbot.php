<?php
   /*
	Plugin Name: I-ChatBot
	Plugin URI: 
	Description: I-ChatBot is an intelligent chatbot
	Version: 1.1
	Author: Amirul Momenin patainc@gmail.com
	Author URI: 
	License: GPL
	*/
	ob_start(); // line 1
	session_start(); // line 2
	$PLUGIN_URL = plugin_dir_url(__FILE__);
	define('CHATBOT_PLUGIN_URL',substr($PLUGIN_URL,0,strlen($PLUGIN_URL)-1));
	define('CHATBOT_PLUGIN_PATH', str_replace('\\', '/', dirname(__FILE__)) );
	
	
	register_activation_hook(__FILE__,'chatbot_install'); 
	register_deactivation_hook( __FILE__, 'chatbot_remove' );
	function chatbot_install()
	 {  
	    create_page('chatbot');
	 
		global $chatbot_db_version;
		$chatbot_db_version = "1.0";
		global $wpdb;
		global $chatbot_db_version;
	
	
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	 
		
		
		add_option("chatbot_db_version", $chatbot_db_version);
		
		//create page
		include_once dirname(__FILE__) . '/create-page.php';
		
	}
	
	function create_table() {
		global $wpdb;
		global $your_db_name;
		$charset_collate = $wpdb->get_charset_collate();
	 
		 $sql1 = "  CREATE TABLE ".$wpdb->prefix ."chatbot (
					  `id` int(10) NOT NULL AUTO_INCREMENT,		
					  `question` text,
                      `answer` text,
					  `created_at` datetime DEFAULT NULL,
					  `updated_at` datetime DEFAULT NULL,
					   UNIQUE KEY id (id)
					) $charset_collate;";
					
		  $sql2 = "INSERT INTO ".$wpdb->prefix ."chatbot ( `question`, `answer`, `created_at`, `updated_at`) VALUES
						( 'Hi', 'Welcome to O2. How can I help you today?\r\n', '2019-06-07 06:39:34', '2019-06-07 10:28:11'),
						( 'How may I buy a product?', 'Register & Login add product and do online order ', '2019-06-07 06:40:33', NULL),
						( 'what about delivery', 'Delivery is within 7 days from your orders', '2019-06-07 06:41:11', NULL),
						( 'hello', 'Hi, How can I help you?\r\n\r\n', '2019-06-07 10:53:56', '2019-12-17 00:00:00');
						";	
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql1);
			dbDelta($sql2);
	}
	// run the install scripts upon plugin activation
	register_activation_hook(__FILE__,'create_table');
	
	function chatbot_remove()
	{
		global $wpdb;
		
		//remove page
		global $wpdb;
	
		$the_page_title = get_option( "my_plugin_page_title" );
		$the_page_name = get_option( "my_plugin_page_name" );
		$the_page_id = get_option( 'my_plugin_page_id' );
		if( $the_page_id ) {
			wp_delete_post( $the_page_id ); 
		}
		delete_option("my_plugin_page_title");
		delete_option("my_plugin_page_name");
		delete_option("my_plugin_page_id");
	}
	
	function create_page($title)
	{
		global $wpdb; 
		
		//chatbot
		$the_page_title = $title;
		$the_page_name = $title;
		
		delete_option("my_plugin_page_title");
		add_option("my_plugin_page_title", $the_page_title, '', 'yes');
		
		delete_option("my_plugin_page_name");
		add_option("my_plugin_page_name", $the_page_name, '', 'yes');
		
		delete_option("my_plugin_page_id");
		add_option("my_plugin_page_id", '0', '', 'yes');
		
		$the_page = get_page_by_title( $the_page_title );
		if ( ! $the_page ) {
			$_p = array();
			$_p['post_title'] = $the_page_title;
			$_p['post_content'] = "[".$title."]";
			$_p['post_status'] = 'publish';
			$_p['post_type'] = 'page';
			$_p['comment_status'] = 'closed';
			$_p['ping_status'] = 'closed';
			$_p['post_category'] = array(1);
			$the_page_id = wp_insert_post( $_p );
		}
	}

    //Admin		
	add_action('admin_menu', 'chatbot_manage');
	function chatbot_manage(){
	  add_menu_page('Chatbot Settings', 'Chatbot', 'manage_options', 'chatbot', 'chatbot_settings_func');
	  add_submenu_page( 'chatbot', 'ChatData', 'ChatData', 'manage_options', 'chatdata', 'chatdata_func');
	}
	 
	function chatbot_settings_func(){
		 include_once dirname(__FILE__) . '/admin_chatbot.php';   
	}   

	function chatdata_func(){
		 include_once dirname(__FILE__) . '/admin_chatdata.php';   
	} 
	
	//short code chatbots
	function chatbot_sort_code_func( $atts ) {
		include_once dirname(__FILE__) . '/template/front/chatbot.php';
	}
	add_shortcode( 'chatbot', 'chatbot_sort_code_func' );