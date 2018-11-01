<?php namespace Pub;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.rto.de
 * @since      1.0.0
 *
 * @package    DynamicConditions
 * @subpackage DynamicConditions/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    DynamicConditions
 * @subpackage DynamicConditions/public
 * @author     RTO GmbH <kundenhomepage@rto.de>
 */
class DynamicConditionsPublic {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $pluginName The ID of this plugin.
     */
    private $pluginName;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $pluginName The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct( $pluginName, $version ) {

        $this->pluginName = $pluginName;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueueStyles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in DynamicConditionsLoader as all of the hooks are defined
         * in that particular class.
         *
         * The DynamicConditionsLoader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        //wp_enqueue_style( $this->pluginName, plugin_dir_url( __FILE__ ) . 'css/dynamic-conditions-public.css', array(), $this->version, 'all' );

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueueScripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in DynamicConditionsLoader as all of the hooks are defined
         * in that particular class.
         *
         * The DynamicConditionsLoader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        //wp_enqueue_script( $this->pluginName, plugin_dir_url( __FILE__ ) . 'js/dynamic-conditions-public.js', array( 'jquery' ), $this->version, false );

    }

    /**
     * Stopp rendering of widget if its hidden
     *
     * @param $content
     * @param $widget
     * @return string
     */
    public function hookRenderContent( $content, $widget ) {
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
            return $content;
        }

        $settings = $widget->get_settings_for_display();
        $controls = $widget->get_controls();

        if ( empty( $settings['dynamicconditions_condition'] ) ) {
            // no condition selected - disable conditions
            return $content;
        }

        /*$dynamic_settings = array_merge( $control_obj->get_settings( 'dynamic' ), $control['dynamic'] );

        if ( ! empty( $dynamic_settings['active'] ) && ! empty( $all_settings[ Manager::DYNAMIC_SETTING_KEY ][ $control_name ] ) ) {
            $parsed_value = $control_obj->parse_tags( $all_settings[ Manager::DYNAMIC_SETTING_KEY ][ $control_name ], $dynamic_settings );*/

        $checkValue = $settings['dynamicconditions_value'];
        $widgetValueArray = $settings['dynamiccondtions_dynamic'];

        if ( !is_array( $widgetValueArray ) ) {
            $widgetValueArray = [ $widgetValueArray ];
        }

        var_dump( $checkValue );
        var_dump( $widgetValueArray );
        //var_dump($controls);

        $condition = false;
        foreach ( $widgetValueArray as $widgetValue ) {
            switch ( $settings['dynamicconditions_condition'] ) {
                case 'equal':
                    $condition = $checkValue == $widgetValue;
                    break;

                case 'not_equal':
                    $condition = $checkValue != $widgetValue;
                    break;

                case 'contains':
                    $condition = strpos( $widgetValue, $checkValue ) !== false;
                    break;

                case 'not_contains':
                    $condition = strpos( $widgetValue, $checkValue ) === false;
                    break;

                case 'empty':
                    $condition = empty( $widgetValue );
                    break;

                case 'not_empty':
                    $condition = !empty( $widgetValue );
                    break;
            }

            if ( $condition ) {
                break;
            }
        }

        $hide = false;

        switch ( $settings['dynamicconditions_visibility'] ) {
            case 'show':
                if ( !$condition ) {
                    $hide = true;
                }
                break;
            case 'hide':
            default:
                if ( $condition ) {
                    $hide = true;
                }
                break;
        }

        if ( $hide ) {
            return '<!-- hidden widget -->';
        }

        return $content;
    }
}