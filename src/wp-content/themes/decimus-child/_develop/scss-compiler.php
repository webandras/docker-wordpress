<?php

/**
 * Functions to compile scss in the theme itself
 *
 * @package decimus
 */

require_once '/var/www/html/vendor/scssphp/scssphp/scss.inc.php';

use ScssPhp\ScssPhp\Compiler;

/**
 * Compiles the scss to a css file to be read by the browser.
 */
function decimus_compile_scss(): void
{
    $compiler = new Compiler();
    $compiler->setSourceMap(Compiler::SOURCE_MAP_FILE);

    $compiler->setOutputStyle(\ScssPhp\ScssPhp\OutputStyle::COMPRESSED);

    if ( decimus_child_has_scss() && is_child_theme() ) {
        $theme_directory = get_stylesheet_directory();
    } else {
        $theme_directory = get_template_directory();
    }

    $scss_file = $theme_directory . '/scss/main.scss';
    $css_file = $theme_directory . '/css/main.css';

    $compiler->setImportPaths(dirname($scss_file));
    if ( is_child_theme() && decimus_child_has_scss() ) {
        $compiler->addImportPath(get_template_directory() . '/scss/');
    }

    $last_modified = decimus_get_last_modified_scss($theme_directory);
    $stored_modified = get_theme_mod('decimus_scss_modified_timestamp', 0);

    $is_environment_dev = (wp_get_environment_type() === 'development');

    if ( $is_environment_dev ) {
        $compiler->setSourceMapOptions([
            'sourceMapURL' => site_url('', 'relative') . 'scss-compiler.php/' . substr(str_replace(ABSPATH, '', $css_file), 0, -3) . 'map',
            'sourceMapBasepath' => substr(str_replace('\\', '/', ABSPATH), 0, -1),
            'sourceRoot' => site_url('', 'relative') . 'scss-compiler.php/',
        ]);
    }

    try {
        if ( $last_modified > $stored_modified || !file_exists($css_file) || $is_environment_dev ) {
            $compiled = $compiler->compileString(file_get_contents($scss_file));

            if ( !file_exists(dirname($css_file)) ) {
                mkdir(dirname($css_file), 0755, true);
            }

            file_put_contents($css_file, $compiled->getCss());
            if ( $is_environment_dev ) {
                file_put_contents(substr($css_file, 0, -3) . 'map', $compiled->getSourceMap());
            }

            set_theme_mod('decimus_scss_modified_timestamp', $last_modified);
        }
    } catch (Exception $e) {
        wp_die('<b>decimus SCSS Compiler - Caught exception:</b><br><br> ' . $e->getMessage());
    }
}


/**
 * Checks if the scss files and returns the last modified times added together.
 *
 * @return float Last modified times added together.
 */
function decimus_get_last_modified_scss(string $theme_directory)
{
    $directory = $theme_directory . '/scss/';
    $files = scandir($directory);
    $total_last_modified = 0;
    foreach ($files as $file) {
        if ( strpos($file, '.scss') !== false || strpos($file, '.css') !== false ) {
            $file_stats = stat($directory . $file);
            $total_last_modified += $file_stats['mtime'];
        }
    }
    //$total_last_modified += stat(get_template_directory() . '/scss/bootstrap/bootstrap.scss')['mtime'];
    return $total_last_modified;
}

/**
 * Check if the child theme has scss files included.
 *
 * @return boolean True when child theme has scss files.
 */
function decimus_child_has_scss(): bool
{
    return file_exists(get_stylesheet_directory() . '/scss/main.scss');
}
