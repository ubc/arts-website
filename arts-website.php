<?php

/*
  Plugin Name: UBC Arts Website
  Plugin URI:
  Description: Transforms the UBC Collab Theme into an Arts website | Note: This plugin will only work on wp-hybrid-clf theme
  Version: 1
  Author: Amir Entezaralmahdi | Arts ISIT
  Licence: GPLv2
  Author URI: http://isit.arts.ubc.ca
 */

Class UBC_Arts_Theme_Options {

    static $prefix;

    /**
     * init function.
     * 
     * @access public
     * @return void
     */
    function init() {

        self::$prefix = 'wp-hybrid-clf'; // function hybrid_get_prefix() is not available within the plugin
        
        // include Arts specific css file
        wp_register_style('arts-theme-option-style', plugins_url('arts-website') . '/css/style.css');
        // include Arts specific javascript file
        wp_register_script('arts-theme-option-script', plugins_url('arts-website') . '/js/script.js');

        add_action('ubc_collab_theme_options_ui', array(__CLASS__, 'arts_ui'));
        
        add_action( 'admin_init',array(__CLASS__, 'admin' ) );
        
        add_filter( 'ubc_collab_default_theme_options', array(__CLASS__, 'default_values'), 10,1 );
        add_filter( 'ubc_collab_theme_options_validate', array(__CLASS__, 'validate'), 10, 2 );
      	
      
        //arts specifics:
        // this needs to happen way later
        // remove_action(self::$prefix.'_header', array( 'UBC_Collab_Navigation','header_menu'), 12 );
        // try something like this instead
        add_action('after_setup_theme', array(__CLASS__, 'remove_navigation',10 );
        //add_action( self::$prefix.'_header', array(__CLASS__, 'arts_header_menu'), 12 );
        
    }
    
    function remove_navigation(){
    	remove_action(self::$prefix.'_header', array( 'UBC_Collab_Navigation','header_menu'), 12 );
    	
    }
    
    /*
     * This function includes the css and js for this specifc admin option
     *
     * @access public
     * @return void
     */
     function arts_ui(){
        wp_enqueue_style('arts-theme-option-style');
        wp_enqueue_script('arts-theme-option-script', array('jquery'));
     }
     
    /**
     * admin function.
     * 
     * @access public
     * @return void
     */
    function admin(){
        
        //Add Arts Options tab in the theme options
        add_settings_section(
                'arts-options', // Unique identifier for the settings section
                'Arts options', // Section title
                '__return_false', // Section callback (we don't want anything)
                'theme_options' // Menu slug, used to uniquely identify the page; see ubc_collab_theme_options_add_page()
        );

        //Add Colour options
        add_settings_field(
                'arts-colours', // Unique identifier for the field for this section
                'Colour Options', // Setting field label
                array(__CLASS__,'arts_colour_options'), // Function that renders the settings field
                'theme_options', // Menu slug, used to uniquely identify the page; see ubc_collab_theme_options_add_page()
                'arts-options' // Settings section. Same as the first argument in the add_settings_section() above
        );
         //Add Why-Unit options
        add_settings_field(
                'arts-why-unit', // Unique identifier for the field for this section
                'Why Unit?', // Setting field label
                array(__CLASS__,'arts_why_unit_options'), // Function that renders the settings field
                'theme_options', // Menu slug, used to uniquely identify the page; see ubc_collab_theme_options_add_page()
                'arts-options' // Settings section. Same as the first argument in the add_settings_section() above
        );       
        
        //Add Why-Unit options
        add_settings_field(
                'arts-apply-now', // Unique identifier for the field for this section
                'Apply Now', // Setting field label
                array(__CLASS__,'arts_apply_now_options'), // Function that renders the settings field
                'theme_options', // Menu slug, used to uniquely identify the page; see ubc_collab_theme_options_add_page()
                'arts-options' // Settings section. Same as the first argument in the add_settings_section() above
        );
   
        //Add Hardcoded list
        add_settings_field(
                'arts-hardcoded-options', // Unique identifier for the field for this section
                'Hardcoded Options', // Setting field label
                array(__CLASS__,'arts_hardcoded_options'), // Function that renders the settings field
                'theme_options', // Menu slug, used to uniquely identify the page; see ubc_collab_theme_options_add_page()
                'arts-options' // Settings section. Same as the first argument in the add_settings_section() above
        );        
    }     
    
    /**
     * arts_colour_options.
     * Display colour options for Arts specific template
     * @access public
     * @return void
     */   
    function arts_colour_options(){ ?>
        
    
		<div class="explanation"><a href="#" class="explanation-help">Info</a>
			
			<div> TODO: some explanation...</div>
		</div>
		<div id="arts-unit-colour-box">
			<label><b>Unit/Website Main Colour:</b></label>
			<div class="arts-colour-item"><span>(A) Main colour: </span><?php  UBC_Collab_Theme_Options::text( 'arts-a-colour' ); ?></div><br/>
                        <div class="arts-colour-item"><span>(B) Gradient colour: </span><?php  UBC_Collab_Theme_Options::text( 'arts-b-colour' ); ?></div><br/>
                        <div class="arts-colour-item"><span>(C) Hover colour: </span><?php  UBC_Collab_Theme_Options::text( 'arts-c-colour' ); ?></div><br/>
                        <div class="arts-colour-item"><span>(D) Reverse colour: </span></div>
                        <ul>                        
                        <?php	
                            foreach ( UBC_Arts_Theme_Options::arts_reverse_colour() as $option ) {
                                ?>
                                <li class="layout">
                                <?php UBC_Collab_Theme_Options::radio( 'arts-d-colour', $option['value'], $option['label']); ?>
                                </li>
                      <?php } ?>
                        </ul>
		</div>   <?php     
    }
    
    /**
     * arts_why_unit_options.
     * Display Why-Unit options for Arts specific template
     * @access public
     * @return void
     */      
    function arts_why_unit_options(){ ?>
            <div class="explanation"><a href="#" class="explanation-help">Info</a>

                    <div> TODO: some explanation...</div>
            </div>
            <div id="arts-why-unit-box">
                <label><b>Why Unit/Website Options:</b></label>
                <div><?php UBC_Collab_Theme_Options::checkbox( 'arts-enable-why-unit', 1, 'Enable Why-Unit bar' ); ?></div>
                <div class="half arts-why-inputs"><?php UBC_Collab_Theme_Options::text('arts-why-unit-text', 'Label text'); ?></div>
                <div class="half arts-why-inputs"><?php UBC_Collab_Theme_Options::text('arts-why-unit-url', 'URL'); ?></div>
            </div>
        
    <?php
    }
    
    /**
     * arts_apply_now_options.
     * Display Apply Now options for Arts specific template
     * @access public
     * @return void
     */      
    function arts_apply_now_options(){ ?>
            <div class="explanation"><a href="#" class="explanation-help">Info</a>

                    <div> TODO: some explanation...</div>
            </div>
            <div id="arts-apply-now-box">
                <label><b>Apply Now Options:</b></label>
                <div><?php UBC_Collab_Theme_Options::checkbox( 'arts-enable-apply-now', 1, 'Enable Apply-now botton' ); ?></div>
                <div class="half arts-apply-inputs"><?php UBC_Collab_Theme_Options::text('arts-apply-now-text', 'Botton text'); ?></div>
                <div class="half arts-apply-inputs"><?php UBC_Collab_Theme_Options::text('arts-apply-now-url', 'URL'); ?></div>
            </div>
        
    <?php
    }
    
    /**
     * arts_apply_now_options.
     * Display Apply Now options for Arts specific template
     * @access public
     * @return void
     */      
    function arts_hardcoded_options(){ ?>
            <div class="explanation"><a href="#" class="explanation-help">Info</a>

                    <div> TODO: some explanation...</div>
            </div>
            <div id="arts-hardcoded-box">
                <label><b>The following options are hardcoded:</b></label>
                <ol>
                    <li>Unit/Website Bar Background Colour: #6D6E70</li>
                    <li>Add Arts logo in the menu</li>
                </ol>
            </div>
        
    <?php
    UBC_Arts_Theme_Options::arts_defaults();
    }    
    
    function arts_defaults(){
        UBC_Collab_Theme_Options::update('clf-unit-colour', '#6D6E70');
        //wp_nav_menu( array( 'before' => '111111' ) );
    }
    
    
    function arts_header_menu(){
		?>
		<!-- UBC Unit Navigation -->
        <div id="ubc7-unit-menu" class="navbar expand" role="navigation">
            <div class="navbar-inner expand">
                <div class="container"><a href="#"><img src="http://anth.sites.olt.ubc.ca/files/2013/02/ArtsSquare1.jpg"/></a>
                 <?php wp_nav_menu( array('theme_location' => 'primary', 'walker' => new Bootstrap_Walker_Nav_Menu(), 'container_class' => 'nav-collapse collapse', 'container_id'=> 'ubc7-unit-navigation' , 'fallback_cb' => array(__CLASS__, 'pages_nav'), 'menu_class' => 'nav') ); ?>
                    
                </div>
            </div><!-- /navbar-inner -->
        </div><!-- /navbar -->
        <!-- End of UBC Unit Navigation -->
		<?php        
    }
    /*********** 
     * Default Options
     * 
     * Returns the options array for arts.
     *
     * @since ubc-clf 1.0
     */
    function default_values( $options ) {

            if (!is_array($options)) { 
                    $options = array();
            }

            $defaults = array(
                'arts-a-colour'		=> '#5E869F',
                'arts-b-colour'		=> '#71a1bf',
                'arts-c-colour'		=> '#002145',
                'arts-d-colour'		=> 'w',
                'arts-enable-why-unit'  => true,
                'arts-why-unit-text'    => 'Why Unit/Department?',
                'arts-why-unit-url'     => '#',
                'arts-enable-apply-now' => true,
                'arts-apply-now-text'   => 'Apply Now',
                'arts-apply-now-url'    => '#',

            );

            $options = array_merge( $options, $defaults );

            return $options;
    }  
	/**
	 * Sanitize and validate form input. Accepts an array, return a sanitized array.
	 *
	 *
	 * @todo set up Reset Options action
	 *
	 * @param array $input Unknown values.
	 * @return array Sanitized theme options ready to be stored in the database.
	 *
	 */
	function validate( $output, $input ) {
		
		// Grab default values as base
		$starter = UBC_Arts_Theme_Options::default_values( array() );
		

	    // Validate Unit Colour Options A, B, and C
            $starter['arts-a-colour'] = UBC_Collab_Theme_Options::validate_text($input['arts-a-colour'], $starter['arts-a-colour'] );
            $starter['arts-b-colour'] = UBC_Collab_Theme_Options::validate_text($input['arts-b-colour'], $starter['arts-b-colour'] );
            $starter['arts-c-colour'] = UBC_Collab_Theme_Options::validate_text($input['arts-c-colour'], $starter['arts-c-colour'] );
            
            // Validate Unit Colour Options D
            if ( isset( $input['arts-d-colour'] ) && array_key_exists( $input['arts-d-colour'], UBC_Arts_Theme_Options::arts_reverse_colour() ) ) {
	        $starter['arts-d-colour'] = $input['arts-d-colour'];
	    }
            
            //Validate Why-unit options
            $starter['arts-enable-why-unit'] = (bool)$input['arts-enable-why-unit'];
            $starter['arts-why-unit-text']   = UBC_Collab_Theme_Options::validate_text($input['arts-why-unit-text'], $starter['arts-why-unit-text'] );
            $starter['arts-why-unit-url']     = UBC_Collab_Theme_Options::validate_text($input['arts-why-unit-url'], $starter['arts-why-unit-url'] );
 
            //Validate Why-unit options
            $starter['arts-enable-apply-now'] = (bool)$input['arts-enable-apply-now'];
            $starter['arts-apply-now-text']   = UBC_Collab_Theme_Options::validate_text($input['arts-apply-now-text'], $starter['arts-apply-now-text'] );
            $starter['arts-apply-now-url']     = UBC_Collab_Theme_Options::validate_text($input['arts-apply-now-url'], $starter['arts-apply-now-url'] );
            
            $output = array_merge($output, $starter);

            return $output;            
        }
        
        
    	/**
	 * Returns and array of reverse colours
	 */
	function arts_reverse_colour() {
		$reverse_colour = array(
	        'w' => array(
	            'value' => 'w',
	            'label' => __( 'White', 'arts-clf' )
	        ),
	        'b' => array(
	            'value' => 'b',
	            'label' => __( 'Black', 'arts-clf' )
	        )
	    );
	   return $reverse_colour;
	}

}

UBC_Arts_Theme_Options::init();

//var_dump( get_option( 'ubc-collab-theme-options' ));
