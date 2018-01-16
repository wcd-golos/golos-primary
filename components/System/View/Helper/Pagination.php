<?php

class System_View_Helper_Pagination
{
  // Config values
  protected $directory      = 'pagination'; /* currently relative to the helper dir */
  protected $style          = 'digg';
  protected $items_per_page = 10;
  protected $total_items    = 0;

  // Automatically generated values
  protected $current_page;
  protected $total_pages;
  protected $current_first_item;
  protected $current_last_item;
  protected $first_page;
  protected $last_page;
  protected $previous_page;
  protected $next_page;

  protected $view;
  //static protected $pagination;

  protected $urlOptions = array();
  protected $urlRoute = array();
  protected $urlReset = array();

  /**
   * add parameter urlQueryString in array $config to add it at the end of url
   * @author keeper
   * @var string|array
   */
  protected $urlQueryString = '';

  /**
   * function name for onclick event
   * @author keeper
   * @var string
   */
  protected $urlOnclick = 'loadPage';

  /**
   * initiate the pagination object
   *
   * @param array $config
   * @return object
   */
  public function pagination($config = array(), $route = null, $reset = false)
  {
    //if(isset(self::$pagination)) return self::$pagination;

    $this->initialize($config, $route, $reset);

    //Set to static instance
    //self::$pagination = $this;
    return $this;
  }

  /**
   * Sets or overwrites (some) config values.
   *
   * @param   array  configuration
   * @return  void
   */
  public function initialize($config = array(), $route = null, $reset = false)
  {
    // Assign config values to the object
    foreach ($config as $key => $value) {
        if (property_exists($this, $key)) {
            $this->$key = $value;
        } else {
            $this->urlOptions[$key] = $value;
        }
    }
    $this->urlRoute = $route;
    $this->urlReset = $reset;

    $page = Zend_Controller_Front::getInstance()->getRequest()->getParam('page');

    $this->directory = dirname(__FILE__).'/pagination/';

    // Core pagination values
    $this->total_items        = (int) max(0, $this->total_items);
    $this->items_per_page     = (int) max(1, $this->items_per_page);
    $this->total_pages        = (int) ceil($this->total_items / $this->items_per_page);
    if (!(isset($this->current_page) && $this->current_page)) $this->current_page = (int) min(max(1, $page), max(1, $this->total_pages));
    $this->current_first_item = (int) min((($this->current_page - 1) * $this->items_per_page) + 1, $this->total_items);
    $this->current_last_item  = (int) min($this->current_first_item + $this->items_per_page - 1, $this->total_items);

    // If there is no first/last/previous/next page, relative to the
    // current page, value is set to FALSE. Valid page number otherwise.
    $this->first_page         = ($this->current_page == 1) ? FALSE : 1;
    $this->last_page          = ($this->current_page >= $this->total_pages) ? FALSE : $this->total_pages;
    $this->previous_page      = ($this->current_page > 1) ? $this->current_page - 1 : FALSE;
    $this->next_page          = ($this->current_page < $this->total_pages) ? $this->current_page + 1 : FALSE;
  }

  /**
   * Generates the HTML for the chosen pagination style.
   *
   * @param   string  pagination style
   * @return  string  pagination html
   */
  public function create_links($style = NULL)
  {
    $style = (isset($style)) ? $style : $this->style;

    $view = new Zend_View();
    $view->addScriptPath($this->directory);

    $view->assign((array)get_object_vars($this));
    $view->objPagination = $this;

    return $view->render($style.'.phtml');
  }

  /**
   * Magically converts pagination object to string.
   *
   * @return  string  pagination html
   */
  public function __toString()
  {
    return $this->create_links();
  }

  /**
   * Magically gets a pagination variable.
   *
   * @param   string  variable key
   * @return  mixed   variable value if the key is found
   * @return  void    if the key is not found
   */
  public function __get($key)
  {
    if (isset($this->$key))
      return $this->$key;
  }


  /**
  * Generates an url given the name of a route.
  *
  * @access public
  *
  * @see    Zend_View_Helper::url()
  * @param  array $urlOptions Options passed to the assemble method of the Route object.
  * @param  mixed $name The name of a Route to use. If null it will use the current Route
  * @param  bool $reset Whether or not to reset the route defaults with those provided
  * @return string Url for the link href attribute.
  */
  public function url(array $urlOptions = array()/*, $name = null, $reset = false*/)
  {
    $urlOptions = array_merge($this->urlOptions, $urlOptions);
    //$url = $this->view->url($urlOptions, $name, $reset);
    $url = $this->view->url($urlOptions, $this->urlRoute, $this->urlReset);
    if ($this->urlQueryString) {
        if (is_array($this->urlQueryString)) $this->urlQueryString = http_build_query($this->urlQueryString, '', '&amp;');
        $url .= '?'.trim($this->urlQueryString, '?');
    }
    return $url;
  }

  /**
   * Set the current view object we are using pagination in...
   *
   * @param Zend_View_Interface $view
   */
  public function setView(Zend_View_Interface $view)
  {
      $this->view = $view;
  }
}