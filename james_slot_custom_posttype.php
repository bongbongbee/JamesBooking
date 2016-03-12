<?php

/*
initing the post type in wordpress
to add in additional fields like
how many tables
what is the cost
booked by who

 */

function jregister_post_type()
{
    $name   = "slot";
    $labels = array(
        'name'               => _x('Slots', 'post type general name', 'your-plugin-textdomain'),
        'singular_name'      => _x('Slot', 'post type singular name', 'your-plugin-textdomain'),
        'menu_name'          => _x('Slots', 'admin menu', 'your-plugin-textdomain'),
        'name_admin_bar'     => _x('Slot', 'add new on admin bar', 'your-plugin-textdomain'),
        'add_new'            => _x('Add New', 'slot', 'your-plugin-textdomain'),
        'add_new_item'       => __('Add New Slot', 'your-plugin-textdomain'),
        'new_item'           => __('New Slot', 'your-plugin-textdomain'),
        'edit_item'          => __('Edit Slot', 'your-plugin-textdomain'),
        'view_item'          => __('View Slot', 'your-plugin-textdomain'),
        'all_items'          => __('All Slots', 'your-plugin-textdomain'),
        'search_items'       => __('Search Slots', 'your-plugin-textdomain'),
        'parent_item_colon'  => __('Parent Slots:', 'your-plugin-textdomain'),
        'not_found'          => __('No Slots found.', 'your-plugin-textdomain'),
        'not_found_in_trash' => __('No Slots found in Trash.', 'your-plugin-textdomain'),
    );
    $args = array(
        'labels'             => $labels,
        'description'        => __('Description.', 'your-plugin-textdomain'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'Slot'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'supports'           => array('author', 'editor', 'custom-fields'),
    );

    register_post_type($name, $args);
}

add_action('init', 'jregister_post_type');
