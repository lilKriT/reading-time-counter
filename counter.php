<?php

/*
    Plugin Name: Reading Time Counter
    Description: A plugin that counts letters, words and approximate reading time for an article.
    Version: 1.0
    Author: lilKriT
    Author URI: https://lilkrit.dev
*/

// 
class ReadingTimeCounter
{
    function __construct()
    {
        add_action("admin_menu", array($this, "adminPage"));
        add_action("admin_init", array($this, "settings"));
    }

    function adminPage()
    {
        add_options_page("Reading Time Counter", "RT Counter", "manage_options", "rtc-settings-page", array($this, "settingsPageHTML"));    // title, name in link list, permissions, slug, function
    }

    function settingsPageHTML()
    { ?>

        <div class="wrap">
            <h1>Reading Time Counter Settings</h1>
            <!-- options.php - wordpress will know what to do with this! -->
            <form action="options.php" method="POST">
                <!-- Built in wp methods -->
                <?php
                // Next line is to avoid warnings (by adding some security, like a nonce)
                settings_fields("wordCountPlugin");  // add group name
                do_settings_sections("rtc-settings-page");
                submit_button();
                ?>
            </form>
        </div>

    <?php
    }

    function settings()
    {
        // Add first section
        add_settings_section("rtc_first_section", null, null, "rtc-settings-page"); // name, title(optional), content (optional), page slug

        // This shows the field
        add_settings_field("rtc_location", "Display Location", array($this, "locationHTML"), "rtc-settings-page", "rtc_first_section");   // name (same as in register setting), html label, the html, slug of the page, section

        // This adds it to the db
        register_setting("wordCountPlugin", "rtc_location", array("sanitize_callback" => "sanitize_text_field", "default" => "0"));  // group of settings, setting name, array(how to sanitize, default value)
    }

    function locationHTML()
    { ?>
        <!-- match the name of the setting you registered -->
        <select name="rtc_location">
            <option value="0">Beginning of post</option>
            <option value="1">End of post</option>
        </select>
<? }
}

// Actually created an instance
$readingTimeCounter = new ReadingTimeCounter();
