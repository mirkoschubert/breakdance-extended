<?php

namespace BreakdanceExtendedElement;

use function Breakdance\Elements\c;
use function Breakdance\Elements\PresetSections\getPresetSection;

if (get_option('bdext_feature_masked_reveal_heading', '1') !== '1') return;

\Breakdance\ElementStudio\registerElementForEditing(
    "BreakdanceExtendedElement\\MaskedRevealHeading",
    \Breakdance\Util\getdirectoryPathRelativeToPluginFolder(__DIR__)
);

class MaskedRevealHeading extends \Breakdance\Elements\Element
{
    static function uiIcon()
    {
        return 'HeadingIcon';
    }

    static function tag()
    {
        return 'h1';
    }

    static function tagOptions()
    {
        return ['h2', 'h3', 'h4', 'h5', 'h6'];
    }

    static function tagControlPath()
    {
        return false;
    }

    static function name()
    {
        return 'Masked Reveal Heading';
    }

    static function className()
    {
        return 'bdext-reveal-heading';
    }

    static function category()
    {
        return 'breakdance-extended';
    }

    static function badge()
    {
        return false;
    }

    static function slug()
    {
        return __CLASS__;
    }

    static function template()
    {
        return file_get_contents(__DIR__ . '/html.twig');
    }

    static function defaultCss()
    {
        return file_get_contents(__DIR__ . '/default.css');
    }

    static function defaultProperties()
    {
        return false;
    }

    static function defaultChildren()
    {
        return false;
    }

    static function cssTemplate()
    {
        $template = file_get_contents(__DIR__ . '/css.twig');
        return $template;
    }

    static function designControls()
    {
        return [c(
        "size",
        "Size",
        [c(
        "width",
        "Width",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['px', 'em', 'rem', '%', 'vw', 'svw', 'custom', 'auto', 'calc']]],
        false,
        false,
        [],
        
      )],
        ['type' => 'section'],
        false,
        false,
        [],
        
      ), getPresetSection(
      "EssentialElements\\typography",
      "Typography",
      "typography",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\spacing_margin_y",
      "Spacing",
      "spacing",
       ['type' => 'popout']
     )];
    }

    static function contentControls()
    {
        return [c(
        "content",
        "Content",
        [c(
        "lines",
        "Lines",
        [c(
        "text",
        "Text",
        [],
        ['type' => 'text', 'layout' => 'vertical'],
        false,
        false,
        [],
        
      ), c(
        "style",
        "Style",
        [],
        ['type' => 'dropdown', 'layout' => 'vertical', 'items' => [['value' => 'default', 'text' => 'Default'], ['text' => 'Outline', 'value' => 'outline'], ['text' => 'Accent', 'value' => 'accent']]],
        false,
        false,
        [],
        
      ), c(
        "dot",
        "Dot",
        [],
        ['type' => 'toggle', 'layout' => 'vertical'],
        false,
        false,
        [],
        
      )],
        ['type' => 'repeater', 'layout' => 'vertical', 'repeaterOptions' => ['titleTemplate' => '{text}', 'defaultTitle' => '', 'buttonName' => '']],
        false,
        false,
        [],
        
      ), c(
        "aria_label",
        "Aria Label",
        [],
        ['type' => 'text', 'layout' => 'inline', 'textOptions' => ['format' => 'plain']],
        false,
        false,
        [],
        
      )],
        ['type' => 'section', 'layout' => 'vertical'],
        false,
        false,
        [],
        
      )];
    }

    static function settingsControls()
    {
        return [];
    }

    static function dependencies()
    {
        return ['0' =>  ['title' => 'Masked Reveal Animation','inlineScripts' => ['(function() {
  function isBuilder() {
    return (
      document.body.classList.contains(\'breakdance-builder\') ||
      document.documentElement.classList.contains(\'breakdance-builder\') ||
      window.self !== window.top
    );
  }

  var headings = document.querySelectorAll(\'.bdext-reveal-heading\');

  headings.forEach(function(heading) {
    var words = heading.querySelectorAll(\'.text\');

    if (isBuilder()) {
      words.forEach(function(el) {
        el.classList.add(\'in\');
      });
      return;
    }

    function triggerAnimation() {
      words.forEach(function(el, i) {
        setTimeout(function() {
          el.classList.add(\'in\');
        }, 200 + i * 140);
      });
    }

    if (\'IntersectionObserver\' in window) {
      var done = false;
      var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
          if (entry.isIntersecting && !done) {
            done = true;
            triggerAnimation();
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.3 });

      observer.observe(heading);
    } else {
      triggerAnimation();
    }
  });
})();'],],];
    }

    static function settings()
    {
        return false;
    }

    static function addPanelRules()
    {
        return false;
    }

    static public function actions()
    {
        return [

'onPropertyChange' => [['script' => '(function() {
  var root = document.querySelector(\'%%SELECTOR%%\');
  if (!root) return;

  root.querySelectorAll(\'.text\').forEach(function(el) {
    el.classList.add(\'in\');
    el.style.transform = \'translateY(0)\';
    el.style.opacity = \'1\';
    el.style.transition = \'none\';
  });
})();',
],],

'onCreatedElement' => [['script' => '(function() {
  var root = document.querySelector(\'%%SELECTOR%%\');
  if (!root) return;

  root.querySelectorAll(\'.text\').forEach(function(el) {
    el.classList.add(\'in\');
    el.style.transform = \'translateY(0)\';
    el.style.opacity = \'1\';
    el.style.transition = \'none\';
  });
})();',
],],];
    }

    static function nestingRule()
    {
        return ['type' => 'final'];
    }

    static function spacingBars()
    {
        return false;
    }

    static function attributes()
    {
        return [['name' => 'aria-label', 'template' => '{{ content.content.aria_label }}']];
    }

    static function experimental()
    {
        return false;
    }

    static function availableIn()
    {
        return ['breakdance'];
    }


    static function order()
    {
        return 0;
    }

    static function dynamicPropertyPaths()
    {
        return false;
    }

    static function additionalClasses()
    {
        return false;
    }

    static function projectManagement()
    {
        return false;
    }

    static function propertyPathsToWhitelistInFlatProps()
    {
        return false;
    }

    static function propertyPathsToSsrElementWhenValueChanges()
    {
        return false;
    }
}
