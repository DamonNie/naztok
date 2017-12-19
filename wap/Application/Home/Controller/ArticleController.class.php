<?php
namespace Home\Controller;

class ArticleController extends HomeController
{
	public function index($id = NULL)
	{
		if (empty($id)) {
			redirect(U('Article/detail'));
		}

		if (!check($id, 'd')) {
			redirect(U('Article/detail'));
		}

		$Articletype = M('ArticleType')->where(array('id' => $id))->find();
		$ArticleTypeList = M('ArticleType')->where(array('status' => 1, 'index' => 1, 'shang' => $Articletype['shang']))->order('sort asc ,id asc')->select();
		$Articleaa = M('Article')->where(array('id' => $ArticleTypeList[0]['id']))->find();
		$this->assign('shang', $Articletype);

		foreach ($ArticleTypeList as $k => $v) {
			$ArticleTypeLista[$v['name']] = $v;
		}
		$this->assign('id',$id);
		$this->assign('ArticleTypeList', $ArticleTypeLista);
		$this->assign('data', $Articleaa);
		$where = array('type' => $Articletype['name']);
		$Model = M('Article');
		$count = $Model->where($where)->count();
		$Page = new \Think\Page($count, 10);
		
		if ($_GET['p'] == '') {
                $_GET['p'] = 1;
            }
			$uppage=$_GET['p']-1;
			$dopage=$_GET['p']+1;
            if ($count <= 10) {
                $Page->setConfig('theme', '');
            } else if ($_GET['p'] == 1) {
                $Page->setConfig('theme', '<a href="/Article/index/id/'.$id.'/p/'.$dopage.'">»</a>');
            } else if ($_GET['p'] == ceil($count / 10)) {
                $Page->setConfig('theme', '<a href="/Article/index/id/'.$id.'/p/'.$uppage.'">«</a>');
            } else if ($_GET['p'] > 1) {
				$Page->setConfig('theme', '<a href="/Article/index/id/'.$id.'/p/'.$uppage.'">«</a><a href="/Article/index/id/'.$id.'/p/'.$dopage.'">»</a>');
            }
			
			
			
            $Page->setConfig('prev', '上一页');
            $Page->setConfig('next', '下一页');
		
		
		
		
		$show = $Page->show();
		$list = $Model->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function detail($id = NULL)
	{
		if (empty($id)) {
			$id = 1;
		}

		if (!check($id, 'd')) {
			$id = 1;
		}

		$data = M('Article')->where(array('id' => $id))->find();
		$ArticleType = M('ArticleType')->where(array('status' => 1, 'index' => 1))->order('sort asc ,id desc')->select();

		foreach ($ArticleType as $k => $v) {
			$ArticleTypeList[$v['name']] = $v;
		}

		$this->assign('ArticleTypeList', $ArticleTypeList);
		$this->assign('data', $data);
		$this->assign('type', $data['type']);
		$this->display();
	}

	public function type($id = NULL)
	{
		if (empty($id)) {
			$id = 1;
		}

		if (!check($id, 'd')) {
			$id = 1;
		}

		$Article = M('ArticleType')->where(array('id' => $id))->find();

		if ($Article['shang']) {
			$shang = M('ArticleType')->where(array('name' => $Article['shang']))->find();
			$ArticleType = M('ArticleType')->where(array('status' => 1, 'shang' => $Article['shang']))->order('sort asc ,id desc')->select();
			$Articleaa = $Article;
		}
		else {
			$shang = M('ArticleType')->where(array('id' => $id))->find();
			$ArticleType = M('ArticleType')->where(array('status' => 1, 'shang' => $Article['name']))->order('sort asc ,id desc')->select();
			$Articleaa = M('ArticleType')->where(array('id' => $ArticleType[0]['id']))->find();
		}

		$this->assign('shang', $shang);

		foreach ($ArticleType as $k => $v) {
			$ArticleTypeList[$v['name']] = $v;
		}

		$this->assign('ArticleTypeList', $ArticleTypeList);
		$this->assign('data', $Articleaa);
		$this->display();
	}

	public function install()
	{
	}

	public function check()
	{
	
	}
}

?>