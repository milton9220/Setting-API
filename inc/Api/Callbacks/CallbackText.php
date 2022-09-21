<?php
/**
 * @package MiltonPlugin
 */
namespace SettingsApi\Inc\Api\Callbacks;

use SettingsApi\Inc\Api\SettingsApi;

class CallbackText{
    public static function callback_text($args){
        $value       = esc_attr( SettingsApi::get_option( $args['id'], $args['section'], $args['std'] ) );
        $size        = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
        $type        = isset( $args['type'] ) ? $args['type'] : 'text';
        $placeholder = empty( $args['placeholder'] ) ? '' : ' placeholder="' . $args['placeholder'] . '"';

        $html        = sprintf( '<input type="%1$s" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"%6$s/>', $type, $size, $args['section'], $args['id'], $value, $placeholder );
        $html       .= SettingsApi::get_field_description( $args );

        echo $html;
    }
}