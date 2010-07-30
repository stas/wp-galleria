<?php
/*
Plugin Name: WordPress Galleria
Plugin URI: http://softwareliber.ro/poze/
Description: Create photo galleries by just uploading directories of photos. A <a href="http://softwareliber.ro/">GSL</a> project!
Version: 1.3
Author: Stas Sușcov
Author URI: http://stas.nerd.ro/
*/
?>
<?php
/*  Copyright 2009  Stas Sushkov (email : stas@nerd.ro)

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

/**
 * Admin cpanel
 */
function galleria_admin_menu() {
    add_management_page(__("WordPress Galleria","wp-galleria"), __("WordPress Galleria","wp-galleria"), 3, "wp-galleria", "galleria_admin");
}

/*
 * Admin stuff
 */
function galleria_admin()
{
    $galleria_fs = wp_upload_dir();
    $galleria_data_path = $galleria_fs["basedir"]."/wp-galleria/";
    ?>
    	<div id="icon-tools" class="icon32"><br /></div>
	<div class="wrap">
		<h2><?php echo __('WordPress Galleria','wp-galleria')?></h2>
		<div id="poststuff" class="metabox-holder">
			<div class="postbox">
				<h3 class="hndle" ><?php echo __('How to use it?','wp-galleria')?></h3>
				<div class="inside">
				    <p><?php echo __('Insert inside the content of a page or a post the shortcode: <code>[GALLERIA]</code>','wp-galleria');?></p>
				    <p><?php echo sprintf(__('Remmeber, the plugin will automaticaly generate all the galleries by fetching your direcotories with photos from <code>%1$s</code>.','wp-galleria'), $galleria_data_path);?></p>
				    <p><?php echo sprintf(__('You can upload photos by using an ftp client like <a href="%1$s">FileZilla</a>.','wp-galleria'),'http://filezilla-project.org/')?></p>
                                    <p><?php echo sprintf(__('WordPress Galleria would never look like now if not <a href="%1$s">jQuery FancyBox</a>. Great thanks to him!','wp-galleria'),'http://fancybox.net/');?></p>
				</div>
			</div>
                </div>
        </div>
    <?
}

/*
 * Content generation hook
 */
function galleria_content()
{   

    $galleria_fs = wp_upload_dir();
    $galleria_data_path = $galleria_fs["basedir"]."/wp-galleria/";
    if(!file_exists($galleria_data_path))
        mkdir($galleria_data_path);
    
    global $wp_query;
    $galleria_permalink = get_permalink($wp_query->post->ID);
    $galleria_get_str = "galleria";
    
    if(!isset($_GET[$galleria_get_str])) {
	$galleria_query_path = null;
	$galleria_path = $galleria_data_path;
    } else {
	$galleria_query_path = $_GET[$galleria_get_str];
	$galleria_path = $galleria_data_path . $galleria_query_path;
    }
    $galleria_web_loc = $galleria_fs["baseurl"]."/wp-galleria/".$galleria_query_path."/";
    $galleria_path_thumbs = $galleria_path."/thumbs";
    $galleria_img_w = 150;
    $galleria_img_h = 150;
    
    if($galleria_query_path != null) {
	$galleria_breadcrumbs = array_filter(explode("/", $galleria_query_path));
	$galleria_breadcrumbs = array_filter($galleria_breadcrumbs);
	
	$galleria_breadcrumbs_done = $galleria_breadcrumbs[0];
	echo "<h3><a href=\"$galleria_permalink\">" . __('Index', 'wp_galleria') . "</a>";
	foreach($galleria_breadcrumbs as $g_b) {
	    if($galleria_breadcrumbs_done != $g_b)
		$galleria_breadcrumbs_done = $galleria_breadcrumbs_done . "/" . $g_b;
	    echo " / <a href=\"".add_query_arg($galleria_get_str, $galleria_breadcrumbs_done, $galleria_permalink)."\">$g_b</a>";
	}
	echo "</h3>";
    }
    
    if (($files = @scandir($galleria_path)) && count($files) <= 2) {
	echo "<p>".__('No galleries/photos yet.', 'wp_galleria')."</p>";
    }
    else
    {
	$galleria_children = array();
	foreach($files as $file) {
	    if($file != '.' && $file != '..' && $file != 'thumbs' && is_dir($galleria_path."/".$file)) {
		if($galleria_query_path)
		    $galleria_filepath = $galleria_query_path."/".$file;
		else
		    $galleria_filepath = $file;
		$galleria_children[] = "\t"."<li class=\"galleria-list\">
					<a href=\"".add_query_arg($galleria_get_str, $galleria_filepath, $galleria_permalink)."\">
					$file</a></li>"."\n";
	    }
	    if($file != '.' && $file != '..' && $file != 'thumbs' && !is_dir($galleria_path."/".$file)) {
		$galleria_finfo = pathinfo($file);
		if(preg_match("/jpg|jpeg|JPEG|JPG|png|PNG/",$galleria_finfo["extension"])) {
		    if(!file_exists($galleria_path_thumbs."/".$file))
			galleria_createthumb(
				$galleria_path_thumbs,
				$galleria_path."/".$file,
				$galleria_path_thumbs."/".$file,
				$galleria_img_w,
				$galleria_img_h
				);
		    $galleria_imgs[] = "\t"."<li class=\"galleria-photo\">
					<a href=\"".$galleria_web_loc.$file."\" rel=\"".str_replace('_', ' ', $galleria_query_path)."\" >
					<img src=\"".$galleria_web_loc."thumbs/".$file."\" alt=\"".str_replace('_', ' ', $galleria_query_path)."\" />
					</a></li>"."\n";
		}
	    }
	}
    
	if(count($galleria_children) > 0 || count($galleria_imgs) > 0) {
	    echo "<ul id=\"wp-galleria\" class=\"wp-galleria\">"."\n";
	    if(count($galleria_children) > 0)
		foreach($galleria_children as $galleria_child) {
		    echo $galleria_child;
		}
	    if(count($galleria_imgs) > 0)
		foreach($galleria_imgs as $galleria_i) {
		    echo $galleria_i;
		}
	    echo "</ul>"."\n";
	}
    }
}

/*
 * Generate thumbnails from pics
 */
function galleria_createthumb($dir,$name,$filename,$new_w,$new_h)
{
    if(!file_exists($dir))
	mkdir($dir);
    
    $system=pathinfo($name);
    if (preg_match("/jpg|jpeg|JPEG|JPG/",$system["extension"])){$src_img=imagecreatefromjpeg($name);}
    if (preg_match("/png|PNG/",$system["extension"])){$src_img=imagecreatefrompng($name);}
    $old_x=imageSX($src_img);
    $old_y=imageSY($src_img);
    if ($old_x > $old_y) 
    {
	$thumb_w=$new_w;
	$thumb_h=$old_y*($new_h/$old_x);
    }
    if ($old_x < $old_y) 
    {
	$thumb_w=$old_x*($new_w/$old_y);
	$thumb_h=$new_h;
    }
    if ($old_x == $old_y) 
    {
	$thumb_w=$new_w;
	$thumb_h=$new_h;
    }
    $dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
    imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 
    if (preg_match("/png/",$system["extension"]))
    {
	imagepng($dst_img,$filename); 
    } else {
	imagejpeg($dst_img,$filename); 
    }
    imagedestroy($dst_img); 
    imagedestroy($src_img); 
}

/*
 * Hooks to load the stuff in header
 */

function galleria_header_styles()
{
    if (! is_admin())
    {
        wp_enqueue_style('galleria', '/wp-content/plugins/wp-galleria/css/wp-galleria.css',null,"1.1",'screen');
        wp_enqueue_style('fancybox', '/wp-content/plugins/wp-galleria/css/jquery.fancybox-1.3.0.css',null,"1.3.0",'screen');
    }
}

function galleria_header_scripts()
{
    wp_enqueue_script('jquery');
    if (! is_admin())
    {
        wp_enqueue_script('fancybox', '/wp-content/plugins/wp-galleria/js/jquery.fancybox-1.3.0.pack.js',array('jquery'),"1.3.0");
        wp_enqueue_script('fancybox-load', '/wp-content/plugins/wp-galleria/js/load.js',array('fancybox'),"1.1",true);
    }
}

// Filter paths!
if(validate_file($_GET['galleria']) > 0)
    wp_die('Ups!', 'WordPress Galleria Error');

//l10n stuff
$galleria_domain = 'wp-galleria';
$galleria_domain_path = PLUGINDIR.'/'.dirname(plugin_basename(__FILE__));
load_plugin_textdomain($galleria_domain,$galleria_domain_path.'/l10n' );

add_action('admin_menu', 'galleria_admin_menu');
add_action('wp_print_scripts', 'galleria_header_scripts');
add_action('wp_print_styles', 'galleria_header_styles');
add_shortcode('GALLERIA', 'galleria_content');
?>