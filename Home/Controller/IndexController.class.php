<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\ToolModel;
class IndexController extends Controller {
    
  public function index(){
      $tool = new ToolModel();
      $tool->run();
  }
   
}