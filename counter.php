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
        add_filter("the_content", array($this, "ifWrap"));
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
        register_setting("wordCountPlugin", "rtc_location", array("sanitize_callback" => array($this, "sanitizeLocation"), "default" => "0"));  // group of settings, setting name, array(how to sanitize, default value)

        // Another field - Headline
        add_settings_field("rtc_headline", "Headline Text", array($this, "headlineHTML"), "rtc-settings-page", "rtc_first_section");
        register_setting("wordCountPlugin", "rtc_headline", array("sanitize_callback" => "sanitize_text_field", "default" => "Post statistics"));

        // Word Count
        add_settings_field("rtc_wordcount", "Word Count", array($this, "checkBoxHTML"), "rtc-settings-page", "rtc_first_section", array('theName' => "rtc_wordcount"));
        register_setting("wordCountPlugin", "rtc_wordcount", array("sanitize_callback" => "sanitize_text_field", "default" => "1"));

        // Character Count
        add_settings_field("rtc_charactercount", "Character Count", array($this, "checkBoxHTML"), "rtc-settings-page", "rtc_first_section", array('theName' => "rtc_charactercount"));
        register_setting("wordCountPlugin", "rtc_charactercount", array("sanitize_callback" => "sanitize_text_field", "default" => "1"));

        // Read Time
        add_settings_field("rtc_readTime", "Read Time", array($this, "checkBoxHTML"), "rtc-settings-page", "rtc_first_section", array('theName' => "rtc_readTime"));
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

    function checkBoxHTML($args)
    { ?>
        <input type="checkbox" name="<?php echo $args['theName'] ?>" value="1" <?php checked(get_option($args['theName']), "1"); ?>>
<?php     }

    function sanitizeLocation($input)
    {
        if ($input != "0" && $input != "1") {
            add_settings_error("rtc_location", "wcp_location_error", "Display location must be beginning or end.");
            return get_option("rtc_location");
        }
        return $input;
    }

    function ifWrap($content)
    {
        if (is_main_query() && is_single() && (get_option("rtc_wordcount", "1") || get_option("rtc_charactercount", "1") || get_option("rtc_readTime", "1"))) {
            return $this->addInfo($content);
        }
        return $content;
    }

    function addInfo($content)
    {
        $extraInfo = '<h3>' . esc_html(get_option("rtc_headline", "Post Statistics")) . '</h3><p>';

        // Calculating
        if (get_option("rtc_wordcount", "1") || get_option("rtc_readTime", "1")) {
            $wordCount = str_word_count(strip_tags($content));
        }

        if (get_option("rtc_wordcount", "1")) {
            $extraInfo .= "This post has " . $wordCount . " words.<br>";
        }

        if (get_option("rtc_charactercount", "1")) {
            $extraInfo .= "This post has " . strlen(strip_tags($content)) . " characters.<br>";
        }

        if (get_option("rtc_readTime", "1")) {
            $extraInfo .= "This post will take around " . round($wordCount / 225) . " minute(s) to read.<br>";
        }

        $extraInfo .= "</p>";

        if (get_option("rtc_location", 0)) {
            return $extraInfo . $content;
        } else {
            return $content . $extraInfo;
        }
    }
}

// Actually created an instance
$readingTimeCounter = new ReadingTimeCounter();
