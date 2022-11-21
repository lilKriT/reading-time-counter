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

        // Another field - Headline
        add_settings_field("rtc_headline", "Headline Text", array($this, "headlineHTML"), "rtc-settings-page", "rtc_first_section");
        register_setting("wordCountPlugin", "rtc_headline", array("sanitize_callback" => "sanitize_text_field", "default" => "Post statistics"));

        // Word Count
        add_settings_field("rtc_wordcount", "Word Count", array($this, "wordcountHTML"), "rtc-settings-page", "rtc_first_section");
        register_setting("wordCountPlugin", "rtc_wordcount", array("sanitize_callback" => "sanitize_text_field", "default" => "1"));

        // Character Count
        add_settings_field("rtc_charactercount", "Character Count", array($this, "characterCountHTML"), "rtc-settings-page", "rtc_first_section");
        register_setting("wordCountPlugin", "rtc_charactercount", array("sanitize_callback" => "sanitize_text_field", "default" => "1"));

        // Read Time
        add_settings_field("rtc_readTime", "Read Time", array($this, "readTimeHTML"), "rtc-settings-page", "rtc_first_section");
        register_setting("wordCountPlugin", "rtc_readTime", array("sanitize_callback" => "sanitize_text_field", "default" => "1"));
    }

    function locationHTML()
    { ?>
        <!-- match the name of the setting you registered -->
        <select name="rtc_location">
            <option value="0" <?php selected(get_option("rtc_location"), "0"); ?>>Beginning of post</option>
            <option value="1" <?php selected(get_option("rtc_location"), "1"); ?>>End of post</option>
        </select>
    <?php }

    function headlineHTML()
    { ?>
        <input type="text" name="rtc_headline" value="<?php echo esc_attr(get_option("rtc_headline")); ?>">

    <?php }

    function wordcountHTML()
    { ?>
        <input type="checkbox" name="rtc_wordcount" value="1" <?php checked(get_option("rtc_wordcount"), "1"); ?>>
    <?php }

    function characterCountHTML()
    { ?>
        <input type="checkbox" name="rtc_charactercount" value="1" <?php checked(get_option("rtc_charactercount"), "1"); ?>>
    <?php    }

    function readTimeHTML()
    { ?>
        <input type="checkbox" name="rtc_readTime" value="1" <?php checked(get_option("rtc_readTime"), "1"); ?>>
<?php    }
}

// Actually created an instance
$readingTimeCounter = new ReadingTimeCounter();
