<?php

/**
 * Widget API: WP_Widget_Tag_Cloud class
 *
 * @package WordPress
 * @subpackage Widgets
 * @since 4.4.0
 */

/**
 * Core class used to implement a Tag cloud widget.
 *
 * @since 2.8.0
 *
 * @see WP_Widget
 */
class Decimus_Post_Tag_Cloud extends WP_Widget
{

    /**
     * Sets up a new Tag Cloud widget instance.
     *
     * @since 2.8.0
     */
    public function __construct()
    {
        $widget_ops = array(
            'description' => __('A cloud of your most used tags.', 'decimus'),
            'customize_selective_refresh' => true,
            'show_instance_in_rest' => false,
        );
        parent::__construct('decimus_post_tag_cloud', __('Decimus Tag Cloud'), $widget_ops);

        add_action('widgets_init', function () {
            register_widget('Decimus_Post_Tag_Cloud');
        });
    }

    /**
     * Outputs the content for the current Tag Cloud widget instance.
     *
     * @param array $args Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param array $instance Settings for the current Tag Cloud widget instance.
     * @since 2.8.0
     *
     */
    public function widget($args, $instance)
    {
        $current_taxonomy = $this->_get_current_taxonomy($instance);

        if ( !empty($instance['title']) ) {
            $title = $instance['title'];
        } else {
            if ( 'post_tag' === $current_taxonomy ) {
                $title = __('Tags');
            } else {
                $tax = get_taxonomy($current_taxonomy);
                $title = $tax->labels->name;
            }
        }

        $default_title = $title;

        $show_count = !empty($instance['count']);

        $tag_cloud = $this->wp_tag_cloud(
        /**
         * Filters the taxonomy used in the Tag Cloud widget.
         *
         * @param array $args Args used for the tag cloud widget.
         * @param array $instance Array of settings for the current widget.
         * @since 4.9.0 Added the `$instance` parameter.
         *
         * @see wp_tag_cloud()
         *
         * @since 2.8.0
         * @since 3.0.0 Added taxonomy drop-down.
         */
            apply_filters(
                'widget_tag_cloud_args',
                array(
                    'taxonomy' => $current_taxonomy,
                    'echo' => false,
                    'show_count' => $show_count,
                ),
                $instance
            )
        );

        if ( empty($tag_cloud) ) {
            return;
        }

        /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        echo $args['before_widget'];
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $format = current_theme_supports('html5', 'navigation-widgets') ? 'html5' : 'xhtml';

        /** This filter is documented in wp-includes/widgets/class-wp-nav-menu-widget.php */
        $format = apply_filters('navigation_widgets_format', $format);

        if ( 'html5' === $format ) {
            // The title may be filtered: Strip out HTML and make sure the aria-label is never empty.
            $title = trim(strip_tags($title));
            $aria_label = $title ? $title : $default_title;
            echo '<nav aria-label="' . esc_attr($aria_label) . '">';
        }

        echo '<div class="tagcloud">';

        echo $tag_cloud;

        echo "</div>\n";

        if ( 'html5' === $format ) {
            echo '</nav>';
        }

        echo $args['after_widget'];
    }

    /**
     * Handles updating settings for the current Tag Cloud widget instance.
     *
     * @param array $new_instance New settings for this instance as input by the user via
     *                            WP_Widget::form().
     * @param array $old_instance Old settings for this instance.
     * @return array Settings to save or bool false to cancel saving.
     * @since 2.8.0
     *
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['count'] = !empty($new_instance['count']) ? 1 : 0;
        $instance['taxonomy'] = stripslashes($new_instance['taxonomy']);
        return $instance;
    }

    /**
     * Outputs the Tag Cloud widget settings form.
     *
     * @param array $instance Current settings.
     * @since 2.8.0
     *
     */
    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $count = isset($instance['count']) ? (bool)$instance['count'] : false;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'decimus'); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>"/>
        </p>
        <?php
        $taxonomies = get_taxonomies(array('show_tagcloud' => true), 'object');
        $current_taxonomy = $this->_get_current_taxonomy($instance);

        switch (count($taxonomies)) {

            // No tag cloud supporting taxonomies found, display error message.
            case 0:
                ?>
                <input type="hidden" id="<?php echo $this->get_field_id('taxonomy'); ?>"
                       name="<?php echo $this->get_field_name('taxonomy'); ?>" value=""/>
                <p>
                    <?php _e('The tag cloud will not be displayed since there are no taxonomies that support the tag cloud widget.', 'decimus'); ?>
                </p>
                <?php
                break;

            // Just a single tag cloud supporting taxonomy found, no need to display a select.
            case 1:
                $keys = array_keys($taxonomies);
                $taxonomy = reset($keys);
                ?>
                <input type="hidden" id="<?php echo $this->get_field_id('taxonomy'); ?>"
                       name="<?php echo $this->get_field_name('taxonomy'); ?>"
                       value="<?php echo esc_attr($taxonomy); ?>"/>
                <?php
                break;

            // More than one tag cloud supporting taxonomy found, display a select.
            default:
                ?>
                <p>
                    <label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e('Taxonomy:', 'decimus'); ?></label>
                    <select class="widefat" id="<?php echo $this->get_field_id('taxonomy'); ?>"
                            name="<?php echo $this->get_field_name('taxonomy'); ?>">
                        <?php foreach ($taxonomies as $taxonomy => $tax) : ?>
                            <option value="<?php echo esc_attr($taxonomy); ?>" <?php selected($taxonomy, $current_taxonomy); ?>>
                                <?php echo esc_html($tax->labels->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>
            <?php
        }

        if ( count($taxonomies) > 0 ) {
            ?>
            <p>
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>"
                       name="<?php echo $this->get_field_name('count'); ?>" <?php checked($count, true); ?> />
                <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Show tag counts', 'decimus'); ?></label>
            </p>
            <?php
        }
    }

    /**
     * Retrieves the taxonomy for the current Tag cloud widget instance.
     *
     * @param array $instance Current settings.
     * @return string Name of the current taxonomy if set, otherwise 'post_tag'.
     * @since 4.4.0
     *
     */
    public function _get_current_taxonomy($instance)
    {
        if ( !empty($instance['taxonomy']) && taxonomy_exists($instance['taxonomy']) ) {
            return $instance['taxonomy'];
        }

        return 'post_tag';
    }


    /**
     * Displays a tag cloud.
     *
     * Outputs a list of tags in what is called a 'tag cloud', where the size of each tag
     * is determined by how many times that particular tag has been assigned to posts.
     *
     * @param array|string $args {
     *     Optional. Array or string of arguments for displaying a tag cloud. See wp_generate_tag_cloud()
     *     and get_terms() for the full lists of arguments that can be passed in `$args`.
     *
     * @type int $number The number of tags to display. Accepts any positive integer
     *                             or zero to return all. Default 45.
     * @type string $link Whether to display term editing links or term permalinks.
     *                             Accepts 'edit' and 'view'. Default 'view'.
     * @type string $post_type The post type. Used to highlight the proper post type menu
     *                             on the linked edit page. Defaults to the first post type
     *                             associated with the taxonomy.
     * @type bool $echo Whether or not to echo the return value. Default true.
     * }
     * @return void|string|string[] Void if 'echo' argument is true, or on failure. Otherwise, tag cloud
     *                              as a string or an array, depending on 'format' argument.
     * @since 4.8.0 Added the `show_count` argument.
     *
     * @since 2.3.0
     * @since 2.8.0 Added the `taxonomy` argument.
     */
    private function wp_tag_cloud($args = '')
    {
        $defaults = array(
            'smallest' => 8,
            'largest' => 22,
            'unit' => 'pt',
            'number' => 45,
            'format' => 'flat',
            'separator' => "\n",
            'orderby' => 'name',
            'order' => 'ASC',
            'exclude' => '',
            'include' => '',
            'link' => 'view',
            'taxonomy' => 'post_tag',
            'post_type' => '',
            'echo' => true,
            'show_count' => 0,
        );

        $args = wp_parse_args($args, $defaults);

        $tags = get_terms(
            array_merge(
                $args,
                array(
                    'orderby' => 'count',
                    'order' => 'DESC',
                )
            )
        ); // Always query top tags.

        if ( empty($tags) || is_wp_error($tags) ) {
            return;
        }

        foreach ($tags as $key => $tag) {
            if ( 'edit' === $args['link'] ) {
                $link = get_edit_term_link($tag, $tag->taxonomy, $args['post_type']);
            } else {
                $link = get_term_link($tag, $tag->taxonomy);
            }

            if ( is_wp_error($link) ) {
                return;
            }

            $tags[$key]->link = $link;
            $tags[$key]->id = $tag->term_id;
        }

        // Here's where those top tags get sorted according to $args.
        $return = $this->wp_generate_tag_cloud($tags, $args);

        /**
         * Filters the tag cloud output.
         *
         * @param string|string[] $return Tag cloud as a string or an array, depending on 'format' argument.
         * @param array $args An array of tag cloud arguments. See wp_tag_cloud()
         *                                for information on accepted arguments.
         * @since 2.3.0
         *
         */
        $return = apply_filters('wp_tag_cloud', $return, $args);

        if ( 'array' === $args['format'] || empty($args['echo']) ) {
            return $return;
        }

        echo $return;
    }


    /**
     * Generates a tag cloud (heatmap) from provided data.
     *
     * @param WP_Term[] $tags Array of WP_Term objects to generate the tag cloud for.
     * @param string|array $args {
     *     Optional. Array or string of arguments for generating a tag cloud.
     *
     * @type int $smallest Smallest font size used to display tags. Paired
     *                                                with the value of `$unit`, to determine CSS text
     *                                                size unit. Default 8 (pt).
     * @type int $largest Largest font size used to display tags. Paired
     *                                                with the value of `$unit`, to determine CSS text
     *                                                size unit. Default 22 (pt).
     * @type string $unit CSS text size unit to use with the `$smallest`
     *                                                and `$largest` values. Accepts any valid CSS text
     *                                                size unit. Default 'pt'.
     * @type int $number The number of tags to return. Accepts any
     *                                                positive integer or zero to return all.
     *                                                Default 0.
     * @type string $format Format to display the tag cloud in. Accepts 'flat'
     *                                                (tags separated with spaces), 'list' (tags displayed
     *                                                in an unordered list), or 'array' (returns an array).
     *                                                Default 'flat'.
     * @type string $separator HTML or text to separate the tags. Default "\n" (newline).
     * @type string $orderby Value to order tags by. Accepts 'name' or 'count'.
     *                                                Default 'name'. The {@see 'tag_cloud_sort'} filter
     *                                                can also affect how tags are sorted.
     * @type string $order How to order the tags. Accepts 'ASC' (ascending),
     *                                                'DESC' (descending), or 'RAND' (random). Default 'ASC'.
     * @type int|bool $filter Whether to enable filtering of the final output
     *                                                via {@see 'wp_generate_tag_cloud'}. Default 1.
     * @type string $topic_count_text Nooped plural text from _n_noop() to supply to
     *                                                tag counts. Default null.
     * @type callable $topic_count_text_callback Callback used to generate nooped plural text for
     *                                                tag counts based on the count. Default null.
     * @type callable $topic_count_scale_callback Callback used to determine the tag count scaling
     *                                                value. Default default_topic_count_scale().
     * @type bool|int $show_count Whether to display the tag counts. Default 0. Accepts
     *                                                0, 1, or their bool equivalents.
     * }
     * @return string|string[] Tag cloud as a string or an array, depending on 'format' argument.
     * @todo Complete functionality.
     * @since 2.3.0
     * @since 4.8.0 Added the `show_count` argument.
     *
     */
    function wp_generate_tag_cloud($tags, $args = '')
    {
        $defaults = array(
            'smallest' => 8,
            'largest' => 22,
            'unit' => 'pt',
            'number' => 0,
            'format' => 'flat',
            'separator' => "\n",
            'orderby' => 'name',
            'order' => 'ASC',
            'topic_count_text' => null,
            'topic_count_text_callback' => null,
            'topic_count_scale_callback' => 'default_topic_count_scale',
            'filter' => 1,
            'show_count' => 0,
        );

        $args = wp_parse_args($args, $defaults);

        $return = ('array' === $args['format']) ? array() : '';

        if ( empty($tags) ) {
            return $return;
        }

        // Juggle topic counts.
        if ( isset($args['topic_count_text']) ) {
            // First look for nooped plural support via topic_count_text.
            $translate_nooped_plural = $args['topic_count_text'];
        } elseif ( !empty($args['topic_count_text_callback']) ) {
            // Look for the alternative callback style. Ignore the previous default.
            if ( 'default_topic_count_text' === $args['topic_count_text_callback'] ) {
                /* translators: %s: Number of items (tags). */
                $translate_nooped_plural = _n_noop('%s item', '%s items');
            } else {
                $translate_nooped_plural = false;
            }
        } elseif ( isset($args['single_text']) && isset($args['multiple_text']) ) {
            // If no callback exists, look for the old-style single_text and multiple_text arguments.
            // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralSingle,WordPress.WP.I18n.NonSingularStringLiteralPlural
            $translate_nooped_plural = _n_noop($args['single_text'], $args['multiple_text']);
        } else {
            // This is the default for when no callback, plural, or argument is passed in.
            /* translators: %s: Number of items (tags). */
            $translate_nooped_plural = _n_noop('%s item', '%s items');
        }

        /**
         * Filters how the items in a tag cloud are sorted.
         *
         * @param WP_Term[] $tags Ordered array of terms.
         * @param array $args An array of tag cloud arguments.
         * @since 2.8.0
         *
         */
        $tags_sorted = apply_filters('tag_cloud_sort', $tags, $args);
        if ( empty($tags_sorted) ) {
            return $return;
        }

        if ( $tags_sorted !== $tags ) {
            $tags = $tags_sorted;
            unset($tags_sorted);
        } else {
            if ( 'RAND' === $args['order'] ) {
                shuffle($tags);
            } else {
                // SQL cannot save you; this is a second (potentially different) sort on a subset of data.
                if ( 'name' === $args['orderby'] ) {
                    uasort($tags, '_wp_object_name_sort_cb');
                } else {
                    uasort($tags, '_wp_object_count_sort_cb');
                }

                if ( 'DESC' === $args['order'] ) {
                    $tags = array_reverse($tags, true);
                }
            }
        }

        if ( $args['number'] > 0 ) {
            $tags = array_slice($tags, 0, $args['number']);
        }

        $counts = array();
        $real_counts = array(); // For the alt tag.
        foreach ((array)$tags as $key => $tag) {
            $real_counts[$key] = $tag->count;
            $counts[$key] = call_user_func($args['topic_count_scale_callback'], $tag->count);
        }

        $min_count = min($counts);
        $spread = max($counts) - $min_count;
        if ( $spread <= 0 ) {
            $spread = 1;
        }
        $font_spread = $args['largest'] - $args['smallest'];
        if ( $font_spread < 0 ) {
            $font_spread = 1;
        }
        $font_step = $font_spread / $spread;

        $aria_label = false;
        /*
	 * Determine whether to output an 'aria-label' attribute with the tag name and count.
	 * When tags have a different font size, they visually convey an important information
	 * that should be available to assistive technologies too. On the other hand, sometimes
	 * themes set up the Tag Cloud to display all tags with the same font size (setting
	 * the 'smallest' and 'largest' arguments to the same value).
	 * In order to always serve the same content to all users, the 'aria-label' gets printed out:
	 * - when tags have a different size
	 * - when the tag count is displayed (for example when users check the checkbox in the
	 *   Tag Cloud widget), regardless of the tags font size
	 */
        if ( $args['show_count'] || 0 !== $font_spread ) {
            $aria_label = true;
        }

        // Assemble the data that will be used to generate the tag cloud markup.
        $tags_data = array();
        foreach ($tags as $key => $tag) {
            $tag_id = isset($tag->id) ? $tag->id : $key;

            $count = $counts[$key];
            $real_count = $real_counts[$key];

            if ( $translate_nooped_plural ) {
                $formatted_count = sprintf(translate_nooped_plural($translate_nooped_plural, $real_count), number_format_i18n($real_count));
            } else {
                $formatted_count = call_user_func($args['topic_count_text_callback'], $real_count, $tag, $args);
            }

            $tags_data[] = array(
                'id' => $tag_id,
                'url' => ('#' !== $tag->link) ? $tag->link : '#',
                'role' => ('#' !== $tag->link) ? '' : ' role="button"',
                'name' => $tag->name,
                'formatted_count' => $formatted_count,
                'slug' => $tag->slug,
                'real_count' => $real_count,
                'class' => 'tag is-link is-size-7 has-text-weight-medium mb-1 has-background-grey-light is-rounded tag-cloud-link tag-link-' . $tag_id,
                'font_size' => $args['smallest'] + ($count - $min_count) * $font_step,
                'aria_label' => $aria_label ? sprintf(' aria-label="%1$s (%2$s)"', esc_attr($tag->name), esc_attr($formatted_count)) : '',
                'show_count' => $args['show_count'] ? '<span class="tag-link-count"> (' . $real_count . ')</span>' : '',
            );
        }

        /**
         * Filters the data used to generate the tag cloud.
         *
         * @param array[] $tags_data An array of term data arrays for terms used to generate the tag cloud.
         * @since 4.3.0
         *
         */
        $tags_data = apply_filters('wp_generate_tag_cloud_data', $tags_data);

        $a = array();

        // Generate the output links array.
        foreach ($tags_data as $key => $tag_data) {
            $class = $tag_data['class'] . ' tag-link-position-' . ($key + 1);
            $a[] = sprintf(
                '<a href="%1$s"%2$s class="%3$s" style="font-size: %4$s;"%5$s>%6$s%7$s</a>',
                esc_url($tag_data['url']),
                $tag_data['role'],
                esc_attr($class),
                esc_attr(str_replace(',', '.', $tag_data['font_size']) . $args['unit']),
                $tag_data['aria_label'],
                esc_html($tag_data['name']),
                $tag_data['show_count']
            );
        }

        switch ($args['format']) {
            case 'array':
                $return = &$a;
                break;
            case 'list':
                /*
			 * Force role="list", as some browsers (sic: Safari 10) don't expose to assistive
			 * technologies the default role when the list is styled with `list-style: none`.
			 * Note: this is redundant but doesn't harm.
			 */
                $return = "<ul class='wp-tag-cloud' role='list'>\n\t<li>";
                $return .= implode("</li>\n\t<li>", $a);
                $return .= "</li>\n</ul>\n";
                break;
            default:
                $return = implode($args['separator'], $a);
                break;
        }

        if ( $args['filter'] ) {
            /**
             * Filters the generated output of a tag cloud.
             *
             * The filter is only evaluated if a true value is passed
             * to the $filter argument in wp_generate_tag_cloud().
             *
             * @param string[]|string $return String containing the generated HTML tag cloud output
             *                                or an array of tag links if the 'format' argument
             *                                equals 'array'.
             * @param WP_Term[] $tags An array of terms used in the tag cloud.
             * @param array $args An array of wp_generate_tag_cloud() arguments.
             * @see wp_generate_tag_cloud()
             *
             * @since 2.3.0
             *
             */
            return apply_filters('wp_generate_tag_cloud', $return, $tags, $args);
        } else {
            return $return;
        }
    }
}

$decimus_post_tags_widget = new Decimus_Post_Tag_Cloud();
