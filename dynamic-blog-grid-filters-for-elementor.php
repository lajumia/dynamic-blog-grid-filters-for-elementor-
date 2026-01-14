<?php
/**
 * Plugin Name: Elementor Dynamic Blog Grid & Filters 
 * Description: An Elementor addon to create dynamic, filterable blog grids with category & tag filters, pagination, and mobile off-canvas UI.
 * Plugin URI:  https://wpspeedpress.com/dynamic-blog-grid-filters-for-elementor/
 * Version:     1.0.0
 * Author:      Md Laju Miah
 * Author URI:  https://profiles.wordpress.org/devlaju/
 * Text Domain: dbgfe
 * License:     GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 6.0
 * Requires Plugins: elementor
 * Requires PHP: 7.4
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define constants
define( 'DBGFE_VERSION', '1.0.0' );
define( 'DBGFE_PATH', plugin_dir_path( __FILE__ ) );
define( 'DBGFE_URL', plugin_dir_url( __FILE__ ) );

/**
 * Check if Elementor is active
 */
function dbgfe_is_elementor_active() {
    return did_action( 'elementor/loaded' );
}

/**
 * Admin notice if Elementor is missing
 */
function dbgfe_admin_notice_missing_elementor() {
    if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
    }
    echo '<div class="notice notice-error"><p>';
    echo esc_html__( 'Dynamic Blog Grid & Filters requires Elementor to be installed and activated.', 'dbgfe' );
    echo '</p></div>';
}

/**
 * Init plugin
 */
function dbgfe_init_plugin() {

    if ( ! dbgfe_is_elementor_active() ) {
        add_action( 'admin_notices', 'dbgfe_admin_notice_missing_elementor' );
        return;
    }

    // Load text domain
    load_plugin_textdomain( 'dbgfe', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

    // Register assets
    add_action( 'wp_enqueue_scripts', 'dbgfe_register_assets' );

    // Register Elementor widget
    add_action( 'elementor/widgets/register', 'dbgfe_register_widgets' );
}
add_action( 'plugins_loaded', 'dbgfe_init_plugin' );

/**
 * Register CSS & JS
 */
function dbgfe_register_assets() {
    wp_enqueue_style(
        'dbgfe-style',
        DBGFE_URL . 'assets/css/dynamic-blog-grid-filters-for-elementor.css',
        [],
        DBGFE_VERSION
    );

    wp_enqueue_script(
        'dbgfe-script',
        DBGFE_URL . 'assets/js/dynamic-blog-grid-filters-for-elementor.js',
        [ 'jquery' ],
        DBGFE_VERSION,
        true
    );
    wp_localize_script(
        'dbgfe-script',
        'dbgfe_ajax', // this is the JS object
        [
            'ajax_url' => admin_url( 'admin-ajax.php' )
        ]
    );
}

/**
 * Register Elementor widgets
 */
function dbgfe_register_widgets( $widgets_manager ) {
    require_once DBGFE_PATH . 'widgets/class-dynamic-blog-grid-filters-for-elementor.php';

    $widgets_manager->register( new \DBGFE_Dynamic_Blog_Grid() );
}

add_action( 'wp_ajax_dbgfe_load_posts', 'dbgfe_load_posts' );
add_action( 'wp_ajax_nopriv_dbgfe_load_posts', 'dbgfe_load_posts' );

function dbgfe_load_posts() {

    $paged = isset($_POST['page']) ? absint($_POST['page']) : 1;

    $args = [
        'post_type'      => 'post',
        'posts_per_page' => 8,
        'post_status'    => 'publish',
        'paged'          => $paged,
    ];

    // Categories
    if ( ! empty($_POST['categories']) ) {
        $args['category__in'] = array_map(
            'absint',
            explode(',', $_POST['categories'])
        );
    }

    // Tags
    if ( ! empty($_POST['tags']) ) {
        $args['tag__in'] = array_map(
            'absint',
            explode(',', $_POST['tags'])
        );
    }

    $query = new WP_Query( $args );

    // ------------------
    // POSTS HTML
    // ------------------
    ob_start();

    if ( $query->have_posts() ) :
        while ( $query->have_posts() ) : $query->the_post(); ?>
            <div class="blog-card">
            
                <a href="<?php the_permalink(); ?>">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'medium', [
                                'alt'   => esc_attr( get_the_title() ),
                                'style' => 'width:100%; height:100px; object-fit:cover;',
                            ] ); ?>
                    <?php else : ?>
                        <img src="<?php echo DBGFE_URL . '/assets/img/image-not-found.jpg' ?>" alt="Image not found!">
                    <?php endif; ?>
                </a>

                <div class="blog-content">
                    <h3><?php the_title(); ?></h3>

                    <p>
                        <?php echo wp_trim_words( get_the_excerpt(), 3 ); ?>
                    </p>
                    

                    <a href="<?php the_permalink(); ?>" class="read-more">
                        <?php esc_html_e( 'Read More →', 'dbgfe' ); ?>
                    </a>
                </div>

            </div>
        <?php endwhile;
    else :
        echo '<p>No posts found.</p>';
    endif;

    wp_reset_postdata();

    $posts_html = ob_get_clean();

    // ------------------
    // PAGINATION HTML
    // ------------------
    ob_start();

    if ( $query->max_num_pages > 1 ) : ?>
        <div class="pagination" id="dbgfe-pagination">

            <a
                href="<?php echo esc_url( get_pagenum_link( max(1, $paged - 1) ) ); ?>"
                class="page-prev <?php echo ( $paged == 1 ) ? 'disabled' : ''; ?>"
                data-page="<?php echo max(1, $paged - 1); ?>"
            >«</a>

            <?php for ( $i = 1; $i <= $query->max_num_pages; $i++ ) : ?>
                <a
                    href="<?php echo esc_url( get_pagenum_link( $i ) ); ?>"
                    class="page-number <?php echo ( $i == $paged ) ? 'active' : ''; ?>"
                    data-page="<?php echo esc_attr( $i ); ?>"
                >
                    <?php echo esc_html( $i ); ?>
                </a>
            <?php endfor; ?>

            <a
                href="<?php echo esc_url( get_pagenum_link( min($query->max_num_pages, $paged + 1) ) ); ?>"
                class="page-next <?php echo ( $paged == $query->max_num_pages ) ? 'disabled' : ''; ?>"
                data-page="<?php echo min($query->max_num_pages, $paged + 1); ?>"
            >»</a>

        </div>
    <?php endif;

    $pagination_html = ob_get_clean();

    wp_send_json([
        'posts'      => $posts_html,
        'pagination' => $pagination_html,
    ]);
}



add_filter(
    'plugin_row_meta',
    function ( $links, $file ) {

        if ( plugin_basename( __FILE__ ) !== $file ) {
            return $links;
        }

        $row_meta = [
            'docs' => '<a href="https://wpspeedpress.com/dynamic-blog-grid-filters-for-elementor/" target="_blank">' . esc_html__( 'Docs & FAQs', 'dbgfe' ) . '</a>',
            'videos' => '<a href="https://www.youtube.com/@speedpress_for_wp" target="_blank">' . esc_html__( 'Video Tutorials', 'dbgfe' ) . '</a>',
        ];

        return array_merge( $links, $row_meta );
    },
    10,
    2
);




