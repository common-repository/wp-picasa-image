<?php
/*
Author: Addo Zhang
Author URI: http://nophoebe.cn
 */

define('WP_ADMIN', TRUE);
//echo realpath("./");
require_once('../../../wp-load.php');
define('WPPWINPATH',str_replace('\\', '/', ABSPATH));
define('WPPFOLDER', dirname(plugin_basename(__FILE__)));
define('WPPPATH', '../wp-content/plugins/'.WPPFOLDER.'/');
require_once(WPPWINPATH . 'wp-admin/includes/admin.php');
$size_arry = array(32=>'s32',48=>'s48',64=>'s64',72=>'72',144=>'s144',160=>'s160',200=>'s200',288=>'s288',320=>'s320',400=>'s400',512=>'s512',576=>'s576',640=>'s640',720=>'s720',800=>'s800',);
$size_select = '<tr class="align">
					<th valign="top" scope="row" class="label"><p><label for="align">Image Size</label></p></th>
					<td class="field">
						<select size="1" id="size" name="size">';
foreach ($size_arry as $key => $value){
	if($key == 512){
		$size_select.='<option value="'.$value.'" SELECTED>'.$key.'</option>';
	}else {
		$size_select.='<option value="'.$value.'">'.$key.'</option>';
	}
}
$size_select.='</select>
					</td>
				</tr>';
$default_align = 'none';
$type = $_GET['type'];
$post_id = $_GET['post_id'];

if($type && $post_id){
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php do_action('admin_xml_ns'); ?> <?php language_attributes(); ?>>
	<head>
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
		<title><?php bloginfo('name') ?> &rsaquo; <?php _e('Uploads'); ?> &#8212; <?php _e('WordPress'); ?></title>
		<link media="all" type="text/css" href="css/picasa.css" rel="stylesheet">
		<?php
		wp_enqueue_style( 'global' );
		wp_enqueue_style( 'wp-admin' );
		wp_enqueue_style( 'colors' );
		wp_enqueue_style( 'media' );
		?>
		<script type="text/javascript">
			//<![CDATA[
			function addLoadEvent(func) {if ( typeof wpOnload!='function'){wpOnload=func;}else{ var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}}
			//]]>
		</script>
		<?php
		do_action('admin_print_styles');
		do_action('admin_print_scripts');
		do_action('admin_head');
		if ( is_string($content_func) )
		do_action( "admin_head_{$content_func}" );
		?>
	</head>
	<body id="media-upload">
		<form enctype="multipart/form-data" method="post" action="<?php echo ''.WPPPATH.'picasa_upload.php?post_id='.$uploading_iframe_ID.'&type=image' ?>" class="media-upload-form type-form validate" id="<?php echo $type; ?>-form">
			<input type="hidden" name="post_id" id="post_id" value="<?php echo (int) $post_id; ?>" />
			<?php wp_nonce_field('media-form'); ?>

			<h3 class="media-title">Add Image from Picasa</h3>

			<script type="text/javascript">
				//<![CDATA[
				var addExtImage = {

					width : '',
					height : '',
					align : 'alignnone',

					insert : function() {
						var t = this, html, f = document.forms[0], cls, title = '', alt = '', caption = null;

						if ( '' == f.src.value || '' == t.width ) return false;

						if ( f.title.value ) {
							title = f.title.value.replace(/['"<>]+/g, '');
							title = ' title="'+title+'"';
						}

						if ( f.alt.value ) {
							alt = f.alt.value.replace(/['"<>]+/g, '');
	<?php if ( ! apply_filters( 'disable_captions', '' ) ) { ?>
					caption = f.alt.value.replace(/'/g, '&#39;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
		<?php } ?>
					}

					cls = caption ? '' : ' class="'+t.align+'"';

					var resrc = f.src.value;

					/*src_array = resrc.split("//")[1].split["\/"];*/

					resrc = resrc.split("//")[1];
					src_array = ("/"+resrc).split("/");
					
					//test the address whether comes from picasa
					var regCat = /lh[0123456789]/;
					if(regCat.test(src_array[0])){
						src_array[src_array.length-1]=f.size.value+"/"+src_array[src_array.length-1];

						resrc = "http:/"+src_array.join("/");
						html = '<img alt="'+alt+'" src="'+resrc+'"'+title+cls+' width="'+t.width+'" height="'+t.height+'" />';
					}else {
						html = '<img alt="'+alt+'" src="'+f.src.value+'"'+title+cls+' width="'+t.width+'" height="'+t.height+'" />';
					}
					if ( f.url.value )
						html = '<a href="'+f.url.value+'">'+html+'</a>';

					if ( caption )
						html = '[caption id="" align="'+t.align+'" width="'+t.width+'" caption="'+caption+'"]'+html+'[/caption]';

					var win = window.dialogArguments || opener || parent || top;
					win.send_to_editor(html);
				},

				resetImageData : function() {
					var t = addExtImage;

					t.width = t.height = '';
					document.getElementById('go_button').style.color = '#bbb';
					if ( ! document.forms[0].src.value )
						document.getElementById('status_img').src = 'images/required.gif';
					else document.getElementById('status_img').src = 'images/no.png';
				},

				updateImageData : function() {
					var t = addExtImage;

					t.width = t.preloadImg.width;
					t.height = t.preloadImg.height;
					document.getElementById('go_button').style.color = '#333';
					document.getElementById('status_img').src = 'images/yes.png';
				},

				getImageData : function() {
					var t = addExtImage, src = document.forms[0].src.value;

					if ( ! src ) {
						t.resetImageData();
						return false;
					}
					document.getElementById('status_img').src = 'images/loading.gif';
					t.preloadImg = new Image();
					t.preloadImg.onload = t.updateImageData;
					t.preloadImg.onerror = t.resetImageData;
					t.preloadImg.src = src;
				}
			}
			//]]>
			</script>

			<div id="media-items">
				<div class="media-item media-blank">
					<?php echo '<table class="describe"><tbody>
																											<tr>
																												<th valign="top" scope="row" class="label" style="width:120px;">
																													<span class="alignleft"><label for="src">' . __('Image URL') . '</label></span>
																													<span class="alignright"><img id="status_img" src="images/required.gif" title="required" alt="required" /></span>
																												</th>
																												<td class="field"><input id="src" name="src" value="" type="text" aria-required="true" onblur="addExtImage.getImageData()" /></td>
																											</tr>

																											<tr>
																												<th valign="top" scope="row" class="label">
																													<span class="alignleft"><label for="title">' . __('Image Title') . '</label></span>
																													<span class="alignright"><abbr title="required" class="required">*</abbr></span>
																												</th>
																												<td class="field"><p><input id="title" name="title" value="" type="text" aria-required="true" /></p></td>
																											</tr>

																											<tr>
																												<th valign="top" scope="row" class="label">
																													<span class="alignleft"><label for="alt">Image Caption</label></span>
																												</th>
																												<td class="field"><input id="alt" name="alt" value="" type="text" aria-required="true" />
																												<p class="help">Also used as alternate text for the image</p></td>
																											</tr>

																											<tr class="align">
																												<th valign="top" scope="row" class="label"><p><label for="align">' . __('Alignment') . '</label></p></th>
																												<td class="field">
																													<input name="align" id="align-none" value="none" onclick="addExtImage.align=\'align\'+this.value" type="radio"' . ($default_align == 'none' ? ' checked="checked"' : '').' />
																														<label for="align-none" class="align image-align-none-label">' . __('None') . '</label>
																													<input name="align" id="align-left" value="left" onclick="addExtImage.align=\'align\'+this.value" type="radio"' . ($default_align == 'left' ? ' checked="checked"' : '').' />
																														<label for="align-left" class="align image-align-left-label">' . __('Left') . '</label>
																													<input name="align" id="align-center" value="center" onclick="addExtImage.align=\'align\'+this.value" type="radio"' . ($default_align == 'center' ? ' checked="checked"' : '').' />
																														<label for="align-center" class="align image-align-center-label">' . __('Center') . '</label>
																													<input name="align" id="align-right" value="right" onclick="addExtImage.align=\'align\'+this.value" type="radio"' . ($default_align == 'right' ? ' checked="checked"' : '').' />
																														<label for="align-right" class="align image-align-right-label">' . __('Right') . '</label>
																												</td>
																											</tr>

																												'.$size_select.'

																											<tr>
																												<th valign="top" scope="row" class="label">
																													<span class="alignleft"><label for="url">' . __('Link Image To:') . '</label></span>
																												</th>
																												<td class="field"><input id="url" name="url" value="" type="text" /><br />

																													<button type="button" class="button" value="" onclick="document.forms[0].url.value=null">' . __('None') . '</button>
																													<button type="button" class="button" value="" onclick="document.forms[0].url.value=document.forms[0].src.value">' . __('Link to image') . '</button>
																												<p class="help">' . __('Enter a link URL or click above for presets.') . '</p></td>
																											</tr>

																											<tr>
																												<td></td>
																												<td>
																													<input type="button" class="button" id="go_button" style="color:#bbb;" onclick="addExtImage.insert()" value="' . attribute_escape(__('Insert into Post')) . '" />
																												</td>
																											</tr>
																									</tbody></table>
																									';
					?>
				</div>
			</div>
		</form>
	</body>
</html>
<?php
add_action('wp_picasa','wp_picasa_form');
}
?>
