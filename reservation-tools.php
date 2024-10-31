<?php
/*
* Plugin Name: Reservation.Tools Embedded Booking Form for Restaurants, Clubs, Bars
* Author URI: https://www.reservation.tools
* Description: A widget that displays the Booking Form integration of the Reservation.Tools app SaaS
* Version: 1.0.0
* Author: Reservation Ltd.
* License: GPLv2
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function rtools_RTSettingsPage() {
  ?>
  <style>
    .token-wrapper {
      font-size: 14px;
      font-weight: bold;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .token-wrapper > p {
      margin: 0;
      padding: 0;
    }

    .app-button {
      background: #2271b1;
      border-radius: 5px;
      color: white;
      font-size: 16px;
      padding: 12px;
      text-decoration: none;
    }
    .header {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .video {
      aspect-ratio: 16 / 9;
      width: 100%;
    }
  </style>
  <div class="wrap">
  <div class="header">
    <h1>Reservation.Tools Settings (Commercial Only)</h1>
    <a class="app-button" href="https://app.reservation.tools/admin" target="_blank">Go to the App </a>
  </div>
  <form method="post" action="options.php">
      <?php
      settings_fields('rt-settings-group');
      do_settings_sections('rt-settings-group');
      ?>
      <br />
      <div class="token-wrapper">
        <span>Account Key:</span>
        <input type="text" name="token" value="<?php echo esc_attr(get_option('token')); ?>" />
        <?php submit_button(); ?>
      </div>
  </form>
  </div>
  <div class="wrap">
    <h1>How to set-up:</h1>
    <div>
      This plugin is providing and Embeddable Booking Form of the Reservation.Tools service. In order to use it, you need to have an account with Reservation.Tools and use the app.<br />
      All the management of the reservations is done in the Reservation.Tools app, using native and web apps.<br />
      <b>You can register for a 1 month trial, then contact the team for the subsciption you want to start. Pricing is available here: <a href="https://www.reservation.tools/pricing" target="_blank">https://www.reservation.tools/pricing </a></b>
    </div><br />
    1. Create an account or login: <a href="https://app.reservation.tools">https://app.reservation.tools </a><br />
    2. After logging in the app, in the top left corner of the app, use the main menu to access the administration panel, or click here:  <a href="https://app.reservation.tools/admin" target="_blank">https://app.reservation.tools/admin </a><br />
    3. In the Venues menu, chose the "Integrations" button on your Venue.<br />
    4. Click Add/Enable on the "Reservation Form" integration.<br />
    5. At the bottom of the page, you will find the HTML code of your Reservation Form. It's an Iframe with this format: "https://app.reservation.tools/i/{TOKEN}". The {TOKEN} part is your actual Account ID, that you need to copy and add in the input above, and click Save.<br />
    6. Now in the page editor there is a Widget Called Reservation.Tools Booking form. Use it to show your booking form.<br />
    7. Take a look at our tutorials, or contact us for a free demo and training. <a href="https://www.reservation.tools/how-to/how-to-manage-reservations/" target="_blank">https://www.reservation.tools/how-to/how-to-manage-reservations/ </a><br />
    8. Contact us for any enquiries. <a href="https://www.reservation.tools/contacts/" target="_blank">https://www.reservation.tools/contacts/ </a><br /><br /><br />

    <iframe class="video" src="https://www.youtube.com/embed/FMTgT6a6BJ0?si=KTuDU7oIK8yaroDG" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
  </div>
  <?php
}

function rtools_ReservationToolsAdminMenu() {
  add_menu_page(
      'Reservation.Tools',   // Page title
      'Reservation.Tools',             // Menu title
      'administrator',               // Capability
      'rt-settings',   // Menu slug
      'rtools_RTSettingsPage', // Callback function
      'dashicons-admin-generic'      // Icon
  );
}


function rtools_ReservationToolsRegisterSettings() {
  register_setting(
      'rt-settings-group', // Option group
      'token'                        // Option name
  );
}

add_action('admin_menu', 'rtools_ReservationToolsAdminMenu');
add_action('admin_init', 'rtools_ReservationToolsRegisterSettings');


function rtools_block_enqueue() {
  wp_enqueue_script(
      'reservation-tools-editor',
      plugins_url('build/index.js', __FILE__),
      array('wp-blocks', 'wp-element', 'wp-editor'),
      filemtime(plugin_dir_path(__FILE__) . 'build/index.js')
  );

  $token = get_option('token', '');

  wp_localize_script(
      'reservation-tools-editor',
      'myBlockData',
      array('token' => $token)
  );
}
add_action('enqueue_block_editor_assets', 'rtools_block_enqueue');
