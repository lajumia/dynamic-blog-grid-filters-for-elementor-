<?php // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<button class="mobile-filter-btn" onclick="toggleFilter()">☰ <span class="badge" id="filterCount">0</span></button>

<div class="overlay" id="overlay" onclick="closeFilter()"></div>

<div class="blog-wrapper">

    <?php 
        if ( 'yes' === $settings['enable_sidebar'] ) {
    ?>
    <aside class="sidebar" id="sidebar">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
            <h3 style="margin:0">Filter</h3>
            <button id="clearFilters" style="background:none;border:none;font-size:13px;cursor:pointer;padding: 16px 8px;" data-postPerPage = <?php echo esc_attr( $settings['posts_per_page'] ); ?>>Clear all</button>
        </div>

        <?php
        $dbgfe_categories = get_categories( [
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => true,
        ] );
        ?>

        <?php 
        if ( 'yes' === $settings['enable_category_filter'] ) {
        ?>

        <div class="filter-group">
            <h4><?php esc_html_e( 'Category', 'dynamic-blog-grid-filters-for-elementor' ); ?></h4>

            <div class="filter-search">
                <input 
                    style="width:100%;padding:15px;"
                    type="text"
                    class="search-category"
                    placeholder="<?php esc_attr_e( 'Search category', 'dynamic-blog-grid-filters-for-elementor' ); ?>"
                    onkeyup="filterList(this)"
                    data-postPerPage = <?php echo esc_attr( $settings['posts_per_page'] ); ?>
                >
            </div>

            <div class="filter-list">
                <?php foreach ( $dbgfe_categories as $dbgfe_category ) : ?>
                    <label>
                        <input 
                            type="checkbox"
                            class="dbgfe-category-filter filter-checkbox"
                            value="<?php echo esc_attr( $dbgfe_category->term_id ); ?>"
                            data-postperpage = <?php echo esc_attr( $settings['posts_per_page'] ); ?>
                        >
                        <span class="filter-name">
                            <?php echo esc_html( $dbgfe_category->name ); ?>
                        </span>
                        <span class="filter-count" style="margin-left:auto;color:#999;font-size:12px">
                            <?php echo esc_html( $dbgfe_category->count ); ?>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <?php 
        }
        ?>
        

        <?php
        $dbgfe_tags = get_tags( [
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => true,
        ] );
        ?>

        <?php 
        if ( 'yes' === $settings['enable_tags_filter'] ) {
        ?>

        <div class="filter-group">
            <h4><?php esc_html_e( 'Tags', 'dynamic-blog-grid-filters-for-elementor' ); ?></h4>

            <div class="filter-search">
                <input 
                    style="width:100%;padding:15px;"
                    class="search-tag"
                    type="text"
                    placeholder="<?php esc_attr_e( 'Search tag', 'dynamic-blog-grid-filters-for-elementor' ); ?>"
                    onkeyup="filterList(this)"
                >
            </div>

            <div class="filter-list">
                <?php foreach ( $dbgfe_tags as $dbgfe_tag ) : ?>
                    <label>
                        <input 
                            type="checkbox"
                            class="dbgfe-tag-filter filter-checkbox"
                            value="<?php echo esc_attr( $dbgfe_tag->term_id ); ?>"
                           data-postperpage="<?php echo esc_attr( absint( $settings['posts_per_page'] ) ); ?>"


                        >
                        <span class="filter-name">
                            <?php echo esc_html( $dbgfe_tag->name ); ?>
                        </span>
                        <span class="filter-count" style="margin-left:auto;color:#999;font-size:12px">
                            <?php echo esc_html( $dbgfe_tag->count ); ?>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <?php 
        }
        ?>

    </aside>

    <?php 
        }
    ?>

    <section class="blog-section">

        <div class="blog-grid" style="grid-template-columns: repeat(<?php echo esc_attr( $settings['columns'] );?>, 1fr);" id="blogGrid">

            <?php

            $dbgfe_post_per_page = $settings['posts_per_page'] ;
       
            $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

           $dbgfe_args = [
                'post_type'      => 'post',
                'posts_per_page' => $dbgfe_post_per_page,
                'post_status'    => 'publish',
                'paged'          => $paged,
            ];


            $dbgfe__query = new WP_Query( $dbgfe_args );

            if ( $dbgfe__query->have_posts() ) :
                while ( $dbgfe__query->have_posts() ) : $dbgfe__query->the_post();
            ?>
            <div class="blog-card">
            
                <a href="<?php the_permalink(); ?>">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'medium', [
                                'alt'   => esc_attr( get_the_title() ),
                                'style' => 'width:100%; height:100px; object-fit:cover;',
                            ] ); ?>
                    <?php else : ?>
                        <img src="<?php echo esc_url( DBGFE_URL . 'assets/img/image-not-found.jpg' ); ?>" alt="<?php esc_attr_e( 'Image not found', 'dynamic-blog-grid-filters-for-elementor' ); ?>">
                    <?php endif; ?>
                </a>

                <div class="blog-content">
                    <h3><?php the_title(); ?></h3>

                    <p>
                        <?php echo esc_html( wp_trim_words( get_the_excerpt(), 3 ) ); ?>
                    </p>

                    <p>
                    <a href="<?php the_permalink(); ?>" class="read-more">
                        <?php esc_html_e( 'Read More →', 'dynamic-blog-grid-filters-for-elementor' ); ?>
                    </a>
                </div>

            </div>

        <?php
            endwhile;
            wp_reset_postdata();
        else :
        ?>
            <p><?php esc_html_e( 'No posts found.', 'dynamic-blog-grid-filters-for-elementor' ); ?></p>
        <?php endif; ?>

        </div>


<?php if ( $dbgfe__query->max_num_pages > 1 ) : ?>
<div class="pagination" id="dbgfe-pagination">

    <!-- Previous button -->
    <a 
        href="#"
        class="page-prev <?php echo ( $paged == 1 ) ? 'disabled' : ''; ?>"
        style="display: <?php echo ( $paged == 1 ) ? 'none' : 'block'; ?>"
        data-page="<?php echo esc_attr(max(1, $paged - 1)); ?>"
        data-posts-per-page="<?php echo esc_attr($dbgfe_post_per_page); ?>"
        
    >
        «
    </a>

    <!-- Page numbers -->
    <?php for ( $dbgfe_i = 1; $dbgfe_i <= $dbgfe__query->max_num_pages; $dbgfe_i++ ) : ?>
        <a 
            href="<?php echo esc_url( get_pagenum_link( $dbgfe_i ) ); ?>"
            class="page-number <?php echo ( $dbgfe_i == $paged ) ? 'active' : ''; ?>"
            data-page="<?php echo esc_attr( $dbgfe_i ); ?>"
            data-posts-per-page="<?php echo esc_attr($dbgfe_post_per_page); ?>"
            
        >
            <?php echo esc_html( $dbgfe_i ); ?>
        </a>
    <?php endfor; ?>

    <!-- Next button -->
    <a 
        href="<?php echo esc_url( get_pagenum_link( min($dbgfe__query->max_num_pages, $paged + 1) ) ); ?>"
        class="page-next <?php echo ( $paged == $dbgfe__query->max_num_pages ) ? 'disabled' : ''; ?>"
        data-page="<?php echo esc_attr(min($dbgfe__query->max_num_pages, $paged + 1)); ?>"
        data-postsPerPage="<?php echo esc_attr($post_per_page); ?>"
    >
        »
    </a>

</div>
<?php endif; ?>




    </section>
</div>
