<?php
/**
 * @package WP-Disclaim-Me
 * @author Dominic Fallows
 * @version 0.3.1
 */
/*
Plugin Name: WP-Disclaim-Me
Plugin URI: http://dominicfallows.co.uk/apps-plugins/wp-disclaim-me/
Author: Dominic Fallows
Version: 0.3.1
Author URI: http://dominicfallows.co.uk/

Description: WP-Disclaim-Me is a plugin that lets you create a disclaimer that a visitor to your blog / website must accept before they can access your posts and pages.

WP-Disclaim-Me has been tested in Wordpress version 2.5 or above. It is in beta so comments are very welcome!

Features
    * Tested with Wordpress 2.8
    * Set your own disclaimer text from the admin pages
    * Set your own accept button text from the admin pages
	* NEW: WYSIWIG disclaimer text edit in the admin pages

COMING SOON
    * Add image to disclaimer from the admin pages
    * Change style/CSS of disclaimer from the admin pages
*/

/*  Copyright 2009 Dominic Fallows (email : apps@dominicfallows.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php
add_action('send_headers', 'session');
add_action( 'send_headers', 'disclaimerpage');
add_action('admin_menu', 'WP_Disclaim_Me_menu');
register_activation_hook(__FILE__,'wp_disclaim_me_config_install');

function session() {
	if(!is_admin()) {
	session_start();
	//	$session_expire = 60*60 ; // onehour
	}	
}

$setvalue = $_POST['value'];
$error = NULL;

function setacceptance() {
$_SESSION['accepted'] = "Yes";
}

//INSTALL
function wp_disclaim_me_config_install () {
	
	if (!get_option('wp_disclaim_me_disclaimer'))
		{ update_option('wp_disclaim_me_disclaimer', "Do you accept my disclaimer?"); }
	if (!get_option('wp_disclaim_me_accepttext'))
		{ update_option('wp_disclaim_me_accepttext', "Yes I do"); }
}
//INSTALL END


//ADMIN
function WP_Disclaim_Me_menu() {
  add_options_page('WP-Disclaim-Me Options', 'WP-Disclaim-Me', 8, 'WP-Disclaim-Me', 'WP_Disclaim_Me_options');
}

function WP_Disclaim_Me_options() { 
	
	if($_POST['wp_disclaim_me_hidden'] == 'Y') {
			//Form data sent
			$disclaimer = $_POST['disclaimer'];
			update_option('wp_disclaim_me_disclaimer', $disclaimer);
			
			$accepttext = $_POST['accepttext'];
			addslashes(update_option('wp_disclaim_me_accepttext', $accepttext));
			?>
			<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
			<?php
		} 
		
			//Normal page display
			$disclaimer = get_option('wp_disclaim_me_disclaimer'); //for use with the tinyMCE textarea - don't need to stripslashes
			$accepttext = stripslashes(get_option('wp_disclaim_me_accepttext'));

	?>
	
<script type="text/javascript" src="/wp-includes/js/tinymce/tiny_mce.js"></script>
<script type="text/javascript">
<!--
tinyMCE.init({
theme : "advanced",
theme_advanced_buttons1 : "bold,italic,underline,|,undo,redo,|,justifyleft,justifycenter,justifyright,|,forecolor,backcolor,bullist,numlist,|,indent,outdent,|,hr,|,code",
theme_advanced_buttons2 : "fontselect,fontsizeselect,|",
theme_advanced_buttons3 : "",
theme_advanced_default_background_color : "#FF00FF",
theme_advanced_toolbar_location : "top",
theme_advanced_toolbar_align : "left",
theme_advanced_statusbar_location : "bottom",
theme_advanced_resizing : true,
auto_resize: true,
mode : "exact",
elements : "disclaimer",
width : "565",
height : "200"
});
-->
</script>
 <div class="wrap">
<div id="icon-options-general" class="icon32"><br /></div>
<h2>WP-Disclaim-Me</h2>
<h3>What is your disclaimer text?</h3>


<!--      <p class="submit">  
#         <input type="submit" name="Submit" value="<?php _e('Update Options', 'oscimp_trdom' ) ?>" />  
#         </p> -->


<form name="wp_disclaim_me_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
<input type="hidden" name="wp_disclaim_me_hidden" value="Y">
	
<table class="form-table">
	<tr valign="top">
	<th scope="row"><label for="disclaimer">Disclaimer Text</label></th>
	<td>
	<div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea">
		<?php /* the_editor($content = $disclaimer, $id = 'disclaimer', $prev_id = 'title'); */ ?>
		</div>
<textarea name="disclaimer" id="disclaimer" rows="10" cols="40"><?php echo $disclaimer; ?></textarea>

	</td>
	</tr>
	<tr valign="top">
	<th scope="row"><label for="accepttext">Accept Button Text</label></th>
	<td><input type="text" name="accepttext" id="accepttext" rows="1" col="60" value="<?php echo $accepttext; ?>"></td>
	</tr>
</table>

<p class="submit"><input type="submit" name="Submit" class="button-primary" value="Save Changes" /></p>
</form>

<?  }
//ADMIN END


function addcss() {
	echo "
		<style type=\"text/css\">
div.disclaimer_bg {
	position:absolute;
}

body > div.disclaimer_bg { 
			font-family: arial;
			font-size: 14px;
			display: block;
			width: 100%;
			height:100%;
			z-index: 60;
			background-color:#000;
			overflow:visible;
			position:fixed;
			  }

			  .disclaimer_inner {
			    color: black;
			    padding: 30px;
			height:100%;
			  }

			  .disclaimer_body {
			    background: #fff;
			    padding: 20px;
			    text-align: center;
			margin:0 auto;
			width:80%;
			  }

			  .disclaimer_body h1 {
			   color:#000;
			font-size:1.5em;
			margin:10px;
			padding:0;
			  }

			  #disclaimer_bg { top: 0; left: 0; }

		</style>
	";
	echo "!--[if gte IE 5.5]><![if lt IE 7]>
	div.disclaimer_bg {
	position:absolute;
	left: expression( ( 0 + ( ignoreMe2 = document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft ) ) + 'px' );
	top: expression( ( 0 + ( ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop ) ) + 'px' );
	}
	<![endif]><![endif]-->";
}


function disclaimerpage() {
	
	$setvalue = $_POST['setvalue'];
	if(isset($setvalue) && $setvalue=="Yes") {
		setacceptance();
	}
	
		if($_SESSION['accepted'] != "Yes" && !is_admin()) { 
			
			add_action( 'wp_head', 'addcss');
			add_action( 'wp_footer', 'disclaimercontent');
		}
}

function disclaimercontent() {
	?>			
	<div id="disclaimer_bg" class="disclaimer_bg">
<div class="disclaimer_inner">
<div class=" disclaimer_body">
<h1><?php echo bloginfo('name'); ?></h1>
<form method="post" action="<?php echo $PHP_SELF;?>">

<? echo stripslashes(get_option('wp_disclaim_me_disclaimer')); ?><br><br>

<input type="hidden" value="Yes" name="setvalue" id="setvalue">
<input type="submit" value="<? echo stripslashes(get_option('wp_disclaim_me_accepttext')); ?>">
</form>
</div>
</div>
</div>
<?
}
?>
