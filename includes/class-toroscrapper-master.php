<?php 
class TOROSCRAPPER_Master {
    protected $cargador;
    protected $theme_name;
    protected $version;
    public function __construct() {
        $this->theme_name = 'TOROSCRAPPER_Theme';
        $this->version = TOROSCRAPPER_VERSION;
        $this->cargar_dependencias();
        $this->cargar_instancias();
        $this->definir_admin_hooks();
        $this->definir_public_hooks();
    }
    private function cargar_dependencias() {

        require_once TOROSCRAPPER_DIR_PATH . 'includes/class-toroscrapper-cargador.php';        

        require_once TOROSCRAPPER_DIR_PATH . 'includes/class-toroscrapper-build-menupage.php';

        require_once TOROSCRAPPER_DIR_PATH . 'admin/class-toroscrapper-admin.php';

        require_once TOROSCRAPPER_DIR_PATH . 'public/class-toroscrapper-public.php';

        require_once TOROSCRAPPER_DIR_PATH . 'includes/class-toroscrapper-ajax-admin.php';
       
    }
    private function cargar_instancias() {
        $this->cargador      = new TOROSCRAPPER_Cargador;
        $this->ajax_admin    = new TOROSCRAPPER_Ajax_Admin;
        $this->toroscrapper_admin  = new TOROSCRAPPER_Admin( $this->get_theme_name(), $this->get_version() );
        $this->toroscrapper_public = new TOROSCRAPPER_Public( $this->get_theme_name(), $this->get_version() );
    }
    private function definir_admin_hooks() {
        $this->cargador->add_action( 'admin_enqueue_scripts', $this->toroscrapper_admin, 'enqueue_styles' );
        $this->cargador->add_action( 'admin_enqueue_scripts', $this->toroscrapper_admin, 'enqueue_scripts' );
        $this->cargador->add_action( 'admin_menu', $this->toroscrapper_admin, 'add_menu' );

        $this->cargador->add_action('wp_ajax_action_add_scrapper', $this->ajax_admin, 'add_scrapper');		  
        $this->cargador->add_action('wp_ajax_action_add_movie', $this->ajax_admin, 'add_movie');		  
        $this->cargador->add_action('wp_ajax_action_add_serie', $this->ajax_admin, 'add_serie');		  
        $this->cargador->add_action('wp_ajax_action_insert_terms', $this->ajax_admin, 'insert_terms');		  
        $this->cargador->add_action('wp_ajax_action_scrapper_all', $this->ajax_admin, 'scrapper_all');		  
    }
    private function definir_public_hooks() {
        $this->cargador->add_action( 'wp_enqueue_scripts', $this->toroscrapper_public, 'enqueue_styles' );
        $this->cargador->add_action( 'wp_footer', $this->toroscrapper_public, 'enqueue_scripts' );
    }
    public function run() {
        $this->cargador->run();
    }
    public function get_theme_name() {
        return $this->theme_name;
    }
    public function get_cargador() {
        return $this->cargador;
    }
    public function get_version() {
        return $this->version;
    }
}