<?php

// Auth controller
class AuthController extends Controller {

	function index() {
        $this->doNotRenderHeader = 1;
	}
        
    function login() {
        $this->doNotRenderHeader = 1;
        $this->render = 0;
        
        if($_POST['email'] && $_POST['password']) {
            $partnerName = 'applicant';
            $partnerPassword = 'd7c3119c6cdab02d68d9';
            $partnerUserID = urlencode(htmlspecialchars($_POST['email']));
            $partnerUserSecret = urlencode(htmlspecialchars($_POST['password']));
            
            $authUrl = 'https://api.expensify.com?command=Authenticate'
                 . '&partnerName=' . $partnerName
                 . '&partnerPassword=' . $partnerPassword
                 . '&partnerUserID=' . $partnerUserID
                 . '&partnerUserSecret=' . $partnerUserSecret
                 . '&useExpensifyLogin=true';
            
            echo json_encode($this->request->buildResponse($this->request->sendRequest($authUrl)));
        }
	}

}
