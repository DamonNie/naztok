<?php
namespace Home\Controller;

class VoteController extends HomeController
{
	public function index()
	{
		if (authgame('vote') != 1) {
			redirect('/');
		}

		$coin_list = M('VoteType')->select();

		if (is_array($coin_list)) {
			foreach ($coin_list as $k => $v) {
				$vv = $v;
				$v = C('coin')[$v['coinname']];
				$list[$v['name']]['img'] = $v['img'];
				$list[$v['name']]['name'] = $v['name'];
				$list[$v['name']]['title'] = $v['title'];
				$list[$v['name']]['zhichi'] = M('Vote')->where(array('coinname' => $v['name'], 'type' => 1))->count() + $vv['zhichi'];
				$list[$v['name']]['fandui'] = M('Vote')->where(array('coinname' => $v['name'], 'type' => 2))->count() + $vv['fandui'];
				$list[$v['name']]['zongji'] = $list[$v['name']]['zhichi'] + $list[$v['name']]['fandui'];
				$list[$v['name']]['bili'] = round(($list[$v['name']]['zhichi'] / $list[$v['name']]['zongji']) * 100, 2);
			}

			$this->assign('list', $list);
		}

		$this->assign('prompt_text', D('Text')->get_content('game_vote'));
		$this->display();
	}

	public function up($type = NULL, $coinname = NULL)
	{
		if (!userid()) {
			$this->error('请先登录！');
		}

		if (($type != 1) && ($type != 2)) {
			$this->error('参数错误！');
		}

		$coin_list = C('coin_list');

		if (!is_array($coin_list[$coinname])) {
			$this->error('参数错误2！');
		}

		if (M('Vote')->where(array('userid' => userid(), 'coinname' => $coinname))->find()) {
			$this->error('您已经投票过，不能再次操作！');
		}
		else if (M('Vote')->add(array('userid' => userid(), 'coinname' => $coinname, 'type' => $type, 'addtime' => time(), 'status' => 1))) {
			$this->success('投票成功！');
		}
		else {
			$this->error('投票失败！');
		}
	}

	public function install()
	{

	}

	public function uninstall()
	{
	}
}

?>