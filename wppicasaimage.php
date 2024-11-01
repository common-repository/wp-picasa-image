<?php
/*
Plugin Name: WP-Picasa-Image
Plugin URI: http://pigsky.net/plugin-wp-picasa-image
Description: 可在日志插入来自picasa的图片，并且可以调整大小。(Insert a image from your picasa albums and you can set its size.)
Version: 1.0
Author: Addo Zhang
Author URI: http://pigsky.net
*/
$win_path = str_replace("\\", "/", ABSPATH);
define('WPPFOLDER', dirname(plugin_basename(__FILE__)));
define('WPPPATH', '../wp-content/plugins/'.WPPFOLDER.'/');


if(!class_exists('WP_Picasa')){
	class WP_Picasa {
		function WP_Picasa(){
		}

		#add picasa button on editor-toolbar
		function add_picasa_button($context=''){
			echo $wpp_path;
			global $post_ID, $temp_ID;
			$uploading_iframe_ID = (int) (0 == $post_ID ? $temp_ID : $post_ID);
			$picasa_upload_iframe_src = ''.WPPPATH.'picasa_upload.php?post_id='.$uploading_iframe_ID.'&type=image';
			$picasa_title = 'Add Image From Picasa';
			$out = '<a href="'.$picasa_upload_iframe_src.'&TB_iframe=true" id="add_picasa_image" class="thickbox" title="'
			.$picasa_title.'"><img src="'.WPPPATH.'/images/picasa.png"/></a>';
			$context.=$out;
			return $context;
		}
	}
}

if(class_exists('WP_Picasa')){
	$wpp = new WP_Picasa();
	add_filter( 'media_buttons_context', array(&$wpp,'add_picasa_button'), 99 );
}
?>