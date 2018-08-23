<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;

require_once ("sphinxapi.php");
class IndexController extends Controller {
    public $s_index;
    public $s_keywords;
    public  $h_cl;
    public $h_opts;
    public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }


    public function test()
    {
        $cl=new \SphinxClient();
        $cl->SetServer('127.0.0.1',9312);
        $cl->SetArrayResult(true);
        $cl->SetMatchMode(SPH_MATCH_ANY);
        $cl->SetLimits(0,12);
        $index_name="dizhi";
        $key="经营部";
        $this->s_keywords=$key;
        $this->s_index=$index_name;
        $this->h_cl=$cl;
        $this->h_opts = array(
            "before_match" => "<strong style='color:red'>",
            "after_match" => "</strong>",
            "chunk_separator" => "<br>",
//            "limit" => 6,
//            "around" =>3
        );


        $res=$cl->Query($key,$index_name);
        $ids=array_column($res['matches'],"id");
//        var_dump($ids);

        $m=M();
        $where['id']=array("in",$ids);
        $res=$m->table("address")->where($where)->select();
        //BuildExcerpts ( $docs, $this->s_index, $this->s_keywords, $this->h_opts);

        foreach ($res as $k=>$v){
            $res[$k]=$cl->BuildExcerpts ( $v, $this->s_index, $this->s_keywords, $this->h_opts);
        }
//        var_dump($res);
        $this->assign("res",$res);
        $this->display("");
    }


    public function is_safe(){
        $id=i("get.id");
        // var_dump($id);die;
        $m=M();
        $res=$m->table("address")->where("id=$id")->find();
        var_dump($res);
    }

}