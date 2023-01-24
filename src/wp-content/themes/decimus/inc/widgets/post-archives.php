<?php

class Decimus_Post_Archives_Widget extends WP_Widget
{

    function __construct()
    {

        parent::__construct(
        // widget ID
            'decimus_post_archives_widget',
            // widget name
            __('Decimus Post Archives Widget', ' decimus'),
            // widget description
            ['description' => __('Decimus Post Archives Widget', 'decimus'),]
        );


        add_action('widgets_init', function () {
            register_widget('Decimus_Post_Archives_Widget');
        });
    }

    public $args = [
        'before_title' => '<h4 class="widgettitle">',
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

        echo '<div class="post-archives-content">';
        echo $this->get_the_post_archives();
        echo '</div>';

        echo $args['after_widget'];
    }


    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Archive', 'decimus');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo esc_html__('Title:', 'decimus'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>

        <?php

    }

    public function update($new_instance, $old_instance)
    {

        $instance = [];

        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

        return $instance;
    }

    /**
     *
     * @return string template
     */
    private function get_the_post_archives($period = 'yearly', $post_type = 'post'): string
    {
        $args = [
            'type' => $period,
            'post_type' => $post_type,
            'echo' => false,

        ];
        return wp_get_archives($args);
    }
}

$decimus_posts_archive_widget = new Decimus_Post_Archives_Widget();
?>
