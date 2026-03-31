<?php
/**
 * Custom menu walker for this theme
 * 
 * @package wanda
 */


if ( ! class_exists( 'Wanda_Details_Menu_Walker' ) ) {
	class Wanda_Details_Menu_Walker extends Walker_Nav_Menu {
		/**
		 * Open submenu level.
		 */
		public function start_lvl( &$output, $depth = 0, $args = null ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
			$indent = str_repeat( "\t", $depth );
			$output .= "\n$indent<ul class=\"sub-menu\" aria-label=\"" . esc_attr__( 'Sottomenu', 'wanda' ) . "\">\n";
		}

		/**
		 * Start menu element.
		 */
		public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
			$indent       = ( $depth ) ? str_repeat( "\t", $depth ) : '';
			$classes      = empty( $item->classes ) ? array() : (array) $item->classes;
			$has_children = in_array( 'menu-item-has-children', $classes, true );
			$class_names  = implode( ' ', array_map( 'sanitize_html_class', array_filter( $classes ) ) );

			$output .= $indent . '<li class="' . esc_attr( $class_names ) . '">';

			if ( $has_children ) {
				$output .= '<details><summary>' . esc_html( $item->title ) . '</summary>';
				return;
			}

			$atts           = array();
			$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
			$atts['target'] = ! empty( $item->target ) ? $item->target : '';
			$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
			$atts['href']   = ! empty( $item->url ) ? $item->url : '';

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$title   = apply_filters( 'the_title', $item->title, $item->ID );
			$title   = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
			$output .= '<a' . $attributes . '>' . esc_html( $title ) . '</a>';
		}

		/**
		 * End menu element.
		 */
		public function end_el( &$output, $item, $depth = 0, $args = null ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
			$classes      = empty( $item->classes ) ? array() : (array) $item->classes;
			$has_children = in_array( 'menu-item-has-children', $classes, true );

			if ( $has_children ) {
				$output .= '</details>';
			}

			$output .= "</li>\n";
		}
	}
}