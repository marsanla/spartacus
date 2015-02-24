<?php

// Home controller
class HomeController extends Controller {

	function index() {
        echo phpversion();
		$this->set('title', 'Expensiwork - Marcos Sanz Latorre');
	}

}
