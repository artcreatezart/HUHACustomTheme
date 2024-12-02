<?php
    add_theme_support('post-thumbnails');

    // Custom logo support
    add_theme_support('custom-logo');

    // CORS support
    function add_cors_http_header() {
        header("Access-Control-Allow-Origin: *");
    }
    add_action('init', 'add_cors_http_header');

    // Enque or Stylesheets 
    function enqueue_parent_and_custom_styles() {
        
        wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

        // custom style
        wp_enqueue_style('child-style', get_template_directory_uri() . '/custom.css', array('parent-style'));
    }
    add_action('wp_enqueue_scripts', 'enqueue_parent_and_custom_styles');

    function custom_excerpt_length($length) {
        return 50; 
    }

    add_filter('excerpt_length', 'custom_excerpt_length' , 999 );

    // Cutomiser settings:
    function custom_theme_customize_register( $wp_customize ) {
        
        // Background Color setting -----------
        $wp_customize->add_setting('background_color', array(
            'default' => '#F7F8FF', 
            'transport' => 'postMessage',
        ));

        // Background Color control:
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'background_color', array(
            'label' => __('Background Colour', 'custom-theme'),
            'section' => 'colors',
        )));

        // added Font Section -----------
        $wp_customize->add_section('fonts', array(
            'title' => __('Fonts', 'custom-theme'),
            'priority' => 50,
        ));

        // Header Font Setting
        $wp_customize->add_setting('header_font_family', array(
            'default' => 'Nunito',
            'transport' => 'postMessage',
        ));

        // Header Font Control
        $wp_customize->add_control('font_family_control', array(
            'label' => 'Headers Font Family',
            'section' => 'fonts',
            'settings' => 'header_font_family',
            'type' => 'select',
            'choices' => array(
                'Arial' => 'Arial',
                'Nunito' => 'Nunito',
                'Inter' => 'Inter',
                'Roboto' => 'Roboto',
                'Atma' => 'Atma',
                'DynaPuff' => 'DynaPuff',
                'McLaren' => 'McLaren'
            ),
        ));

        // larger section Bg Color setting -----------
        $wp_customize->add_setting('section_colors', array(
            'default' => '#171717',
            'transport' => 'postMessage',
        ));

        // larger section Bg Color control
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'section_colors', array (
            'label' => __('Sectioned Colour', 'custom-theme'),
            'section' => 'colors',
        )));

        // Primary Button Bg Color setting -----------
        $wp_customize->add_setting('primary_button_color', array(
            'default' => '#5FB1BF',
            'transport' => 'postMessage',
        ));

        // Primary Button Bg Color control
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_button_color', array (
            'label' => __('Primary Button Colour', 'custom-theme'),
            'section' => 'colors',
        )));

        // Secondary Button Bg Color setting -----------
        $wp_customize->add_setting('secondary_button_color', array(
            'default' => '#EA7B3B',
            'transport' => 'postMessage',
        ));

        // Secondary Button Bg Color control
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_button_color', array (
            'label' => __('Secondary Button Colour', 'custom-theme'),
            'section' => 'colors',
        )));

    }

    add_action('customize_register', 'custom_theme_customize_register');

    // Custom Rest API endpoint to retreive customiser settings
    function get_customizer_settings() {
        $settings = array(
            'backgroundColor' => get_theme_mod('background_color', '#F7F8FF'),
            'headerFontFamily' => get_theme_mod('header_font_family', 'Nunito'),
            'sectionedColor' => get_theme_mod('section_colors', '#171715'),
            'primaryButtonColor' => get_theme_mod('primary_button_color', '#5FB1BF'),
            'secondaryButtonColor' => get_theme_mod('secondary_button_color', '#EA7B3B'),
        );

        return rest_ensure_response($settings);
    }

    add_action('rest_api_init', function () {
        register_rest_route('custom-theme/v1', '/customizer-settings', array(
            'methods' => 'GET',
            'callback' => 'get_customizer_settings',
            'permission_callback' => '__return_true'
        ));
    });

    // navlogo from dashboard -----------
    function get_nav_logo() {
        $custom_logo_id = get_theme_mod('custom_logo');
        $logo = wp_get_attachment_image_src($custom_logo_id, 'full');

        return $logo;
    }

    add_action('rest_api_init', function () {
        register_rest_route('custom/v1', 'nav-logo', array(
            'methods' => 'GET',
            'callback' => 'get_nav_logo',
            'permission_callback' => '__return_true'
        ));
    });
?>