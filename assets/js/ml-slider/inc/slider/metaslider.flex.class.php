<?php

/**
 * Flex Slider specific markup, javascript, css and settings.
 */
class MetaFlexSlider extends MetaSlider
{
    protected $js_function          = 'flexslider';
    protected $js_path              = 'sliders/flexslider/jquery.flexslider-min.js';
    protected $css_path             = 'sliders/flexslider/flexslider.css';
    protected $carousel_item_margin = 5;

    /**
     * Constructor
     *
     * @param int     $id slideshow ID
     * @param         $shortcode_settings
     */
    public function __construct($id, $shortcode_settings)
    {
        parent::__construct($id, $shortcode_settings);

        add_filter('metaslider_flex_slider_parameters', [$this, 'enable_carousel_mode'], 10, 2);
        add_filter('metaslider_flex_slider_parameters', [$this, 'enable_easing'], 10, 2);
        add_filter('metaslider_css', [$this, 'get_carousel_css'], 11, 3);
        add_filter('metaslider_css_classes', [$this, 'remove_bottom_margin'], 11, 3);

        $this->carousel_item_margin = apply_filters('metaslider_carousel_margin', $this->carousel_item_margin, $id);
    }

    /**
     * Adjust the slider parameters so they're comparible with the carousel mode
     *
     * @param  array $options
     * @param  int   $slider_id
     * @return array   $options
     */
    public function enable_carousel_mode($options, $slider_id)
    {
        if (isset($options['carouselMode'])) {
            if ('true' === $options['carouselMode']) {
                $options['itemWidth']  = $this->get_setting('width');
                $options['animation']  = "'slide'";
                $options['direction']  = "'horizontal'";
                $options['minItems']   = 1;
                $options['itemMargin'] = $this->carousel_item_margin;
            }

            unset($options['carouselMode']);
        }

        // we don't want this filter hanging around if there's more than one slideshow on the page
        remove_filter('metaslider_flex_slider_parameters', [$this, 'enable_carousel_mode'], 10, 2);

        return $options;
    }

    /**
     * Ensure CSS transitions are disabled when easing is enabled.
     *
     * @param  array $options
     * @param  int   $slider_id
     * @return array   $options
     */
    public function enable_easing($options, $slider_id)
    {
        if (isset($options['easing'])) {
            $options['useCSS'] = 'false';
        }

        // we don't want this filter hanging around if there's more than one slideshow on the page
        remove_filter('metaslider_flex_slider_parameters', [$this, 'enable_easing'], 10, 2);

        return $options;
    }

    /**
     * Add a 'nav-hidden' class to slideshows where the navigation is hidden.
     *
     * @param        $class
     * @param        $id
     * @param  array $settings
     * @return string $css
     * @internal param string $css
     * @internal param int $slider_id
     */
    public function remove_bottom_margin($class, $id, $settings)
    {
        if (isset($settings['navigation']) && 'false' === $settings['navigation']) {
            return $class .= ' nav-hidden';
        }

        // we don't want this filter hanging around if there's more than one slideshow on the page
        remove_filter('metaslider_css_classes', [$this, 'remove_bottom_margin'], 11, 3);

        return $class;
    }

    /**
     * Return css to ensure our slides are rendered correctly in the carousel
     *
     * @param  string $css
     * @param  array  $settings
     * @param  int    $slider_id
     * @return string  $css
     */
    public function get_carousel_css($css, $settings, $slider_id)
    {
        if (isset($settings['carouselMode']) && 'true' === $settings['carouselMode']) {
            $css .= "\n        #metaslider_{$slider_id}.flexslider li {margin-right: {$this->carousel_item_margin}px;}";
        }

        // we don't want this filter hanging around if there's more than one slideshow on the page
        remove_filter('metaslider_css', [$this, 'get_carousel_css'], 11, 3);

        return $css;
    }

    /**
     * Enable the parameters that are accepted by the slider
     *
     * @param  string $param
     * @return array|bool enabled parameters (false if parameter doesn't exist)
     */
    protected function get_param($param)
    {
        $params = [
            'effect'         => 'animation',
            'direction'      => 'direction',
            'prevText'       => 'prevText',
            'nextText'       => 'nextText',
            'delay'          => 'slideshowSpeed',
            'animationSpeed' => 'animationSpeed',
            'hoverPause'     => 'pauseOnHover',
            'reverse'        => 'reverse',
            'navigation'     => 'controlNav',
            'links'          => 'directionNav',
            'carouselMode'   => 'carouselMode',
            'easing'         => 'easing',
            'autoPlay'       => 'slideshow',
        ];

        if (isset($params[$param])) {
            return $params[$param];
        }

        return false;
    }

    /**
     * Include slider assets
     */
    public function enqueue_scripts()
    {
        parent::enqueue_scripts();

        if ('true' === $this->get_setting('printJs')) {
            wp_enqueue_script('metaslider-easing', METASLIDER_ASSETS_URL . 'easing/jQuery.easing.min.js', ['jquery'], METASLIDER_VERSION);
        }
    }

    /**
     * Build the HTML for a slider.
     *
     * @return string slider markup.
     */
    protected function get_html()
    {
        $class = 'true' === $this->get_setting('noConflict') ? '' : ' class="flexslider"';

        $return_value = '<div id="' . $this->get_identifier() . '"' . $class . '>';
        $return_value .= "\n            <ul class=\"slides\">";

        foreach ($this->slides as $slide) {
            // backwards compatibility with older versions of Meta Slider Pro (< v2.0)
            // MS Pro < 2.0 does not include the <li>
            // MS Pro 2.0+ returns the <li>
            if (0 === mb_strpos($slide, '<li')) {
                $return_value .= "\n                " . $slide;
            } else {
                $return_value .= "\n                <li style=\"display: none;\">" . $slide . '</li>';
            }
        }

        $return_value .= "\n            </ul>";
        $return_value .= "\n        </div>";

        return apply_filters('metaslider_flex_slider_get_html', $return_value, $this->id, $this->settings);
    }
}
