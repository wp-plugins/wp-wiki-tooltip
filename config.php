<?php

$wp_wiki_tooltip_default_options = array(

    'wiki-urls' => array (
        'standard' => 1,
        'data' => array (
            '1' => array ( // standard URL of Wiki to get contents from
                'id' => 'EN',
                'url' => 'https://en.wikipedia.org/w/api.php',
                'sitename' => 'Wikipedia'
            )
        )
    ),

    'cache' => array ( // NOT USED: cache settings - default "1 week"

        'count' => 1, // how many

        'unit' => 'week' // what
    ),

    'a-target' => '_blank', // where to open links to wiki pages

    'theme' => 'default', // use default theme of Tooltipster

    'tooltip-head' => 'font-size: 125%; font-weight: bold;', // make the head of the tooltip a little bigger

    'tooltip-body' => '', // no special body styles in tooltip

    'tooltip-foot' => 'font-style: italic; font-weight: bold;', // make the footer link somehow nicer

    'a-style' => 'font-style: italic;'  // line of css for the style attribute
);