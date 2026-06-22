<?php
/** @var array $propertiesData */

if (!function_exists('trp_custom_language_switcher')) {
    return;
}

$languages = trp_custom_language_switcher();

if (empty($languages) || !is_array($languages)) {
    return;
}

$content   = $propertiesData['content']['content'] ?? [];
$separator = $content['separator'] ?? ' / ';

$current_locale = get_locale();
$items = [];

foreach ($languages as $lang) {
    $label = strtolower($lang['short_language_name'] ?? substr($lang['language_code'] ?? '', 0, 2));

    $is_active = ($lang['language_code'] ?? '') === $current_locale;
    $class     = $is_active ? 'link is-active' : 'link';

    $items[] = sprintf(
        '<a class="%s" href="%s">%s</a>',
        esc_attr($class),
        esc_url($lang['current_page_url'] ?? '#'),
        esc_html($label)
    );
}

echo implode('<span class="separator">' . esc_html($separator) . '</span>', $items);
