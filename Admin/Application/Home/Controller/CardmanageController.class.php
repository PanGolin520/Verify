<?php
namespace Home\Controller;

use Common\Controller\BaseController;
use Home\Tool\HJCTool;
use Home\Tool\Secret;

class CardmanageController extends BaseController {
    public function index() {
		if($this->getUser()['username'] != C('ADMIN_USER')){
			http_response_code(404);
			return;
        }
        $this->display();
    }
	public function CardList(){
		if($this->getUser()['username'] != C('ADMIN_USER')){
			http_response_code(404);
			return;
        }
		if (I('post.page') == '') {
            return;
        }
		if (I('post.limit') == '') {
            return;
        }
		$search = null;
		if (I('post.searchParams') != '') {
			$json = I('post.searchParams');
			$json = html_entity_decode($json);
            $json = stripslashes($json);
			$search = json_decode($json,true);
		}
		$this->_currentPage = I('post.page/d');
		$this->_itemCountAPage = I('post.limit/d');
		$mysql = M('card');
		$ret = $mysql->select();
		if ($search == null) {
            $list = $mysql->limit(abs($this->_currentPage-1) * $this->_itemCountAPage . ", " . $this->_itemCountAPage)->select();
        } else {
		    $list = $mysql->where("card = '{$search['card']}'")->select();
		}
		$date=[
                  'code'=>0,
                  'msg'=>'',
				  'count' => sizeof($ret),
				  "data" => $list
               ];
		echo json_encode($date);
	}
	public function CardDel(){
		if($this->getUser()['username'] != C('ADMIN_USER')){
			http_response_code(404);
			return;
        }
		$mysql = M('Card');
		$card = I('post.card');
		$ret = $mysql->where("card = '$card'")->delete();
		$date=[
                  'status'=>0,
                  'msg'=>($ret?'删除成功':'删除失败')
              ];
		$this->ajaxReturn(json_encode($date),'JSON');
	}
	public function State(){
		if($this->getUser()['username'] != C('ADMIN_USER')){
			http_response_code(404);
			return;
        }
		$mysql = M('Card');
		$ret = $mysql->create();
		$card = I('post.card');
		$ret = $mysql->where("card = '$card'")->save($ret);
		$date=[
                  'status'=>($ret ? 0 : 1 ),
                  'msg'=>($ret?'更新成功':'更新失败')
              ];
		$this->ajaxReturn(json_encode($date),'JSON');
	}
}