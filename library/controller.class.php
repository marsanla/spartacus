<?php
class Controller {

	protected $_controller;
	protected $_action;
	protected $_template;
    
    public $doNotRenderHeader;
	public $render;
    public $request;

    // Create contructor
	function __construct($controller, $action) {
        global $inflect;

		$this->_controller = ucfirst($controller);
		$this->_action = $action;
		
		$model = ucfirst($inflect->singularize($controller));
		$this->doNotRenderHeader = 0;
		$this->render = 1;
        if (file_exists(ROOT . DS . 'app' . DS . 'models' . DS . $model . '.php')) {
            $this->$model = new $model;
        }
		$this->_template = new Template($controller,$action);
        
        $this->request = new Request();
	}

    // Set variables
	function set($name,$value) {
		$this->_template->set($name,$value);
	}

    // Render the template
	function __destruct() {
        if ($this->render) {
			$this->_template->render($this->doNotRenderHeader);
		}
	}

}
