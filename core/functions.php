<?php
/* Define values for use global
 *
 */
//define('MULTIPLIER', ['KB' => 1024, 'MB' => 1048576, 'GB' => 1073741824, 'TB' => 1099511627776]);
/**
 * Outputs the html checked attribute.
 *
 * Compares the first two arguments and if identical marks as checked
 *
 * @since 1.0.0
 *
 * @param mixed $checked One of the values to compare
 * @param mixed $current (true) The other value to compare if not just true
 * @param bool  $echo    Whether to echo or just return the string
 * @return string html attribute or empty string
 */
function checked( $checked, $current = true, $echo = true ) {
    return __checked_selected_helper( $checked, $current, $echo, 'checked' );
}

/**
 * Outputs the html selected attribute.
 *
 * Compares the first two arguments and if identical marks as selected
 *
 * @since 1.0.0
 *
 * @param mixed $selected One of the values to compare
 * @param mixed $current  (true) The other value to compare if not just true
 * @param bool  $echo     Whether to echo or just return the string
 * @return string html attribute or empty string
 */
function selected( $selected, $current = true, $echo = true ) {
    return __checked_selected_helper( $selected, $current, $echo, 'selected' );
}

/**
 * Outputs the html disabled attribute.
 *
 * Compares the first two arguments and if identical marks as disabled
 *
 * @since 1.0.0
 *
 * @param mixed $disabled One of the values to compare
 * @param mixed $current  (true) The other value to compare if not just true
 * @param bool  $echo     Whether to echo or just return the string
 * @return string html attribute or empty string
 */
function disabled( $disabled, $current = true, $echo = true ) {
    return __checked_selected_helper( $disabled, $current, $echo, 'disabled' );
}

/**
 * Outputs the html readonly attribute.
 *
 * Compares the first two arguments and if identical marks as readonly
 *
 * @since 1.0.0
 *
 * @param mixed $readonly One of the values to compare
 * @param mixed $current  (true) The other value to compare if not just true
 * @param bool  $echo     Whether to echo or just return the string
 * @return string html attribute or empty string
 */
function readonly( $readonly, $current = true, $echo = true ) {
    return __checked_selected_helper( $readonly, $current, $echo, 'readonly' );
}

/**
 * Private helper function for checked, selected, disabled and readonly.
 *
 * Compares the first two arguments and if identical marks as $type
 *
 * @since 1.0.0
 * @access private
 *
 * @param mixed  $helper  One of the values to compare
 * @param mixed  $current (true) The other value to compare if not just true
 * @param bool   $echo    Whether to echo or just return the string
 * @param string $type    The type of checked|selected|disabled|readonly we are doing
 * @return string html attribute or empty string
 */
function __checked_selected_helper( $helper, $current, $echo, $type ) {
    if ( (string) $helper === (string) $current ) {
        $result = " $type='$type'";
    } else {
        $result = '';
    }

    if ( $echo ) {
        echo $result;
    }

    return $result;
}

/**
 * Appends a trailing slash.
 *
 * Will remove trailing forward and backslashes if it exists already before adding
 * a trailing forward slash. This prevents double slashing a string or path.
 *
 * The primary use of this is for paths and thus should be used for paths. It is
 * not restricted to paths and offers no specific path support.
 *
 * @since 1.0.0
 *
 * @param string $string What to add the trailing slash to.
 * @return string String with trailing slash added.
 */
function trailingslashit( $string ) {
    return untrailingslashit( $string ) . '/';
}
/**
 * Removes trailing forward slashes and backslashes if they exist.
 *
 * The primary use of this is for paths and thus should be used for paths. It is
 * not restricted to paths and offers no specific path support.
 *
 * @since 1.0.0
 *
 * @param string $string What to remove the trailing slashes from.
 * @return string String without the trailing slashes.
 */
function untrailingslashit( $string ) {
    return rtrim( $string, '/\\' );
}
/**
 * Check current page and element of page.
 *
 * Compares the first two arguments and if identical marks as active
 *
 * @since 1.0.0
 *
 * @param mixed  $current One of the values to compare
 * @param mixed  $page (true) The other value to compare if not just true
 * @return string html attribute or empty string.
 */
function current_page( $current, $page, $class = 'active') {
    if ( (string) $current === (string) $page ) {
        $result = 'class="'.$class.'"';
    } else {
        $result = '';
    }
    echo $result;
}

function slugify($string, $replace = array(), $delimiter = '-') {
  if (!extension_loaded('iconv')) {
    throw new Exception('iconv module not loaded');
  }
  // Save the old locale and set the new locale to UTF-8
  $oldLocale = setlocale(LC_ALL, '0');
  setlocale(LC_ALL, 'en_US.UTF-8');
  $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
  if (!empty($replace)) {
    $clean = str_replace((array) $replace, ' ', $clean);
  }
  $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
  $clean = strtolower($clean);
  $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
  $clean = trim($clean, $delimiter);
  // Revert back to the old locale
  setlocale(LC_ALL, $oldLocale);
  return $clean;
}