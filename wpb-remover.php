<?php
/**
  * WP Bakery Shortcode Remover
  * Script to remove WPBakery shortcode tags while retaining the content inside
  * The goal is to capture everything inside the shortcode tags and keep the 
  * text content while removing the shortcode itself.
  * 
  * Access this script through your browser by navigating to 
  * http://yourdomain.com/remove-shortcodes.php. 
  * It will execute the script and clean the content of all pages.
  * Important: After running the script, delete the file for security reasons.
  * 
  * By Mariano de Iriondo - mariano@mdi.com.ar
  */


require('wp-load.php'); // Ensure the path is correct  

// Define an array of post types to include; add more CPTs as necessary  
$post_types = array('page', 'recipe', 'blog'); // Replace with your custom post types  

// Define the query arguments to fetch all posts of the selected post types  
$args = array(  
    'post_type' => $post_types,  
    'posts_per_page' => -1,  
    'post_status' => array('publish', 'draft'), // Include drafts  
);  

$all_posts = get_posts($args); // Fetch posts based on the specified criteria  

foreach ($all_posts as $post) {  
    $content = $post->post_content;  

    // Remove WPBakery shortcode tags but keep the content inside  
    $content = preg_replace('/\[vc_row.*?\](.*?)\[\/vc_row\]/s', '$1', $content); // Keep content inside vc_row  
    $content = preg_replace('/\[vc_column.*?\](.*?)\[\/vc_column\]/s', '$1', $content); // Keep content inside vc_column  
    $content = preg_replace('/\[vc_column_text.*?\](.*?)\[\/vc_column_text\]/s', '$1', $content); // Keep content inside vc_column_text  

    // Uncomment for debugging  
    /*  
    echo "<br>TITLE:<br>";  
    echo $post->post_title;  
    echo "<br><br>";  
    echo $post->ID;  
    echo "<br>Content:<hr>";  
    echo $content;  
    */  

    // Update the post content if changes were made  
    if ($content !== $post->post_content) {  
        $updated_post = wp_update_post(array(  
            'ID' => $post->ID,  
            'post_content' => $content,  
        ));  

        // Check if the post was updated successfully  
        if (is_wp_error($updated_post)) {  
            error_log('Error updating post ID ' . $post->ID . ': ' . $updated_post->get_error_message());  
        }  
    }  
}  

echo "Shortcodes removed from all specified post types, preserving the content.";  
?>
