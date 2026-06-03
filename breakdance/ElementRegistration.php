<?php

namespace BreakdanceCustomElements;

use function Breakdance\Util\getDirectoryPathRelativeToPluginFolder;
use function Breakdance\ElementStudio\registerSaveLocation;
use function Breakdance\Elements\registerCategory;

function registerElements()
{
    add_action('breakdance_loaded', function () {
        registerSaveLocation(
            getDirectoryPathRelativeToPluginFolder(__DIR__) . '/elements',
            'BreakdanceExtElements',
            'element',
            'Breakdance Extended Elements',
            false
        );

        registerSaveLocation(
            getDirectoryPathRelativeToPluginFolder(__DIR__) . '/macros',
            'BreakdanceExtElements',
            'macro',
            'Breakdance Extended Macros',
            false,
        );

        registerSaveLocation(
            getDirectoryPathRelativeToPluginFolder(__DIR__) . '/presets',
            'BreakdanceExtElements',
            'preset',
            'Breakdance Extended Presets',
            false,
        );

        $any_active = array_reduce(
            ['video', 'gallery', 'icon', 'blockquote', 'google_rating', 'leaflet'],
            fn($carry, $key) => $carry || get_option('bdext_feature_' . $key, '1') === '1',
            false
        );

        if ($any_active) {
            registerCategory('breakdance-extended', 'Breakdance Extended');
        }
    },
        // register elements before loading them
        9
    );
}