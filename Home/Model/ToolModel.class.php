<?php
namespace Home\Model;

use Think\Model;

class ToolModel extends Model
{

    public function run()
    {
        $this->_makeModel('abc');
    }

    private function _listTable()
    {
        $sql = "show tables";
        $rs = $this->query($sql);
        foreach ($rs as $v) {
            $tables[] = $v;
        }
        return $tables;
    }

    private function _makeModel($table)
    {
        $table = ucfirst(strtolower($table));
        $context = '
<?php
namespace Home\Model;

use Think\Model;

class ' . $table . 'Model extends Model
{
    
}
    ';
        $filepath = '/Home/Model/' . $table . 'Model.class.php';
        $this->_build($filepath, $context);
    }

    private function _build($filepath, $context)
    {
        $filepath = APP_PATH . '/' . $filepath;
        $filepath = str_replace("//", "/", $filepath);
        if (! file_exists(dirname($filepath))) {
            $this->_mkdir(dirname($filepath));
        }
        file_put_contents($filepath, $context);
    }

    private function _error($msg)
    {
        echo $msg;
        exit();
    }

    private function _mkdir($dir, $mode = 0777)
    {
        if (is_dir($dir) || mkdir($dir, $mode))
            return true;
        if (! $this->_mkdir(dirname($dir), $mode))
            return false;
        return mkdir($dir, $mode);
    }
}