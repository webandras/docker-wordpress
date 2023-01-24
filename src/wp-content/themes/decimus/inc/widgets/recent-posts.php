<?php

// print_r(wp_get_registered_image_subsizes());

class Decimus_Recent_Posts_Widget extends WP_Widget
{

    function __construct()
    {

        parent::__construct(
        // widget ID
            'decimus_recent_posts_widget',
            // widget name
            __('Decimus Recent Posts Widget', ' decimus'),
            // widget description
            array('description' => __('Decimus Recent Posts Widget', 'decimus'),)
        );


        add_action('widgets_init', function () {
            register_widget('Decimus_Recent_Posts_Widget');
        });
    }

    public $args = [
        'before_title' => '<h4 class="widgettitle is-5">',
        'after_title' => '</h4>',
        'before_widget' => '<div class="widget-wrap">',
        'after_widget' => '</div>'
    ];

    public function widget($args, $instance)
    {

        echo $args['before_widget'];

        if ( !empty($instance['title']) ) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        echo '<div class="recent-articles-content">';
        echo $this->get_the_recent_posts(absint($instance['number_of_posts']));
        echo '</div>';

        echo $args['after_widget'];
    }


    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Recent Posts', 'decimus');
        $number_of_posts = !empty($instance['number_of_posts']) ? absint($instance['number_of_posts']) : 5;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo esc_html__('Title:', 'decimus'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number_of_posts')); ?>"><?php echo esc_html__('Number of posts to show:', 'decimus'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('number_of_posts')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('number_of_posts')); ?>" type="number"
                   value="<?php echo esc_attr($number_of_posts); ?>">
        </p>
        <?php

    }

    public function update($new_instance, $old_instance)
    {

        $instance = [];

        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['number_of_posts'] = (!empty($new_instance['number_of_posts'])) ? absint($new_instance['number_of_posts']) : 5;

        return $instance;
    }

    /**
     * @param int $number_of_posts
     *
     * @return string template
     */
    private function get_the_recent_posts($number_of_posts = 5): string
    {
        $template = '<ul>';

        // WP_Query arguments
        $args = [
            'post_type' => ['post'],
            'post_status' => ['publish'], // Also support: pending, draft, auto-draft, future, private, inherit, trash, any
            'posts_per_page' => $number_of_posts, // -1 for all post
            'order' => 'DESC',
            'orderby' => 'date',
        ];

        // The Query
        $query = new WP_Query($args);

        // The Loop
        if ( $query->have_posts() ) {
            while ($query->have_posts()) {
                $query->the_post();

                $template .= '<li>';
                $template .= '<a href="' . esc_url(get_the_permalink()) . '">';
                $template .= '<div class="post-inline">';
                $template .= wp_get_attachment_image(get_post_thumbnail_id(), 'thumbnail');
                //$template .= get_the_post_thumbnail(get_post_thumbnail_id(), "thumbnail");
                $template .= '<span>' . esc_html(get_the_title()) . '</span>';
                $template .= '</div>';
                $template .= '</a>';
                $template .= '</li>';
            }
        } else {
            // no posts found
            $template .= '<li>';
            $template .= '<span>' . esc_html(__('No posts found.', 'decimus')) . '</span>';
            $template .= '</li>';
        }

        // Restore original postdata
        wp_reset_postdata();

        $template .= '</ul>';
        return $template;
    }
}

$decimus_recent_posts_widget = new Decimus_Recent_Posts_Widget();
?>
