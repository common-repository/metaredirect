<?php
class metaredirect {

    /**
     * Initializes actions and filters
     */
    function __construct() {
    	add_action( 'admin_menu', [$this, 'create_plugin_settings_page'] );
    	add_action( 'admin_init', [$this, 'setup_sections'] );
    	add_action( 'admin_init', [$this, 'setup_fields'] );
        add_action( 'admin_init', [$this, 'metaredirect_scripts'] );
        add_filter('query_vars', [$this, 'metaredirect_queryvariable'] );
    }

    /**
     * Sets up the admin page
     */
    function create_plugin_settings_page() {
    	$page_title = 'MetaRedirect Settings Page';
    	$menu_title = 'MetaRedirect';
    	$capability = 'manage_options';
    	$slug = 'metaredirect_settings';
    	$callback = [$this, 'plugin_settings_page_content'];
    	$icon = 'dashicons-admin-plugins';
    	$position = 300;

    	add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
    }

    /**
     * Basic page setup
     */
     function plugin_settings_page_content() {?>
     	<div class="wrap">
    		<h2>MetaRedirect Settings</h2><?php
            if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ){
                  self::admin_notice();
            } ?>
    		<form method="POST" action="options.php">
                <?php
                    settings_fields( 'metaredirect_fields' );
                    do_settings_sections( 'metaredirect_fields' );
                    submit_button();
                ?>
    		</form>
    	</div> <?php
    }
    
    /**
     * Notification setup
     */
    function admin_notice() { ?>
        <div class="notice notice-success is-dismissible">
            <p>Your settings have been updated!</p>
        </div><?php
    }

    /**
     * Add page sections
     */
    function setup_sections() {
        add_settings_section( 'main_section', 'General Settings', [$this, 'section_callback'], 'metaredirect_fields' );
    }

    /**
     * Page section router
     * @param array $arguments
     */
    function section_callback( $arguments ) {
    	switch( $arguments['id'] ){
            case 'main_section':
                echo 'Configure the redirection behavior here. Please refer to the plugin page for more information.';
                break;
            }
    }

    /**
     * Setup the fields
     */
    function setup_fields() {
        $fields = [
                [
                    'uid' => 'metaredirect_enabled',
                    'label' => 'Enable',
                    'section' => 'main_section',
                    'type' => 'checkbox',
                    'options' => ['enabled'=>'Enabled'],
                    'default' => [],
                    'supplemental' => 'Uncheck the box to disable the plugin.'
                ],
                [
                    'uid' => 'metaredirect_type',
                    'label' => 'Redirection type',
                    'section' => 'main_section',
                    'type' => 'radio',
                    'options' => [
                        '302' => 'Temporary (302)',
                        '301' => 'Permanent (301)'
                        ],
                    'supplemental' => '[required] Select the type of redirection action. Temporary redirections can be reversed, while permanent ones cannot.',
                    'default' => []
        	],
        	[
                    'uid' => 'metaredirect_customfield',
                    'label' => 'Custom Field Source',
                    'section' => 'main_section',
                    'type' => 'text',
                    'supplemental' => '[required] Provide the name of the field that holds the target URL.',
        	],
        	[
                    'uid' => 'metaredirect_trigger',
                    'label' => 'Trigger',
                    'section' => 'main_section',
                    'type' => 'radio',
                    'options' => [
                        'permanent' => 'Permanent',
                        'provisional' => 'Provisional'
                        ],
                    'supplemental' => '[required] Select <b>Permanent</b> to always perform the redirection whenever the custom field is present.<br>Select <b>Provisional</b> to only trigger the redirection if the the query parameter provided below is found in the URL.',
                    'default' => []
        	],
        	[
                    'uid' => 'metaredirect_trigger_argument',
                    'label' => 'Triggering URL parameter',
                    'section' => 'main_section',
                    'type' => 'text',
                    'supplemental' => 'Provide the query parameter that will trigger the redirection. Example: jump, as in https://mysite.com/post?jump',
        	],
        	[
                    'uid' => 'metaredirect_query_attachment',
                    'label' => 'Query string attachment',
                    'section' => 'main_section',
                    'type' => 'text',
                    'supplemental' => '[optional] If needed, provide the key/value pair parameter(s) that will be appended to every target URL. Example: source=mydomain.com&date=december',
        	]
                
        ];
    	foreach( $fields as $field ){
            add_settings_field( $field['uid'], $field['label'], [$this, 'field_callback'], 'metaredirect_fields', $field['section'], $field );
            register_setting( 'metaredirect_fields', $field['uid'] );
    	}
    }

    /**
     * Field templates
     * @param array $arguments An array of properties to build fields from
     */
    function field_callback( $arguments ) {

        $value = get_option( $arguments['uid'] );

        if( ! $value ) {
            $value = $arguments['default'];
        }

        switch( $arguments['type'] ){
            case 'text':
            case 'password':
            case 'number':
                printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
                break;
            case 'textarea':
                printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['uid'], $arguments['placeholder'], $value );
                break;
            case 'select':
            case 'multiselect':
                if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
                    $attributes = '';
                    $options_markup = '';
                    foreach( $arguments['options'] as $key => $label ){
                        $options_markup .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $value[ array_search( $key, $value, true ) ], $key, false ), $label );
                    }
                    if( $arguments['type'] === 'multiselect' ){
                        $attributes = ' multiple="multiple" ';
                    }
                    printf( '<select name="%1$s[]" id="%1$s" %2$s>%3$s</select>', $arguments['uid'], $attributes, $options_markup );
                }
                break;
            case 'radio':
            case 'checkbox':
                if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
                    $options_markup = '';
                    $iterator = 0;
                    foreach( $arguments['options'] as $key => $label ){
                        $iterator++;
                        $options_markup .= sprintf( '<label for="%1$s_%6$s"><input id="%1$s_%6$s" name="%1$s[]" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>', $arguments['uid'], $arguments['type'], $key, checked( $value[ array_search( $key, $value, true ) ], $key, false ), $label, $iterator );
                    }
                    printf( '<fieldset>%s</fieldset>', $options_markup );
                }
                break;
        }

        if( $helper = $arguments['helper'] ){
            printf( '<span class="helper"> %s</span>', $helper );
        }

        if( $supplemental = $arguments['supplemental'] ){
            printf( '<p class="description">%s</p>', $supplemental );
        }

    }
    
    /**
     * Returns the value of the query string argument set as a provisional trigger
     * @param array $vars
     * @return array 
     */
    function metaredirect_queryvariable($vars) {
        $vars[] = get_option('metaredirect_trigger_argument');
        return $vars;         
    }
    
    /**
     * Script injector
     */
    function metaredirect_scripts() {
        //wp_register_script( 'metaredirect-admin',  plugins_url('js/metaredirect.admin.js', __FILE__ ),filemtime(plugin_dir_path(__FILE__) .'js/metaredirect.admin.js'), true);
        wp_register_script( 'metaredirect-admin',  plugins_url('js/metaredirect.admin.js', __FILE__ ),"1.0", true);
        wp_enqueue_script( 'metaredirect-admin');
    }

}

