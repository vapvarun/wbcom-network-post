<?php
/**
 * Network Posts Utility Functions
 *
 * Collection of utility functions for the Network Posts plugin.
 * PHP 8.3+ compatible.
 *
 * @package NetworkPosts
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if array contains a specific value.
 *
 * @param mixed $needle Value to search for.
 * @param array $array  Array to search in.
 * @return bool True if found, false otherwise.
 */
function array_has_value( $needle, $array ) {
	foreach ( $array as $value ) {
		if ( trim( (string) $needle ) === trim( (string) $value ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Multi-dimensional array sort.
 *
 * FIXED: Removed eval() for PHP 8.3 compatibility and security.
 *
 * @param array $array Array to sort.
 * @param array $cols  Columns to sort by.
 * @return array Sorted array.
 */
function array_msort( $array, $cols ) {
	if ( empty( $array ) || empty( $cols ) ) {
		return $array;
	}

	$colarr = array();

	// Build column arrays for sorting
	foreach ( $cols as $col => $order ) {
		$colarr[ $col ] = array();
		foreach ( $array as $k => $row ) {
			if ( isset( $row[ $col ] ) ) {
				$colarr[ $col ][ '_' . $k ] = is_string( $row[ $col ] ) ? strtolower( $row[ $col ] ) : $row[ $col ];
			}
		}
	}

	// Build arguments for array_multisort dynamically
	$sort_params = array();
	foreach ( $cols as $col => $order ) {
		$sort_params[] = &$colarr[ $col ];
		$sort_params[] = constant( $order ); // SORT_ASC or SORT_DESC
	}

	// Call array_multisort with dynamic parameters
	if ( ! empty( $sort_params ) ) {
		array_multisort( ...$sort_params );
	}

	// Rebuild the sorted array
	$ret = array();
	foreach ( $colarr as $col => $arr ) {
		foreach ( $arr as $k => $v ) {
			$k = substr( $k, 1 );
			if ( ! isset( $ret[ $k ] ) ) {
				$ret[ $k ] = $array[ $k ];
			}
			if ( isset( $array[ $k ][ $col ] ) ) {
				$ret[ $k ][ $col ] = $array[ $k ][ $col ];
			}
		}
	}

	return $ret;
}

/**
 * Custom sort comparison function.
 *
 * @param array $a First array element.
 * @param array $b Second array element.
 * @return int Comparison result.
 */
function custom_sort( $a, $b ) {
	if ( array_key_exists( 'post_date', $a ) && array_key_exists( 'post_date', $b ) ) {
		return $a['post_date'] < $b['post_date'] ? -1 : 1;
	} elseif ( array_key_exists( 'post_date', $a ) ) {
		return -1;
	} elseif ( array_key_exists( 'post_date', $b ) ) {
		return 1;
	}
	return 0;
}

/**
 * Format SQL exclusion clause.
 *
 * @param string       $table_name  Table name.
 * @param string       $column_name Column name.
 * @param array|string $values      Values to exclude.
 * @return string SQL clause.
 */
function format_exclusion( $table_name, $column_name, $values ) {
	if ( is_array( $values ) && ! empty( $values ) ) {
		$values = array_map(
			function ( $item ) use ( $column_name ) {
				if ( is_array( $item ) && isset( $item[ $column_name ] ) ) {
					return "'" . esc_sql( $item[ $column_name ] ) . "'";
				}
				return "'" . esc_sql( $item ) . "'";
			},
			$values
		);
		return "($table_name.$column_name NOT IN (" . implode( ',', $values ) . '))';
	}
	return '';
}

/**
 * Format SQL inclusion clause.
 *
 * @param string       $table_name  Table name.
 * @param string       $column_name Column name.
 * @param array|string $values      Values to include.
 * @param string       $union       Union type (OR/AND).
 * @return string SQL clause.
 */
function format_inclusion( $table_name, $column_name, $values, $union = 'OR' ) {
	if ( is_array( $values ) ) {
		if ( count( $values ) > 0 ) {
			$values = array_map(
				function ( $item ) use ( $column_name ) {
					if ( is_array( $item ) && isset( $item[ $column_name ] ) ) {
						return "'" . esc_sql( $item[ $column_name ] ) . "'";
					}
					return "'" . esc_sql( $item ) . "'";
				},
				$values
			);

			if ( $union === 'OR' ) {
				return "($table_name.$column_name IN (" . implode( ',', $values ) . '))';
			} else {
				$sql = array();
				foreach ( $values as $value ) {
					$sql[] = "$table_name.$column_name = $value";
				}
				return implode( ' AND ', $sql );
			}
		} else {
			return "({$table_name}.{$column_name} = '')";
		}
	}
	return '';
}

/**
 * Get excerpt by letter count.
 *
 * @param int    $length    Character limit.
 * @param string $content   Content to excerpt.
 * @param string $permalink Post permalink.
 * @return string Excerpt.
 */
function get_letters_excerpt( $length, $content, $permalink ) {
	if ( ! $length ) {
		return $content;
	}

	$content = substr( $content, 0, intval( $length ) );
	$words   = explode( ' ', $content );
	array_pop( $words );
	$content = implode( ' ', $words );

	return $content . '...';
}

/**
 * Get excerpt by word count.
 *
 * @param int    $length    Word limit.
 * @param string $content   Content to excerpt.
 * @param string $permalink Post permalink.
 * @return string Excerpt.
 */
function get_words_excerpt( $length, $content, $permalink ) {
	if ( ! $length ) {
		return $content;
	}

	$words = explode( ' ', $content );
	if ( count( $words ) > $length ) {
		$words   = array_slice( $words, 0, $length );
		$content = implode( ' ', $words );
		return $content . '... <a href="' . esc_url( $permalink ) . '">   ' . __( '', 'trans-nlp' ) . '</a>';
	}

	$content = implode( ' ', $words );
	return $content . ' <a href="' . esc_url( $permalink ) . '">   ' . __( '', 'trans-nlp' ) . '</a>';
}

/**
 * Strip text to specific word count.
 *
 * @param string $text       Text to strip.
 * @param int    $word_count Word limit.
 * @return string Stripped text.
 */
function netsposts_strip_text_words( string $text, int $word_count ): string {
	$words = explode( ' ', $text );
	if ( count( $words ) > $word_count ) {
		$words         = array_slice( $words, 0, $word_count );
		$stripped_text = implode( ' ', $words ) . '...';
		return $stripped_text;
	}
	return $text;
}

/**
 * Remove array element by key and value.
 *
 * @param array  $array Array to modify (by reference).
 * @param string $key   Key to check.
 * @param mixed  $value Value to match.
 */
function removeElementWithValue( &$array, $key, $value ) {
	foreach ( $array as $subKey => $subArray ) {
		if ( isset( $subArray[ $key ] ) && $subArray[ $key ] == $value ) {
			unset( $array[ $subKey ] );
		}
	}
}

/**
 * Sanitize quotes in string.
 *
 * @param string $str String to sanitize.
 * @return string Sanitized string.
 */
function sanitize_quotes( $str ) {
	return str_replace( '&#039;', '"', (string) $str );
}

/**
 * Shorten text to specific character limit.
 *
 * @param string $text  Text to shorten.
 * @param int    $limit Character limit.
 * @return string Shortened text.
 */
function ShortenText( $text, $limit ) {
	$chars_limit = $limit;
	$chars_text  = strlen( $text );
	$text        = $text . ' ';
	$text        = substr( $text, 0, $chars_limit );

	$nearest_space_position = strrpos( $text, ' ' );
	if ( $nearest_space_position !== false ) {
		$text = substr( $text, 0, $nearest_space_position );
	}

	if ( $chars_text > $chars_limit ) {
		$text = $text . '...';
	}

	return $text;
}

/**
 * Shorten text to exact length.
 *
 * @param string $text  Text to shorten.
 * @param int    $limit Character limit.
 * @return string Shortened text.
 */
function shorten_text_exact( $text, $limit ) {
	return mb_substr( (string) $text, 0, $limit );
}

/**
 * Get unique array elements by key.
 *
 * @param array  $array Array to process.
 * @param string $key   Key to check for uniqueness.
 * @return array Unique array.
 */
function super_unique( $array, $key ) {
	$temp_array = array();
	foreach ( $array as $v ) {
		if ( isset( $v[ $key ] ) && ! isset( $temp_array[ $v[ $key ] ] ) ) {
			$temp_array[ $v[ $key ] ] = $v;
		}
	}
	return array_values( $temp_array );
}

/**
 * Convert string to DateTime object.
 *
 * @param string|null $str Date string or 'now'.
 * @return DateTime|null DateTime object with time set to 00:00:00.
 */
function netsposts_strtodate( $str ) {
	try {
		if ( $str ) {
			$filter_date = new DateTime( $str );
			$filter_date->setTime( 0, 0, 0 );
		} else {
			$filter_date = new DateTime();
			$filter_date->setTime( 0, 0, 0 );
		}
		return $filter_date;
	} catch ( Exception $e ) {
		return null;
	}
}

/**
 * Filter array by date fields.
 *
 * @param array       $src_array       Source array.
 * @param array|null  $show_after_date  Filter after date.
 * @param array|null  $show_before_date Filter before date.
 * @param string|null $show_for_today   Filter for today.
 * @return array Filtered array.
 */
function netsposts_filter_by_date( array $src_array, ?array $show_after_date = null, ?array $show_before_date = null, $show_for_today = null ) {
	$current_date = netsposts_strtodate( 'now' );

	return array_filter(
		$src_array,
		function ( $post ) use ( $show_after_date, $show_before_date, $show_for_today, $current_date ) {
			try {
				$result = false;

				if ( $show_after_date && isset( $post[ $show_after_date[0] ] ) ) {
					$post_after_date = netsposts_strtodate( $post[ $show_after_date[0] ] );
					if ( $post_after_date ) {
						$result = $result || $post_after_date->getTimestamp() >= $show_after_date[1]->getTimestamp();
					}
				}

				if ( $show_before_date && isset( $post[ $show_before_date[0] ] ) ) {
					$post_before_date = netsposts_strtodate( $post[ $show_before_date[0] ] );
					if ( $post_before_date ) {
						$result = $result || $post_before_date->getTimestamp() < $show_before_date[1]->getTimestamp();
					}
				}

				if ( $show_for_today && isset( $post[ $show_for_today ] ) ) {
					$post_today_date = new DateTime( $post[ $show_for_today ] );
					if ( $current_date ) {
						$result = $result || $post_today_date->getTimestamp() === $current_date->getTimestamp();
					}
				}

				return $result;
			} catch ( Exception $e ) {
				return false;
			}
		}
	);
}

/**
 * Create label from ID.
 *
 * @param string $id ID to convert.
 * @return string Label.
 */
function netsposts_create_label_from_id( $id ) {
	$fullname = str_replace( '_', ' ', (string) $id );
	return strtoupper( mb_substr( $fullname, 0, 1 ) ) . mb_substr( $fullname, 1 );
}

/**
 * Modify pagination URLs.
 *
 * @param string $route_url  Route URL.
 * @param string $pagination Pagination HTML.
 * @param int    $page       Current page.
 * @return string Modified pagination.
 */
function netsposts_modify_pagination( $route_url, $pagination, $page ) {
	$escaped_route_url = str_replace( '/', '\/', $route_url );
	$pagination        = str_replace( "'", '"', $pagination );
	$pattern           = '/\b' . $escaped_route_url . '.*?\/([0-9]+)\/"/m';
	$new_pagination    = preg_replace( $pattern, $route_url . '$1"', $pagination );
	return $new_pagination;
}

/**
 * Filter empty values from array.
 *
 * @param array $data Array to filter.
 * @return array Filtered array.
 */
function netsposts_filter_empty_values( array $data ): array {
	return array_filter(
		$data,
		function ( $item ) {
			return ! empty( $item );
		}
	);
}

/**
 * Get post custom taxonomies.
 *
 * @param int $post_id Post ID.
 * @return WP_Term[] Array of terms.
 */
function netsposts_get_post_custom_taxonomies( int $post_id ): array {
	if ( function_exists( 'cptui_get_taxonomy_slugs' ) ) {
		$custom_taxonomies = cptui_get_taxonomy_slugs();
		$terms             = wp_get_post_terms( $post_id, $custom_taxonomies );

		if ( is_array( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as &$term ) {
				$term->url = get_term_link( $term );
			}
			return $terms;
		}
	}
	return array();
}

/**
 * Get post taxonomies by type.
 *
 * @param int   $post_id Post ID.
 * @param array $types   Taxonomy types.
 * @return array Array of terms.
 */
function netsposts_get_post_taxonomies( int $post_id, array $types ): array {
	$terms = wp_get_post_terms( $post_id, $types );

	if ( is_array( $terms ) && ! is_wp_error( $terms ) ) {
		foreach ( $terms as &$term ) {
			$term->url = get_term_link( $term );
		}
		return $terms;
	}
	return array();
}

/**
 * Remove pagination from text.
 *
 * @param string|null $text Text to process.
 * @return string Text without pagination.
 */
function netsposts_remove_pagination( ?string $text ): string {
	if ( is_null( $text ) || $text === '' ) {
		return '';
	}
	$re = '/\<div\sclass\=\"elementor-pagination\"\>.*?\<\/div\>/s';
	return preg_replace( $re, '', $text ) ?? '';
}

/**
 * Get ACF local fields for a post.
 *
 * @param int $post_id Post ID.
 * @return array Array of fields.
 */
function netsposts_get_local_fields( int $post_id ): array {
	global $acf;

	if ( ! isset( $acf ) ) {
		return array();
	}

	do_action( 'acf/include_fields' );

	$local_fields = array();

	if ( isset( $acf->local ) && isset( $acf->local->fields ) ) {
		$local_fields = $acf->local->fields;
	} elseif ( function_exists( 'acf_get_local_fields' ) ) {
		$local_fields = acf_get_local_fields();
	}

	if ( $local_fields && is_array( $local_fields ) ) {
		$output_fields = array();
		foreach ( $local_fields as $field ) {
			if ( isset( $field['name'] ) ) {
				$value                           = get_post_meta( $post_id, $field['name'], true );
				$field['value']                  = acf_format_value( $value, $post_id, $field );
				$output_fields[ $field['name'] ] = $field;
			}
		}
		return $output_fields;
	}

	return array();
}

/**
 * Replace image domains in HTML.
 *
 * @param string $html        HTML content.
 * @param string $needle      Domain to replace.
 * @param string $replacement Replacement domain.
 * @return string Modified HTML.
 */
function netsposts_replace_image_domains( string $html, string $needle, string $replacement ): string {
	$needle_escaped = str_replace( '/', '\/', $needle );

	// Replace in src attributes
	$pattern = '/img.*?src=[\'"](' . $needle_escaped . '.*?)[\'"]/';
	$matches = array();
	if ( preg_match_all( $pattern, $html, $matches ) ) {
		foreach ( $matches[0] as $match ) {
			$replaced = str_replace( $needle, $replacement, $match );
			$html     = str_replace( $match, $replaced, $html );
		}
	}

	// Replace in srcset attributes
	$pattern = '/img.*?srcset=[\'"](' . $needle_escaped . '.*?)[\'"]/';
	$matches = array();
	if ( preg_match_all( $pattern, $html, $matches ) ) {
		foreach ( $matches[0] as $match ) {
			$replaced = str_replace( $needle, $replacement, $match );
			$html     = str_replace( $match, $replaced, $html );
		}
	}

	return $html;
}

/**
 * Replace image link domains in HTML.
 *
 * @param string $html        HTML content.
 * @param string $needle      Domain to replace.
 * @param string $replacement Replacement domain.
 * @return string Modified HTML.
 */
function netsposts_replace_image_link_domains( string $html, string $needle, string $replacement ): string {
	$needle_escaped = str_replace( '/', '\/', $needle );
	$needle_escaped = str_replace( '.', '\.', $needle_escaped );

	$pattern = '/a.*?href\=[\'"](' . $needle_escaped . '.*?)[\'"].*?\>\s*\<img/';
	$matches = array();
	if ( preg_match_all( $pattern, $html, $matches ) ) {
		foreach ( $matches[0] as $match ) {
			$replaced = str_replace( $needle, $replacement, $match );
			$html     = str_replace( $match, $replaced, $html );
		}
	}

	return $html;
}

/**
 * Display variable for debugging.
 *
 * @param mixed $var Variable to display.
 */
function display_var( $var ): void {
	if ( isset( $GLOBALS['NETSPOSTS_DEBUG'] ) &&
		 $GLOBALS['NETSPOSTS_DEBUG'] &&
		 ! is_admin() ) {
		var_dump( $var );
	}
}

/**
 * Display string for debugging.
 *
 * @param mixed $str String to display.
 */
function display_string( $str ): void {
	if ( isset( $GLOBALS['NETSPOSTS_DEBUG'] ) &&
		 $GLOBALS['NETSPOSTS_DEBUG'] &&
		 ! is_admin() ) {
		var_dump( $str );
	}
}
