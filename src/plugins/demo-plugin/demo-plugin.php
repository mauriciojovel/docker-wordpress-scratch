<?php
/*
Plugin Name: Site Plugin for example.com
Description: Site specific code changes for example.com
*/
 
 /**
  *=========================================================
  * Creating a function to create our CPT
  *=========================================================
  */
  
 function custom_post_type() {
  
 // Set UI labels for Custom Post Type
     $labels = array(
         'name'                => _x( 'Movies', 'Post Type General Name', 'demo-plugin' ),
         'singular_name'       => _x( 'Movie', 'Post Type Singular Name', 'demo-plugin' ),
         'menu_name'           => __( 'Movies', 'demo-plugin' ),
         'parent_item_colon'   => __( 'Parent Movie', 'demo-plugin' ),
         'all_items'           => __( 'All Movies', 'demo-plugin' ),
         'view_item'           => __( 'View Movie', 'demo-plugin' ),
         'add_new_item'        => __( 'Add New Movie', 'demo-plugin' ),
         'add_new'             => __( 'Add New', 'demo-plugin' ),
         'edit_item'           => __( 'Edit Movie', 'demo-plugin' ),
         'update_item'         => __( 'Update Movie', 'demo-plugin' ),
         'search_items'        => __( 'Search Movie', 'demo-plugin' ),
         'not_found'           => __( 'Not Found', 'demo-plugin' ),
         'not_found_in_trash'  => __( 'Not found in Trash', 'demo-plugin' ),
     );
      
 // Set other options for Custom Post Type
      
     $args = array(
         'label'               => __( 'movies', 'demo-plugin' ),
         'description'         => __( 'Movie news and reviews', 'demo-plugin' ),
         'labels'              => $labels,
         // Features this CPT supports in Post Editor
         'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
         // You can associate this CPT with a taxonomy or custom taxonomy. 
         'taxonomies'          => array( 'genres' ),
         /* A hierarchical CPT is like Pages and can have
         * Parent and child items. A non-hierarchical CPT
         * is like Posts.
         */ 
         'hierarchical'        => false,
         'public'              => true,
         'show_ui'             => true,
         'show_in_menu'        => true,
         'show_in_nav_menus'   => true,
         'show_in_admin_bar'   => true,
         'menu_position'       => 5,
         'can_export'          => true,
         'has_archive'         => true,
         'exclude_from_search' => false,
         'publicly_queryable'  => true,
         'menu_icon'           => 'dashicons-video-alt',
         'capability_type'     => 'movie',
         'capabilities' => array(
            'publish_posts' => 'publish_movies',
            'edit_posts' => 'edit_movies',
            'edit_others_posts' => 'edit_others_movies',
            'delete_posts' => 'delete_movies',
            'delete_others_posts' => 'delete_others_movies',
            'read_private_posts' => 'read_private_movies',
            'edit_post' => 'edit_movie',
            'delete_post' => 'delete_movie',
            'read_post' => 'read_movie',
            'delete_published_posts' => 'delete_published_movies',
            'edit_published_posts' => 'edit_published_movies'
          ),
         'map_meta_cap'        => true,
     );
      
     // Registering your Custom Post Type
     register_post_type( 'movies', $args );
  
 }
  
 /* Hook into the 'init' action so that the function
 * Containing our post type registration is not 
 * unnecessarily executed. 
 */
  
 add_action( 'init', 'custom_post_type', 0 );

/**
 *=========================================================
 * create a custom taxonomy name it topics for your posts
 *=========================================================
 */
function create_topics_hierarchical_taxonomy() {
  
  // Add new taxonomy, make it hierarchical like categories
  //first do the translations part for GUI
  
   $labels = array(
     'name' => _x( 'Genres', 'taxonomy general name' ),
     'singular_name' => _x( 'Genre', 'taxonomy singular name' ),
     'search_items' =>  __( 'Search genre', 'demo-plugin' ),
     'all_items' => __( 'All genres', 'demo-plugin' ),
     'parent_item' => __( 'Parent genre', 'demo-plugin' ),
     'parent_item_colon' => __( 'Parent genre:', 'demo-plugin' ),
     'edit_item' => __( 'Edit genre', 'demo-plugin' ), 
     'update_item' => __( 'Update genre', 'demo-plugin' ),
     'add_new_item' => __( 'Add New genre', 'demo-plugin' ),
     'new_item_name' => __( 'New genres Name', 'demo-plugin' ),
     'menu_name' => __( 'Genres', 'demo-plugin' ),
   );    
  
  // Now register the taxonomy
  
   register_taxonomy('genres',array('movies'), array(
     'hierarchical' => true,
     'labels' => $labels,
     'show_ui' => true,
     'show_admin_column' => true,
     'query_var' => true,
     'rewrite' => array( 'slug' => 'genres' ),
     'capabilities' => array (
        'manage_terms' => 'manage_movies', //by default only admin
        'edit_terms' => 'manage_movies',
        'delete_terms' => 'manage_movies',
        'assign_terms' => 'edit_movies'  // edit_posts means administrator', 'editor', 'author', 'contributor'
      )
   ));
  
  }

 //hook into the init action and call create_book_taxonomies when it fires
add_action( 'init', 'create_topics_hierarchical_taxonomy', 0 );

/**
 *=========================================================
 * Create a custom Role
 *=========================================================
 */

function add_roles_on_plugin_activation() {
    add_role( 'movie_creator', __(
        'Movie Creator', 'demo-plugin' ),
        array(
        'read' => true, // true allows this capability
        'edit_posts' => false, // Allows user to edit their own posts
        'edit_pages' => false, // Allows user to edit pages
        'edit_others_posts' => false, // Allows user to edit others posts not just their own
        'create_posts' => false, // Allows user to create new posts
        'manage_categories' => false, // Allows user to manage post categories
        'publish_posts' => false, // Allows the user to publish, otherwise posts stays in draft mode
        'edit_themes' => false, // false denies this capability. User can’t edit your theme
        'install_plugins' => false, // User cant add new plugins
        'update_plugin' => false, // User can’t update any plugins
        'update_core' => false, // user cant perform core updates
        'upload_files'=>true
        )
    );

    // Add the roles you'd like to administer the custom post types
    $roles = array('movie_creator','editor','administrator');

    // Loop through each role and assign capabilities
    foreach($roles as $the_role) { 
        $role = get_role($the_role);
        $role->add_cap( 'publish_movies' );
        $role->add_cap( 'edit_movies' );

        $role->add_cap( 'read_movies');
        $role->add_cap( 'read_private_movies' );
        $role->add_cap('delete_movies');

        $role->add_cap('edit_movie');
        $role->add_cap('delete_movie');
        $role->add_cap('read_movie');

        // The movie creator only can edit/delete their posts
        if($the_role != 'movie_creator') {
            $role->add_cap('edit_others_movies');
            $role->add_cap( 'delete_others_movies' );
            $role->add_cap( 'delete_published_movies' );
            $role->add_cap( 'manage_movies' );
        }
        
        $role->add_cap( 'edit_published_movies' );
        $role->add_cap( 'delete_private_movies' );
    }
}

register_activation_hook( __FILE__, 'add_roles_on_plugin_activation' );

function delete_role() {
    $roles = array('movie_creator','editor','administrator');
    
    // Loop through each role and assign capabilities
    foreach($roles as $the_role) { 
        $role = get_role($the_role);

        $role->remove_cap( 'publish_movies' );
        $role->remove_cap( 'edit_movies' );

        $role->remove_cap( 'read_movies');
        $role->remove_cap( 'read_private_movies' );
        $role->remove_cap('delete_movies');

        $role->remove_cap('edit_movie');
        $role->remove_cap('delete_movie');
        $role->remove_cap('read_movie');

        // The movie creator only can edit/delete their posts
        if($the_role != 'movie_creator') {
            
            $role->remove_cap('edit_others_movies');
            $role->remove_cap( 'delete_others_movies' );
            $role->remove_cap( 'delete_published_movies' );
            $role->remove_cap( 'manage_movies' );
        }

        $role->remove_cap( 'edit_published_movies' );
        $role->remove_cap( 'delete_private_movies' );
    }

    remove_role('movie_creator');
}

register_deactivation_hook(__FILE__, 'delete_role');

// Show only posts and media related to logged in author
add_action('pre_get_posts', 'query_set_only_author' );
function query_set_only_author( $wp_query ) {
    global $current_user;
    if( is_admin() && !current_user_can('edit_others_movies') ) {
        $wp_query->set( 'author', $current_user->ID );
        add_filter('views_edit-movies', 'fix_post_counts');
        add_filter('views_upload', 'fix_media_counts');
    }
}

// Fix post counts
function fix_post_counts($views) {
    global $current_user, $wp_query;
    unset($views['mine']);
    $types = array(
        array( 'status' =>  NULL ),
        array( 'status' => 'publish' ),
        array( 'status' => 'draft' ),
        array( 'status' => 'pending' ),
        array( 'status' => 'trash' )
    );
    foreach( $types as $type ) {
        $query = array(
            'author'      => $current_user->ID,
            'post_type'   => 'movies',
            'post_status' => $type['status']
        );
        $result = new WP_Query($query);
        if( $type['status'] == NULL ):
            $class = ($wp_query->query_vars['post_status'] == NULL) ? ' class="current"' : '';
            $views['all'] = sprintf(
            '<a href="%1$s"%2$s>%4$s <span class="count">(%3$d)</span></a>',
            admin_url('edit.php?post_type=movies'),
            $class,
            $result->found_posts,
            __('All', 'demo-plugin')
        );
        elseif( $type['status'] == 'publish' ):
            $class = ($wp_query->query_vars['post_status'] == 'publish') ? ' class="current"' : '';
            $views['publish'] = sprintf(
            '<a href="%1$s"%2$s>%4$s <span class="count">(%3$d)</span></a>',
            admin_url('edit.php?post_type=movies&post_status='.$type['status']),
            $class,
            $result->found_posts,
            __('Publish', 'demo-plugin')
        );
        elseif( $type['status'] == 'draft' ):
            $class = ($wp_query->query_vars['post_status'] == 'draft') ? ' class="current"' : '';
            $views['draft'] = sprintf(
            '<a href="%1$s"%2$s>%4$s <span class="count">(%3$d)</span></a>',
            admin_url('edit.php?post_type=movies&post_status='.$type['status']),
            $class,
            $result->found_posts,
            __('Draft', 'demo-plugin')
        );
        elseif( $type['status'] == 'pending' ):
            $class = ($wp_query->query_vars['post_status'] == 'pending') ? ' class="current"' : '';
            $views['pending'] = sprintf(
            '<a href="%1$s"%2$s>%4$s <span class="count">(%3$d)</span></a>',
            admin_url('edit.php?post_type=movies&post_status='.$type['status']),
            $class,
            $result->found_posts,
            __('Pending', 'demo-plugin')
        );
        elseif( $type['status'] == 'trash' ):
            $class = ($wp_query->query_vars['post_status'] == 'trash') ? ' class="current"' : '';
            $views['trash'] = sprintf(
            '<a href="%1$s"%2$s>%4$s <span class="count">(%3$d)</span></a>',
            admin_url('edit.php?post_type=movies&post_status='.$type['status']),
            $class,
            $result->found_posts,
            __('Trash', 'demo-plugin')
        );
        endif;
    }
    return $views;
}

// Fix media counts
function fix_media_counts($views) {
    global $wpdb, $current_user, $post_mime_types, $avail_post_mime_types;
    $views = array();
    $count = $wpdb->get_results( "
        SELECT post_mime_type, COUNT( * ) AS num_posts 
        FROM $wpdb->posts 
        WHERE post_type = 'attachment' 
        AND post_author = $current_user->ID 
        AND post_status != 'trash' 
        GROUP BY post_mime_type
    ", ARRAY_A );
    foreach( $count as $row )
        $_num_posts[$row['post_mime_type']] = $row['num_posts'];
    $_total_posts = array_sum($_num_posts);
    $detached = isset( $_REQUEST['detached'] ) || isset( $_REQUEST['find_detached'] );
    if ( !isset( $total_orphans ) )
        $total_orphans = $wpdb->get_var("
            SELECT COUNT( * ) 
            FROM $wpdb->posts 
            WHERE post_type = 'attachment'
            AND post_author = $current_user->ID 
            AND post_status != 'trash' 
            AND post_parent < 1
        ");
    $matches = wp_match_mime_types(array_keys($post_mime_types), array_keys($_num_posts));
    foreach ( $matches as $type => $reals )
        foreach ( $reals as $real )
            $num_posts[$type] = ( isset( $num_posts[$type] ) ) ? $num_posts[$type] + $_num_posts[$real] : $_num_posts[$real];
    $class = ( empty($_GET['post_mime_type']) && !$detached && !isset($_GET['status']) ) ? ' class="current"' : '';
    $views['all'] = "<a href='upload.php'$class>" . sprintf( __('All <span class="count">(%s)</span>', 'uploaded files' ), number_format_i18n( $_total_posts )) . '</a>';
    foreach ( $post_mime_types as $mime_type => $label ) {
        $class = '';
        if ( !wp_match_mime_types($mime_type, $avail_post_mime_types) )
            continue;
        if ( !empty($_GET['post_mime_type']) && wp_match_mime_types($mime_type, $_GET['post_mime_type']) )
            $class = ' class="current"';
        if ( !empty( $num_posts[$mime_type] ) )
            $views[$mime_type] = "<a href='upload.php?post_mime_type=$mime_type'$class>" . sprintf( translate_nooped_plural( $label[2], $num_posts[$mime_type] ), $num_posts[$mime_type] ) . '</a>';
    }
    $views['detached'] = '<a href="upload.php?detached=1"' . ( $detached ? ' class="current"' : '' ) . '>' . sprintf( __( 'Unattached <span class="count">(%s)</span>', 'detached files' ), $total_orphans ) . '</a>';
    return $views;
}
 
/* Stop Adding Functions Below this Line */
?>