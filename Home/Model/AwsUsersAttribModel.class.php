<?php
namespace Home\Model;

use Think\Model;

class AwsUsersAttribModel extends Model
{

    protected $trueTableName = "aws_users_attrib";

    /**
     * 搜索
     *
     * @param string $where
     *            查询条件
     * @param number $page
     *            要查询的页数
     * @param string $order
     *            排序
     * @param string $options
     *            其它选项 expire:缓存失效时间;num:每页显示条数;page_html:是否输出分页代码;debug:调试模式
     * @return array $result 返回搜索结果
     */
    public function search($where = "1", $page = 1, $order = "id desc", $options = null)
    {
        $page = intval($page) < 1 ? 1 : $page;
        $num = isset($options["num"]) ? intval($options["num"]) : 10;
        $cacheTime = isset($options["expire"]) ? $options["expire"] : C("DATA_CACHE_TIME");
        
        // 读取缓存
        $cacheName = md5($this->trueTableName . "_where:" . $where . ";page:" . $page . ";order:" . $order . ";num:" . $num);
        $result = S($cacheName);
        if ($result) {
            return $result;
        }
        
        $result["list"] = $this->field("id")
            ->where($where)
            ->page($page, $num)
            ->order($order)
            ->select();
        if($options["debug"]){
            echo $this->_sql()."<br>";
        }
        foreach ($result["list"] as $k => $v) {
            $result["list"][$k] = $this->getInfo($v["id"]);
        }
        $result["total_num"] = $this->where($where)->count();
        if ($options["page_html"]) {
            $result["page"] = page_html($result["total_num"], $page, $num);
        }
        $result["total_page"] = ceil($result["total_num"] / $num);
        if ($result["total_page"] > $page) {
            $result["next_page"] = $page + 1;
        } else {
            $result["next_page"] = "";
        }
        if ($page > 1) {
            $result["prev_page"] = $page - 1;
        } else {
            $result["prev_page"] = "";
        }
        
        S($cacheName, $result, $cacheTime);
        return $result;
    }

    /**
     * 获取单条信息
     *
     * @param number $id            
     * @param string $options
     *            选项 expire:缓存失效时间;
     * @return array $result 返回结果
     */
    public function getInfo($id, $options = null)
    {
        $id = intval($id);
        $cacheTime = isset($options["expire"]) ? $options["expire"] : C("DATA_CACHE_TIME");
        if (! $id) {
            return false;
        }
        // 读取缓存
        $cacheName = md5($this->trueTableName . "_" . $id);
        $result = S($cacheName);
        if ($result) {
            return $result;
        }
        $result = $this->find($id);
        /*
         * 可以在这组合数据然后缓存起来,以后每次读取内容都走此方法;
         * 当内容更新时候会自动清缓存,否则等待缓存自动过期
         */
        S($cacheName, $result, $cacheTime);
        
        return $result;
    }

    /**
     * 添加数据
     * 
     * @param array $data            
     * @return number $id 返回自增id
     */
    public function addInfo($data)
    {
        /*
         * 这里可以放预处理数据e.g.
         * $data["createtime"] = time();
         * unset($data["id"]);
         */
        $id = $this->add($data);
        return $id;
    }

    /**
     * 删除数据
     * 
     * @param number $id            
     * @return boolean
     */
    public function deleteInfo($id)
    {
        /*
         * 这里可以数据验证,比如验证子类是否还有数据
         *
         */
        $where = "id=" . intval($id);
        $id = $this->where($where)->delete();
        
        //删除缓存
        $cacheName = md5($this->trueTableName . "_" . $id);
        S($cacheName,NULL);
        
        return $id;
    }
    
    /**
     * 更新数据
     * @param number $id
     * @param array $data
     * @return boolean
     */
    public function updateInfo($id,$data){
        $id = intval($id);
        if(!$id){
            return false;
        }
        unset($data["id"]);
        $where = "id=" . intval($id);
        $result = $this->where($where)->save($data);
        
        //删除缓存
        $cacheName = md5($this->trueTableName . "_" . $id);
        S($cacheName,NULL);
        
        return $result;
    }
}
        
            
    