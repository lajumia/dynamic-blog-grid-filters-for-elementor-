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
        $this->add_control(
        'enable_category_filter',
        [
            'label'        => esc_html__( 'Enable Category Filter', 'dbgfe' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'dbgfe' ),
            'label_off'    => esc_html__( 'No', 'dbgfe' ),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]
        );


        // Tags filter
        $this->add_control(
        'enable_tags_filter',
        [
            'label'        => esc_html__( 'Enable Tags Filter', 'dbgfe' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'dbgfe' ),
            'label_off'    => esc_html__( 'No', 'dbgfe' ),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]
        );

        // Enable/Disable Sidebar
        $this->add_control(
        'enable_sidebar',
        [
            'label'        => esc_html__( 'Enable Sidebar', 'dbgfe' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'dbgfe' ),
            'label_off'    => esc_html__( 'No', 'dbgfe' ),
            'return_value' => 'yes',
            'default'      => 'yes',
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
                    '{{WRAPPER}} .blog-grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'color_template',
            [
                'label' => esc_html__( 'Color Template', 'dbgfe' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pagination a.active, {{WRAPPER}} .pagination a:hover' => '
                    background-color: {{VALUE}};
                    border-color: {{VALUE}};
                    ',
                    '{{WRAPPER}} .read-more'  => 'color: {{VALUE}};',
                    '{{WRAPPER}} .filter-group input::before'  => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .mobile-filter-btn'  => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} #clearFilters'  => 'color: {{VALUE}};',
                    '{{WRAPPER}} .search-category, {{WRAPPER}} .search-tag,{{WRAPPER}} .filter-checkbox' => '
                    border-color:{{VALUE}};
                    ',
                    
                    
                ],
            ]
        );

        $this->add_control(
            'taxonomy-hover',
            [
                'label' => esc_html__( 'Taxonomy Hover', 'dbgfe' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .filter-group label:hover'  => 'background-color: {{VALUE}};',
                    
                    
                ],
            ]
        );
        $this->end_controls_section();
        // Style Tab End
    }

    protected function render(): void {
        $settings = $this->get_settings_for_display();

        // Include template file
        $template_path = DBGFE_PATH . 'templates/dynamic-blog-grid.php';
        if (file_exists($template_path)) {
            include $template_path;
        }

        wp_reset_postdata();
    }

}
