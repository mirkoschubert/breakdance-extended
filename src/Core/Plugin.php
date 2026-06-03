<?php

namespace Breakdance\Ext\Core;

use Breakdance\Ext\Core\Optimization;
use Breakdance\Ext\Core\AdminSettings;
use Breakdance\Ext\Core\GooglePlaceRatingController;
use function BreakdanceExtendedElements\registerElements;

class Plugin
{
  protected $optimization;

  public function __construct()
  {
    $this->init();
    $this->registerElements();
    $this->register_dependencies();
  }

  public function init()
  {
    register_activation_hook('__FILE__', [$this, 'activate']);
    register_deactivation_hook('__FILE__', [$this, 'deactivate']);

    add_action('init', [$this, 'load_text_domain']);
    add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

    add_action('init', [$this, 'add_excerpt_to_pages']);

    new AdminSettings();

    if (get_option('bdext_feature_optimization', true)) {
      $this->optimization = new Optimization();
    }

    add_action('rest_api_init', function () {
      $controller = new GooglePlaceRatingController();
      $controller->register_routes();
    });

    add_filter('rank_math/frontend/breadcrumb/items', [$this, 'register_job_breadcrumb'], 10, 2);

    add_filter('breakdance_builder_elements', [$this, 'filter_disabled_elements']);
  }

  public function filter_disabled_elements(array $elements): array
  {
    $map = [
      'video'                 => 'BreakdanceExtendedElement\Video',
      'gallery'               => 'BreakdanceExtendedElement\Gallery',
      'icon'                  => 'BreakdanceExtendedElement\Icon',
      'blockquote'            => 'BreakdanceExtendedElement\Blockquote',
      'masked_reveal_heading' => 'BreakdanceExtendedElement\MaskedRevealHeading',
      'google_rating'         => 'BreakdanceExtendedElement\GooglePlaceRating',
      'leaflet'               => 'BreakdanceExtendedElement\LeafletMaps',
    ];

    $disabled = array_values(array_filter(
      array_map(
        fn($key, $slug) => get_option('bdext_feature_' . $key, '1') !== '1' ? $slug : null,
        array_keys($map),
        $map
      )
    ));

    return array_values(array_diff($elements, $disabled));
  }


  public function activate()
  {
    // Activation
  }

  public function deactivate()
  {
    // Deactivation
  }

  static public function uninstall()
  {
    // Uninstallation
  }

  public function load_text_domain()
  {
    load_plugin_textdomain('bdext', false, BDEXT_PATH . '/lang/');
  }

  public function get_plugin_name()
  {
    return esc_html__(BDEXT_PLUGIN, 'bdext');
  }

  public function enqueue_scripts()
  {
    if (!is_admin()) {
      wp_enqueue_script('jquery');
      wp_enqueue_script('choices-js', plugins_url('/src/Core/assets/js/choices.min.js', BDEXT_PATH), [], false, true);
      wp_enqueue_script('bdext-script', plugins_url('/src/Core/assets/js/bd-ext.js', BDEXT_PATH), ['jquery'], true);

      wp_enqueue_style('choices-css', plugins_url('/src/Core/assets/css/choices.min.css', BDEXT_PATH));
      wp_enqueue_style('bdext-style', plugins_url('/src/Core/assets/css/bd-ext.css', BDEXT_PATH));
    }
  }

  public function register_dependencies()
  {
    add_filter('breakdance_reusable_dependencies_urls', function ($urls) {
      $urls['bdextGooglePlaceRatingJs'] = plugins_url('breakdance/elements/GooglePlaceRating/assets/js/google-place-rating.js', BDEXT_PATH);

      $base = plugins_url('breakdance/elements/LeafletMaps/assets', BDEXT_PATH);

      $urls['bdextLeafletJs'] = $base . '/js/leaflet.js';
      $urls['bdextLeafletCss'] = $base . '/css/leaflet.css';
      $urls['bdextLeafletProviders'] = $base . '/js/leaflet-providers.js';
      $urls['bdextLeafletFullscreenJs'] = $base . '/js/leaflet.fullscreen.js';
      $urls['bdextLeafletFullscreenCss'] = $base . '/css/leaflet.fullscreen.css';
      $urls['bdextLeafletClusterJs'] = $base . '/js/leaflet.markercluster.js';
      $urls['bdextLeafletClusterCss'] = $base . '/css/MarkerCluster.css';
      $urls['bdextLeafletClusterDefaultCss'] = $base . '/css/MarkerCluster.Default.css';
      $urls['bdextLeafletInit'] = $base . '/js/leaflet-map-init.js';

      return $urls;
    });
  }

  private function registerElements()
  {
    registerElements();
  }

  public function register_job_breadcrumb($crumbs, $class)
  {

    if (!is_singular('job')) {
      return $crumbs;
    }

    $career_page_id = 29;
    $career_page = get_post($career_page_id);

    if (empty($career_page) || is_wp_error($career_page)) {
      return $crumbs;
    }

    $home_crumb = $crumbs[0];

    $new_crumbs = [];
    $new_crumbs[] = $home_crumb;
    $new_crumbs[] = [
      0 => get_the_title($career_page),
      1 => get_permalink($career_page),
      'hide_in_schema' => false,
    ];

    for ($i = 1; $i < count($crumbs); $i++) {
      $new_crumbs[] = $crumbs[$i];
    }

    return $new_crumbs;
  }

  public function add_excerpt_to_pages()
  {
    add_post_type_support('page', 'excerpt');
  }


}
