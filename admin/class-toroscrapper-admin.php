<?php 
class TOROSCRAPPER_Admin {
    private $theme_name;
    private $version;
    private $build_menupage;
    
    public function __construct( $theme_name, $version ) {
        $this->theme_name     = $theme_name;
        $this->version        = $version;
        $this->build_menupage = new TOROSCRAPPER_Build_Menupage();
    }
    
    public function enqueue_styles( $hook ) {
        if(isset($_GET['page'])){
            if( $hook == 'toplevel_page_demo_torothemes' or $_GET['page'] == 'demo_comment' or $_GET['page'] == 'demo_add_movies' or $_GET['page'] == 'demo_add_series' or $_GET['page'] == 'demo_add_seasons' ) {
                wp_enqueue_style( 'materialize_admin_css', 'https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css', array(), $this->version, 'all' );
                wp_enqueue_style( 'materialize_icon', 'https://fonts.googleapis.com/icon?family=Material+Icons', array(), $this->version, 'all' );
            }
        }   
    }
    public function enqueue_scripts( $hook ) {
        wp_enqueue_script( 'materialize_admin_js', 'https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js', [ 'jquery' ], $this->version, true );

        wp_enqueue_script( 'function_admin_js', TOROSCRAPPER_DIR_URI . 'admin/js/toroscrapper_admin.js', [ 'jquery' ], $this->version, true );

        $toroscrapper_Admin = [
            'url'   => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'toroscrapper_seg' ),
        ];
        wp_localize_script( 'function_admin_js', 'toroscrapper_Admin', $toroscrapper_Admin );
    }


    public function add_menu() {
        $this->build_menupage->add_menu_page(
            __( 'ToroScrapper', 'toroscrapper' ),
            __( 'ToroScrapper', 'toroscrapper' ),
            'manage_options',
            'demo_torothemes',
            [ $this, 'demo_torothemes' ]
        );

        $this->build_menupage->add_submenu_page(
            'demo_torothemes',
            __( 'Players', 'toroscrapper' ),
            __( 'Players', 'toroscrapper' ),
            'manage_options',
            'demo_torothemes',
            [ $this, 'demo_torothemes' ]
        );

        $this->build_menupage->add_submenu_page(
            'demo_torothemes',
            __( 'Add Movies', 'toroscrapper' ),
            __( 'Add Movies', 'toroscrapper' ),
            'manage_options',
            'demo_add_movies',
            [ $this, 'demo_add_movies' ]
        );

        $this->build_menupage->add_submenu_page(
            'demo_torothemes',
            __( 'Add Series', 'toroscrapper' ),
            __( 'Add Series', 'toroscrapper' ),
            'manage_options',
            'demo_add_series',
            [ $this, 'demo_add_series' ]
        );

        $this->build_menupage->add_submenu_page(
            'demo_torothemes',
            __( 'Add Seasons and Episodes', 'toroscrapper' ),
            __( 'Add Seasons and Episodes', 'toroscrapper' ),
            'manage_options',
            'demo_add_seasons',
            [ $this, 'demo_add_seasons' ]
        );

        $this->build_menupage->add_submenu_page(
            'demo_torothemes',
            __( 'Comments', 'toroscrapper' ),
            __( 'Comments', 'toroscrapper' ),
            'manage_options',
            'demo_comment',
            [ $this, 'demo_comment' ]
        );
        $this->build_menupage->run();
    }
    
    public function demo_torothemes(){
        require_once TOROSCRAPPER_DIR_PATH . 'admin/partials/toroscrapper_demo.php';
    }

    public function demo_comment(){
        require_once TOROSCRAPPER_DIR_PATH . 'admin/partials/toroscrapper_demo_comment.php';
    }
    public function demo_add_movies(){
        require_once TOROSCRAPPER_DIR_PATH . 'admin/partials/toroscrapper_demo_add_movies.php';
    }
    public function demo_add_series(){
        require_once TOROSCRAPPER_DIR_PATH . 'admin/partials/toroscrapper_demo_add_series.php';
    }
    public function demo_add_seasons(){
        require_once TOROSCRAPPER_DIR_PATH . 'admin/partials/toroscrapper_demo_add_seasons.php';
    }
}