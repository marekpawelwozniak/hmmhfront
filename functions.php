<?php

add_action('acf/init', 'my_acf_init_block_types');
function my_acf_init_block_types() {

    // Check function exists.
    if( function_exists('acf_register_block_type') ) {

        // register a testimonial block.
        acf_register_block_type(array(
            'name'				=> 'case_study',
            'title'				=> __('Case studies'),
            'description'		=> __('A custom case studies block.'),
            'render_template'	=> 'blocks/case_study.php',
            'category'			=> 'formatting',
            'icon'				=> 'admin-comments',
            'keywords'			=> array( 'case-study', 'quote' ),
        ));
    }
}

function new_enqueue_style() {
    wp_enqueue_style( 'bootstrap-css', '//stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' );
    wp_enqueue_style( 'new-style', get_template_directory_uri() . '/css/' . 'index.css', false );
}
add_action( 'wp_enqueue_scripts', 'new_enqueue_style' );


function add_js() {
    wp_enqueue_script('new-js', get_template_directory_uri() . '/js/scripts.js', array('jquery'), time(), true);
}

add_action( 'wp_enqueue_scripts', 'add_js' );

function create_posttype() {

    register_post_type( 'casestudy',

        [
            'labels' => [
                'name' => __( 'Case studies' ),
                'singular_name' => __( 'Case study' )
            ],
            'supports'            => ['title', 'custom-fields'],
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'hierarchical'        => false,
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'case-studies'),
            'show_in_rest' => true,

        ]
    );
}

add_action( 'init', 'create_posttype' );

function case_study_taxonomy() {

    $labels = array(
        'name' => 'Case studies category',
        'singular_name' => 'Case studies category',
        'search_items' => 'Case studies',
        'all_items' => 'All case studies',
        'edit_item' => 'Edit case study',
        'update_item' => 'Update case study',
        'add_new_item' => 'Add New case stude',
        'new_item_name' => 'New case study name',
        'menu_name' => 'Case studies category',
    );

    register_taxonomy('casestudy',['casestudy'], [
        'hierarchical' => false,
        'labels' => $labels,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'show_in_nav_menus', true,
        'rewrite' => ['slug' => 'casestudy'],
    ]);

}
//add_action("init", "lolo");
//
//function lolo(){
//    die(var_dump(get_the_terms(24, "casestudy")));
//    //die("run");
//}


add_action( 'init', 'case_study_taxonomy', 0 );

function get_casestudies(){

    $type = $_POST['type'] ? $_POST['type'] : 'all';

    $args = [
        'post_type'      => 'casestudy',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'ASC'
        ];

    if($type != "all"){
        $args['tax_query'] = [[
            'taxonomy' => 'casestudy',
            'field' => 'slug',
            'terms' => $type,

        ]];
    }

    $query = new WP_Query($args);

    $response = [];

    $i = 0;


    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $tags = "";
            $query->the_post();

            $response[$i]['name'] = get_the_title();
            $response[$i]['title'] = get_field('title');
            $response[$i]['image'] = get_field('image');
            $response[$i]['link'] = get_field('link');
            $id = get_the_ID();
            $tag_arr = get_the_terms($id, "casestudy");
            foreach ($tag_arr as $tag){
                $tags .= $tag->name . ' / ';
            }
            $tags = rtrim($tags, " / ");
            $response[$i]['tag'] = $tags;
            $i++;
        }
    }

    wp_die(json_encode($response));
}

add_action('wp_ajax_nopriv_get_casestudies', 'get_casestudies');
add_action('wp_ajax_get_casestudies', 'get_casestudies');
