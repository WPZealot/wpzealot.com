<?php //=======================================================================
// Setup our CPT's
//==========================================================================


//###########################################################################
// Products
//###########################################################################
add_action('init', 'cpt_products');
function cpt_products(){
	register_post_type('products', array(
		'label'			=> __('Products'),
		'labels'		=> array(
			'singular_name' 	=> __('Product'),
			'all_items'			=> __('All Products'),
			'add_new'			=> __('Add New'),
			'add_new_item'		=> __('Add New Product'),
			'edit_item'			=> __('Edit Product'),
			'new_item'			=> __('New Product'),
			'view_item'			=> __('View Product'),
			'search_items'		=> __('Search Products'),
			'not_found'			=> __('No Products found'),
			'not_found_in_trash'=> __('No Products found in trash'),
			'parent_item_colon'	=> __('Parent Product')
		),
		'public'		=> true,
		'menu_position'	=> 20.058278,
		'menu_icon'		=> get_template_directory_uri() . '/img/icons/products.png',
		'capabilities' => array(
			'publish_posts' => 'manage_options',
			'edit_posts' => 'manage_options',
			'edit_others_posts' => 'manage_options',
			'delete_posts' => 'manage_options',
			'delete_others_posts' => 'manage_options',
			'read_private_posts' => 'manage_options',
			'edit_post' => 'manage_options',
			'delete_post' => 'manage_options',
			'read_post' => 'manage_options',
	    ),
		'hierarchical'	=> false,
		'supports'		=> array('title', 'revisions', 'editor')
	));
}
//=============================================================================
// Products Metabox
//=============================================================================
add_filter('cmb_meta_boxes', 'metabox_products');
function metabox_products($mb){
	$pre = '_product_';
	$mb[] = array(
		'id'		=> 'product-properties',
		'title'		=> 'Product Properties',
		'pages'		=> array('products'),
		'context'	=> 'normal',
		'priority'	=> 'high',
		'show_names'=> true,
		'fields'	=> array(
		)
	);
	$mb[] = array(
		'id'		=> 'product-thumbnail-preview',
		'title'		=> 'Thumbnail Preview',
		'pages'		=> array('products'),
		'context'	=> 'side',
		'priority'	=> 'default',
		'show_names'=> false,
		'fields'	=> array(
			array(
				'name'	=> __('Sprite Offset'),
				'desc'	=> __('Set the offset (get\'s multiplied by 100), used to display the image with hover effect'),
				'id'	=> $pre . 'position',
				'type'	=> 'text'
			)			
		)
	);

	return $mb;
}
//=============================================================================
// Page specific scripts
//=============================================================================
add_action('admin_enqueue_scripts', 'scripts_products');
function scripts_products($hook){
	if(_GET('post_type', 'products')){
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Echo the Styles
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		?>
			<style>
				#product-thumbnail-preview-image, #product-thumbnail-preview-image div{
					width: 100px; 
					height: 100px; 
					margin: auto; 
					background: url(<?php echo get_template_directory_uri();?>/img/sprites/products.png); 
					margin-bottom: 20px;
					background-position: 0 100px;
				}
				#product-thumbnail-preview-image div{background-position: 0 0px;}
			</style>
		<?php
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Update the Preview
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		wp_enqueue_script('script_products', get_template_directory_uri() . '/js/admin/products.js');
	}
}