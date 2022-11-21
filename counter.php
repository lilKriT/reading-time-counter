<?php

/*
    Plugin Name: Reading Time Counter
    Description: A plugin that counts letters, words and approximate reading time for an article.
    Version: 1.0
    Author: lilKriT
    Author URI: https://lilkrit.dev
*/

function counterSettingsLink()
{
    add_options_page("Reading Time Counter", "RT Counter", "manage_options", "rt-counter-settings", "settingsPageHTML");    // title, name in link list, permissions, slug, function
}
add_action("admin_menu", "counterSettingsLink");

function settingsPageHTML()
{ ?>

    Hello World, plugin

<?php }
