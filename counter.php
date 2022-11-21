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
    }

    function adminPage()
    {
        add_options_page("Reading Time Counter", "RT Counter", "manage_options", "rt-counter-settings", array($this, "settingsPageHTML"));    // title, name in link list, permissions, slug, function
    }

    function settingsPageHTML()
    { ?>

        <div class="wrap">
            <h1>Reading Time Counter Settings</h1>
            <p>Settings here.</p>
        </div>

<?php
    }
}

// Actually created an instance
$readingTimeCounter = new ReadingTimeCounter();
