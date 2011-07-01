<?php
/**
 * Page_Framework.php
 *
 * Class used for loading and displaying pages from the default template.
 *
 * @author Sean McGary <sean.mcgary@gmail.com>
 */
class lib_libraries_pageFramework
{

    public $load;
    public $javascript = array();
    public $css = array();
    public $header_data = array();
    public $pre_css = array();

    public function  __construct()
    {
        $this->load = core_loadFactory::get_inst('core_load', 'load');
    }

    public function load_javascript($source)
    {
        $this->javascript[] = $source;
    }

    public function load_css($source)
    {
        $this->css[] = $source;
    }

    public function load_pre_css($source)
    {
        $this->pre_css[] = $source;
    }

    public function generate_leftCol_default()
    {
        $data = array();


        return $data;
    }

    public function set_header_data($data)
    {
        $this->header_data = array_merge($this->header_data, $data);
    }

    /**
     *
     * @param string $main_page     The main view file to load
     * @param array $data           Data to load. Each page has an index.
     *                                  eg: $data['header'] is for the header
     * @param string $other_col     The other view file to load
     */
    public function render($rightCol, $rightCol_data, $leftCol = null, $leftCol_data = array())
    {
        $header_data = array();
        $header_data['javascript'] = $this->javascript;
        $header_data['css'] = $this->css;
        $header_data['pre_css'] = $this->pre_css;

        $header_data = array_merge($this->header_data, $header_data);
        
        // load header
        $this->load->view('template/header_view', $header_data);

        if($leftCol != null)
        {

            $this->load->view($leftCol, $leftCol_data);
        }
        else
        {
            $this->load->view('template/leftCol_view_empty', array());
        }

        $this->load->view($rightCol, $rightCol_data);


        $this->load->view('template/footer_view');
        
    }
}
?>
