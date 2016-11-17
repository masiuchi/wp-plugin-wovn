<?php
/*

Plugin Name: WOVN
Plugin URI:  https://github.com/masiuchi/wp-plugin-wovn
Description: Translate WordPress site by WOVN.io.
Version:     0.0.1
Author:      Masahiro Iuchi
Author URI:  https://github.com/masiuchi
License:     GPL2

Copyright 2016 Masahiro Iuchi (email : masahiro.iuchi@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

// http://stackoverflow.com/questions/772510/wordpress-filter-to-modify-final-html-output
if (!is_admin()) {
  ob_start();
  
  add_action('shutdown', function () {
    $final = '';
    $levels = ob_get_level();
    for ($i = 0; $i < $levels; $i++) {
      $final .= ob_get_clean();
    }
    echo apply_filters('final_output', $final);
  }, 0);
  
  add_filter('final_output', function ($output) {
    $wovn_lang_code = get_query_var('wovn');
    $defaultLanguage = esc_attr(get_option('wovn_default_language'));
    $token = esc_attr(get_option('wovn_token'));

    if ($defaultLanguage !== null
      && $defaultLanguage !== ''
      && $defaultLanguage === $wovn_lang_code
    ) {
      global $wp;
      $query_string = preg_replace('/&?wovn=[^&]+/', '', $_SERVER['QUERY_STRING']);
      $redirect_url = add_query_arg($query_string, '', home_url($wp->request));
      wp_redirect($redirect_url, 307);
      return;
    }

    require_once(dirname(__FILE__) . '/wovn_translator.php');
    $translator = new WovnTranslator($defaultLanguage, $token, $output);
    return $translator->translate($wovn_lang_code);
  });
}

if (!is_admin()) {
  function add_query_vars_filter( $vars ){
    $vars[] = "wovn";
    return $vars;
  }
  add_filter( 'query_vars', 'add_query_vars_filter' );
}

if (is_admin()) {
  add_action('admin_init', function () {
    register_setting('wovn', 'wovn_token');
    register_setting('wovn', 'wovn_default_language');
  });

  add_action('admin_menu', function () {
    add_options_page( 'WOVN Settings', 'WOVN', 'manage_options', 'wovn-script', function () {
      require_once(dirname(__FILE__) . '/wovn_lang.php');

      echo '<div class="wrap">';
      echo '  <h1>WOVN settings</h1>';
      echo '  <form method="post" action="options.php">';
                settings_fields('wovn');
                do_settings_sections('wovn');
      echo '    <table class="form-table">';
      echo '      <tr>';
      echo '        <th scope="row"><label for="wovn-token">Token</label></th>';
      echo '        <td>';
      echo '          <input id="wovn-token" type="text" name="wovn_token" value="' . esc_attr(get_option('wovn_token')) . '" />';
      echo '          <p>You can get your Token from <a href="https://wovn.io" target="_blank">WOVN.io</a>.</p>';
      echo '        </td>';
      echo '      </tr>';
      echo '      <tr>';
      echo '        <th scope="row"><label for="wovn-default-language">Default Language</label></th>';
      echo '        <td>';
      echo '          <select name="wovn_default_language">';
                      $defaultLanguage = esc_attr(get_option('wovn_default_language'));
                      if ($defaultLanguage === '') {
                        $defaultLanguage = 'en';
                      }
                      foreach (WovnLang::getAllLangs() as $lang) {
                        if ($lang->code === $defaultLanguage) {
      echo '            <option value="' . $lang->code . '" selected="selected">' . $lang->en . '</option>';
                        } else {
      echo '            <option value="' . $lang->code . '">' . $lang->en . '</option>';
                        }
                      }
      echo '          </select>';
      echo '        </td>';
      echo '      </tr>';
      echo '    </table>';
      echo      submit_button();
      echo '  </form>';
      echo '</div>';
    });
  });
}

register_uninstall_hook(__FILE__, 'remove_wovn_settings');

function remove_wovn_settings () {
  delete_option('wovn_default_language');
  delete_option('wovn_token');
};
