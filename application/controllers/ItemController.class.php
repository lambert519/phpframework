<?php
/**
 * Created by PhpStorm.
 * User: lamb
 * Date: 2019/4/23
 * Time: 2:46 PM
 */

class ItemController extends Controller
{
        //查看所有数据
        public function index()
        {
            $items = (new ItemModel())->selectAll();
            $this->assign('title','全部条目');
            $this->assign('items',$items);
        }
        //添加数据
        public function add()
        {
            $data['item_name'] = $_POST['value'];
            $count = (new ItemModel())->add($data);
            $this->assign('title','添加成功');
            $this->assign('count',$count);
        }
        //根据id查找数据
        public function view($id=null)
        {
            $item = (new ItemModel())->select($id);
            $this->assign('title','正在查看'.$item['item_name']);
            $this->assign('item',$item);
        }
        public function update()
        {
            $data =array('id'=>$_POST['id'],'item_name'=>$_POST['value']);
            $count = (new ItemModel())->update($data['id'],$data);
            $this->assign('title','修改成功');
            $this->assign('count',$count);
        }
        // 删除记录，测试框架DB记录删除（Delete）
        public function delete($id = null)
        {
            $count = (new ItemModel)->delete($id);

            $this->assign('title', '删除成功');
            $this->assign('count', $count);
        }
}