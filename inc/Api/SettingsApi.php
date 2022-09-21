<?php
/**
 * @package MiltonPlugin
 */
namespace SettingsApi\Inc\Api;

use SettingsApi\Inc\Api\Callbacks\CallbackText;
use SettingsApi\Inc\Api\Callbacks\CallbackMultiCheck;

class SettingsApi {

    public $admin_pages = array();

    public $admin_subpages = array();

    protected $settings_sections = array();

    protected $settings_fields = array();

    public function register() {

        if ( !empty( $this->admin_pages ) || !empty($this->admin_subpages) ) {
            add_action( 'admin_menu', array( $this, 'addAdminMenu' ) );
        }

        if(!empty($this->settings_sections)){
            add_action('admin_init', array($this,'registerCustomField'));
        }
    }

    public function addPages( array $pages ) {
        $this->admin_pages = $pages;
        return $this;
    }

    public function addSubPages( array $pages ) {
        $this->admin_subpages = array_merge( $this->admin_subpages, $pages );
        return $this;
    }

    public function withSubPage( string $title = null ) {

        if ( empty( $this->admin_pages ) ) {
            return;
        }
        $admin_page = $this->admin_pages[0];
        $subpages = array(
            array(
                'parent_slug' => $admin_page['menu_slug'],
                'page_title'  => $admin_page['page_title'],
                'menu_title'  => ( $title ) ? $title : $admin_page['menu_title'],
                'capability'  => $admin_page['capability'],
                'menu_slug'   => $admin_page['menu_slug'],
                'callback'    => $admin_page['callback'],
            ),
        );
        $this->admin_subpages = $subpages;

        return $this;
    }

    function set_sections( $sections ) {
        $this->settings_sections = $sections;

        return $this;
    }
    function set_fields( $settings_fields ) {
        $this->settings_fields = $settings_fields;

        return $this;
    }

    public function addAdminMenu() {

        foreach ( $this->admin_pages as $page ) {
            add_menu_page( $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'], $page['icon_url'], $page['position'] );
        }

        foreach ( $this->admin_subpages as $page ) {
            add_submenu_page( $page['parent_slug'], $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'] );
        }

    }

    public function registerCustomField(){

        //register settings sections

        foreach($this->settings_sections as $section){
            if(false===get_option($section['id'])){
                add_option($section['id']);
            }
            if ( isset($section['desc']) && !empty($section['desc']) ) {
                $section['desc'] = '<div class="inside">' . $section['desc'] . '</div>';
                $callback = function() use ( $section ) {
                        echo str_replace( '"', '\"', $section['desc'] );
		            };
            } else if ( isset( $section['callback'] ) ) {
                $callback = $section['callback'];
            } else {
                $callback = null;
            }
            add_settings_section( $section['id'], $section['title'], $callback, $section['id'] );
        }

        //register settings fields

        foreach ( $this->settings_fields as $section => $field ) {
            foreach ( $field as $option ) {
               
                $name = $option['name'];
                $type = isset( $option['type'] ) ? $option['type'] : 'text';
                $label = isset( $option['label'] ) ? $option['label'] : '';

                switch ($type) {
                    case 'text':
                        $callback = isset( $option['callback'] ) ? $option['callback'] : array( CallbackText::class, 'callback_text');
                        break;
                    case 'multicheck':
                        $callback = isset( $option['callback'] ) ? $option['callback'] : array( CallbackMultiCheck::class, 'callback_multicheck');
                        break;
                    default:
                        $callback = isset( $option['callback'] ) ? $option['callback'] : array( CallbackText::class, 'callback_text');
                        break;
                }
                $args = array(
                    'id'                => $name,
                    'class'             => isset( $option['class'] ) ? $option['class'] : $name,
                    'label_for'         => "{$section}[{$name}]",
                    'desc'              => isset( $option['desc'] ) ? $option['desc'] : '',
                    'name'              => $label,
                    'section'           => $section,
                    'size'              => isset( $option['size'] ) ? $option['size'] : null,
                    'options'           => isset( $option['options'] ) ? $option['options'] : '',
                    'std'               => isset( $option['default'] ) ? $option['default'] : '',
                    'sanitize_callback' => isset( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : '',
                    'type'              => $type,
                    'placeholder'       => isset( $option['placeholder'] ) ? $option['placeholder'] : '',
                    'min'               => isset( $option['min'] ) ? $option['min'] : '',
                    'max'               => isset( $option['max'] ) ? $option['max'] : '',
                    'step'              => isset( $option['step'] ) ? $option['step'] : '',
                );

                add_settings_field( "{$section}[{$name}]", $label, $callback, $section, $section, $args );
            }
        }

        // creates our settings in the options table
        foreach ( $this->settings_sections as $section ) {
            register_setting( $section['id'], $section['id'], array( $this, 'sanitize_options' ) );
        }
    }

    function sanitize_options( $options ) {

        if ( !$options ) {
            return $options;
        }
        foreach( $options as $option_slug => $option_value ) {
            $sanitize_callback = $this->get_sanitize_callback( $option_slug );

            // If callback is set, call it
            if ( $sanitize_callback ) {
                $options[ $option_slug ] = call_user_func( $sanitize_callback, $option_value );
                continue;
            }
        }

        return $options;
    }

    function get_sanitize_callback( $slug = '' ) {
        if ( empty( $slug ) ) {
            return false;
        }

        // Iterate over registered fields and see if we can find proper callback
        foreach( $this->settings_fields as $section => $options ) {
            foreach ( $options as $option ) {
                if ( $option['name'] != $slug ) {
                    continue;
                }

                // Return the callback name
                return isset( $option['sanitize_callback'] ) && is_callable( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : false;
            }
        }

        return false;
    }

    public static function get_option( $option, $section, $default = '' ) {

        $options = get_option( $section );

        if ( isset( $options[$option] ) ) {
            return $options[$option];
        }

        return $default;
    }
    public static function get_field_description( $args ) {
        if ( ! empty( $args['desc'] ) ) {
            $desc = sprintf( '<p class="description">%s</p>', $args['desc'] );
        } else {
            $desc = '';
        }

        return $desc;
    }
    function show_navigation() {
        $html = '<h2 class="nav-tab-wrapper">';


        $count = count( $this->settings_sections );

        // don't show the navigation if only one section exists
        if ( $count === 1 ) {
            return;
        }

        foreach ( $this->settings_sections as $tab ) {
            $html .= sprintf( '<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['title'] );
        }

        $html .= '</h2>';

        echo $html;
    }
    function show_forms() {
        ?>
        <div class="metabox-holder">
            <?php foreach ( $this->settings_sections as $form ) { ?>
                <div id="<?php echo $form['id']; ?>" class="group" style="display: none;">
                    <form method="post" action="options.php">
                        <?php
                        do_action( 'wsa_form_top_' . $form['id'], $form );
                        settings_fields( $form['id'] );
                        do_settings_sections( $form['id'] );
                        do_action( 'wsa_form_bottom_' . $form['id'], $form );
                        if ( isset( $this->settings_fields[ $form['id'] ] ) ):
                        ?>
                        <div style="padding-left: 10px">
                            <?php submit_button(); ?>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>
            <?php } ?>
        </div>
        <?php
    }


}
