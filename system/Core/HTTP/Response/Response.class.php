<?php
namespace Flush\Core\HTTP\Response;

class Response 
{
	protected $status;
    protected $data;
    protected $protocol = 'HTTP/1.1';
    protected $contentType = 'text/html';
    
    public function __construct($status = 200) {
        $this->status = $status;
    }
    
    public function setStatus($status) {
    	$this->status = $status;
    }
    
    public function setContentType($contentType) {
    	$this->contentType = $contentType;
    }
    
    public function setData($data) {
    	$this->data = $data;
    }
    
    public function renderPage(){
    	foreach ($this->headers as $header) {
    		$this->sendHeader($header);
    	}
    	if ($jem) {
    		switch($j){
    		    case 'a':
    		        $this->workHard();
    		    case 'f':
    		    	$this->work();
    		}
    	}
    }
    
    
}
?>