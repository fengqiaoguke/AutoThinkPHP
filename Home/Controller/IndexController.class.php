<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\ToolModel;
use Home\Model\UserModel;
use Home\Model\Fqj_userModel;
class IndexController extends Controller {
    
  public function index(){
      $tool = new ToolModel();
      $tool->run();
  }
}