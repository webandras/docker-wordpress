<?php

// Comments
function decimus_reply(): void
{

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'decimus_reply');


// Comments
if (!function_exists('decimus_comment')) :
    /**
     * Template for comments and pingbacks.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     */
    function decimus_comment($comment, $args, $depth): void
    {
        // $GLOBALS['comment'] = $comment;

        if ('pingback' == $comment->comment_type || 'trackback' == $comment->comment_type) : ?>

            <li class="bg-danger" id="comment-<?php comment_ID(); ?>" <?php comment_class('media'); ?>>
                <div class="comment-body">
                    <?php _e('Pingback:', 'decimus'); ?> <?php comment_author_link(); ?> <?php edit_comment_link(__('Edit', 'decimus'), '<span class="edit-link">', '</span>'); ?>
                </div>

            <?php else : ?>

            <li id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?>>

                <article id="div-comment-<?php comment_ID(); ?>" class="comment-body mt-4 d-flex">

                    <div class="comment-content">
                        <div class="card">
                            <div class="card-body">

                                <div class="mt-0">
                                    <?php if (0 != $args['avatar_size']) echo get_avatar($comment, 32/*$args['avatar_size']*/, '', '', array('class' => 'img-thumbnail rounded-circle')); ?>
                                    <?php printf(__('%s <span class="says d-none">says:</span>', 'decimus'), sprintf('<h3 class="h5">%s</h3>', get_comment_author_link())); ?>
                                </div>

                                <div class="small comment-meta text-muted mb-2">
                                    <time datetime="<?php comment_time('c'); ?>">
                                        <?php printf(_x('%1$s at %2$s', '1: date, 2: time', 'decimus'), get_comment_date(), get_comment_time()); ?>
                                    </time>
                                    <?php edit_comment_link(__('Edit', 'decimus'), '<span class="edit-link">', '</span>'); ?>
                                </div>


                                <?php if ('0' == $comment->comment_approved) : ?>
                                    <p class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'decimus'); ?></p>
                                <?php endif; ?>

                                <div class="card-block">
                                    <?php comment_text(); ?>
                                </div><!-- .comment-content -->

                                <?php comment_reply_link(
                                    array_merge(
                                        $args,
                                        array(
                                            'add_below' => 'div-comment',
                                            'depth'     => $depth,
                                            'max_depth' => $args['max_depth'],
                                            'before'     => '<footer class="reply comment-reply">',
                                            'after'     => '</footer><!-- .reply -->'
                                        )
                                    )
                                ); ?>
                            </div> <!-- card-body -->
                        </div><!-- card -->
                    </div><!-- .comment-content -->

                </article><!-- .comment-body -->
            </li><!-- #comment -->

<?php
        endif;
    }
endif; // ends check for decimus_comment()



// h2 Reply Title
add_filter('comment_form_defaults', 'decimus_custom_reply_title');
function decimus_custom_reply_title(array $defaults): array
{
    $defaults['title_reply_before'] = '<h2 id="reply-title" class="mt-4">';
    $defaults['title_reply_after'] = '</h2>';
    return $defaults;
}
// h2 Reply Title End



// Comment Cookie Checkbox
function decimus_change_comment_form_cookies_consent(array $fields): array
{
    $consent  = empty($commenter['comment_author_email']) ? '' : ' checked="checked"';
    $fields['cookies'] = '<p class="comment-form-cookies-consent custom-control form-check mb-3">' .
        '<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes" class="form-check-input"' . $consent . ' />' .
        '<label for="wp-comment-cookies-consent" class="form-check-label">' . __('Save my name, email, and website in this browser for the next time I comment.', 'decimus') . '</label>' .
        '</p>';
    return $fields;
}
add_filter('comment_form_default_fields', 'decimus_change_comment_form_cookies_consent');
// Comment Cookie Checkbox End



// Open comment author link in new tab
add_filter('get_comment_author_link', 'decimus_open_comment_author_link_in_new_window');
function decimus_open_comment_author_link_in_new_window(string $author_link): string
{
    return str_replace("<a", "<a target='_blank'", $author_link);
}
// Open comment author link in new tab End
