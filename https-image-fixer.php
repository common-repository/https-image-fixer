<?php
/*
Plugin Name: HTTPS Image Fixer
Plugin URI: http://www.studiopress.com/plugins/https-image-fixer

Description: Fixes insecure content messages that appear when images load on an SSL secured website.

Author: Mitch Bartlett
Author URI: https://mitchbartlett.com

Version: 1.0.2

Text Domain: https-image-fixer
Domain Path: /languages

License: GNU General Public License v3.0 (or later)
License URI: http://www.gnu.org/licenses/gpl-3.0.html

*/

/*
    "HTTPS Image Fixer" Copyright (C) 2018 Mitch Bartlett  (email : mitch.bartlett@gmail.com)

    This file is part of HTTPS Image Fixer for WordPress.

    HTTPS Image Fixer is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    HTTPS Image Fixer is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see http://www.gnu.org/licenses/gpl-3.0.html
*/


add_action( 'admin_menu', 'https_image_fix_menu' );

function https_image_fix_menu() {
	add_options_page( 'HTTPS Image Fixer Options', 'HTTPS Image Fixer', 'manage_options', 'https-image-fixer', 'https_image_fix_options' );
}

function https_image_fix_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	echo '<h1>HTTPS Image Fixer</h1>';

	echo '<p>            <form method="post">';
		wp_nonce_field( 'runsql', 'name_of_nonce_field' );
	echo '
	
                <p class="submit">
                    <input type="submit" name="runsql" class="button-primary"
                           value="Fix Images"/>
                </p>
                
            </form>
        </div></p>';
	echo '</div>';


// if this fails, check_admin_referer() will automatically print a "failed" page and die.
if ( ! empty( $_POST ) && check_admin_referer( 'runsql', 'name_of_nonce_field' ) ) {
   // process form data

    global $wpdb;
    $wpdb->query("UPDATE $wpdb->posts
SET    post_content = ( Replace (post_content, 'src=\"http://', 'src=\"//') )
WHERE  Instr(post_content, 'jpeg') > 0 
        OR Instr(post_content, 'jpg') > 0 
        OR Instr(post_content, 'gif') > 0 
        OR Instr(post_content, 'png') > 0;");


    echo '<script language="javascript">';
echo 'alert("Images Fixed!")';
echo '</script>';
}


}




?>