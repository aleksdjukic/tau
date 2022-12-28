<?php
/*
Plugin Name: T-Shirt Logo Printing
Plugin URI: http://www.example.com/tshirt-logo-printing
Description: A plugin to allow customers to print logos on t-shirts.
Version: 1.0
Author: Aleksandar Đukić
Author URI: http://www.example.com
*/


function tshirt_logo_printing_customize_register($wp_customize)
{
    // Add a new section for t-shirt logo printing
    $wp_customize->add_section('tshirt_logo_printing', array(
        'title' => __('T-Shirt Logo Printing'),
        'priority' => 30,
    ));

    // Add a control for selecting the logo
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'tshirt_logo', array(
        'label' => __('Select Logo'),
        'section' => 'tshirt_logo_printing',
        'mime_type' => 'image',
    )));

    // Add controls for customizing the logo placement and size
    $wp_customize->add_control('tshirt_logo_x_position', array(
        'label' => __('Logo X Position'),
        'section' => 'tshirt_logo_printing',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 0,
            'max' => 100,
            'step' => 1,
        ),
    ));
    $wp_customize->add_control('tshirt_logo_y_position', array(
        'label' => __('Logo Y Position'),
        'section' => 'tshirt_logo_printing',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 0,
            'max' => 100,
            'step' => 1,
        ),
    ));
}

add_action('customize_register', 'tshirt_logo_printing_customize_register');


function tshirt_logo_printing_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'size' => 'medium',
    ), $atts);

    $logo_url = get_theme_mod('tshirt_logo');
    $x_position = get_theme_mod('tshirt_logo_x_position');
    $y_position = get_theme_mod('tshirt_logo_y_position');
    $size = $atts['size'];

    if (!$logo_url) {
        return '';
    }

    $html = '<div class="tshirt-logo" style="background-image: url(' . esc_url($logo_url) . '); background-position: ' . intval($x_position) . '% ' . intval($y_position) . '%; background-size: ' . esc_attr($size) . ';"></div>';

    return $html;
}

add_shortcode('tshirt_logo', 'tshirt_logo_printing_shortcode');
