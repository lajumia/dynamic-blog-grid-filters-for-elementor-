<?php
class DBGFE_Dynamic_Blog_Grid extends \Elementor\Widget_Base {

    public function get_name(): string {
        return 'dynamic_blog_grid';
    }

    public function get_title(): string {
        return esc_html__( 'Dynamic Blog Grid', 'dbgfe' );
    }

    public function get_icon(): string {
        return 'eicon-posts-grid';
    }

    public function get_categories(): array {
        return [ 'basic' ];
    }

    public function get_keywords(): array {
        return [ 'blog', 'post', 'grid', 'filter', 'category', 'tag' ];
    }

    protected function register_controls(): void {

        // Content Tab Start
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Content', 'dbgfe' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Posts per page
        $this->add_control(
            'posts_per_page',
            [
                'label' => esc_html__( 'Posts Per Page', 'dbgfe' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 8,
                'min' => 1,
                'step' => 1,
            ]
        );

        // Columns
        $this->add_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'dbgfe' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    1 => '1',
                    2 => '2',
                    3 => '3',
                    4 => '4',
                ],
                'default' => 4,
            ]
        );

        // Categories filter
        $categories = get_terms([
            'taxonomy' => 'category',
            'hide_empty' => true,
        ]);
        $cats_options = [];
        foreach ( $categories as $cat ) {
            $cats_options[ $cat->term_id ] = $cat->name;
        }

        $this->add_control(
            'categories',
            [
                'label' => esc_html__( 'Filter by Categories', 'dbgfe' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $cats_options,
                'multiple' => true,
            ]
        );

        // Tags filter
        $tags = get_terms([
            'taxonomy' => 'post_tag',
            'hide_empty' => true,
        ]);
        $tags_options = [];
        foreach ( $tags as $tag ) {
            $tags_options[ $tag->term_id ] = $tag->name;
        }

        $this->add_control(
            'tags',
            [
                'label' => esc_html__( 'Filter by Tags', 'dbgfe' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $tags_options,
                'multiple' => true,
            ]
        );

        $this->end_controls_section();
        // Content Tab End

        // Style Tab Start
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'Grid', 'dbgfe' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'grid_gap',
            [
                'label' => esc_html__( 'Grid Gap (px)', 'dbgfe' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .dbgfe-blog-grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'dbgfe' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dbgfe-post-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
        // Style Tab End
    }

    protected function render(): void {
        $settings = $this->get_settings_for_display();

        // Prepare WP_Query args
        $args = [
            'post_type' => 'post',
            'posts_per_page' => $settings['posts_per_page'],
        ];

        if (!empty($settings['categories'])) {
            $args['category__in'] = $settings['categories'];
        }

        if (!empty($settings['tags'])) {
            $args['tag__in'] = $settings['tags'];
        }

        $query = new WP_Query($args);

        // Include template file
        $template_path = DBGFE_PATH . 'templates/dynamic-blog-grid.php';
        if (file_exists($template_path)) {
            include $template_path;
        }

        wp_reset_postdata();
    }


    protected function content_template(): void {
        $template_path = DBGFE_PATH . 'templates/dynamic-blog-grid-editor.php';
        if ( file_exists( $template_path ) ) {
            include $template_path;
        }
    }

}
