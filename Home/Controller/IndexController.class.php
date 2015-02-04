<?php
namespace Home\Controller;

use Think\Controller;
use Home\Model\ToolModel;
 
class IndexController extends Controller
{

    public function _initialize()
    {
        $fun = '_'.strtolower(REQUEST_METHOD);
        $this->$fun();
    }

    public function index()
    {
        $tool = new ToolModel();
        // $tool->run();
    
    }
    public function _get(){
        echo 'get';
    }
    
    public function _post(){
        echo 'post';
    }
    
    public function _put(){
        echo 'put';
    }
    public function _delete(){
        echo 'delete';
    }
}