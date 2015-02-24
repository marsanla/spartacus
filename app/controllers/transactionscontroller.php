<?php

class TransactionsController extends Controller {
    
    function index() {
        $this->doNotRenderHeader = 1;
	}
    
	function viewAll() {
	   $this->doNotRenderHeader = 1;
        
        if($_POST['authToken']) {
            $partnerName = 'applicant';
            $authToken = $_POST['authToken'];
            
            $getUrl = 'https://api.expensify.com?command=Get'
                 . '&partnerName=' . $partnerName
                 . '&authToken=' . $authToken
                 . '&returnValueList=transactionList';
            
            $data = $this->request->buildResponse($this->request->sendRequest($getUrl));
            $this->set('transactions', $data['value']->transactionList);
        }
	}
	
	function add() {
        $this->doNotRenderHeader = 1;
        $this->render = 0;
        
        if($_POST['authToken'] && $_POST['created'] && $_POST['amount'] && $_POST['currency'] && $_POST['merchant']) {
            $authToken = $_POST['authToken'];
            $created = $_POST['created'];
            $amount = $_POST['amount'];
            $currency = $_POST['currency'];
            $merchant = $_POST['merchant'];
            
            $addUrl = 'https://api.expensify.com?command=CreateTransaction'
                . '&authToken=' . $authToken
                . '&created=' . $created
                . '&amount=' . $amount
                . '&currency=' . $currency
                . '&merchant=' . urlencode(htmlspecialchars($merchant));

            echo json_encode($this->request->buildResponse($this->request->sendRequest($addUrl)));
        }
	}
}
