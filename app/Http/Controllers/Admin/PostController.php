<?php

namespace App\Http\Controllers\Admin;

use App\Models\Comment;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class PostController extends CommonController
{
    #帖子列表
    public function index(Request $request)
    {
        view()->share('page_title','帖子管理');
        $data = Post::leftjoin('users','posts.user_id', '=', 'users.id')->where(function ($query) use ($request){
            #$query->select('users.*,posts.*');
            if($request->get('id')){
                $query->where('posts.id',$request->get('id'));
            }
            if($request->get('user_id')){
                $query->where('posts.user_id',$request->get('user_id'));
            }
            if($request->get('name_id')){
                $query->where('users.name_id',$request->get('name_id'));
            }
            if($request->get('nick_name')){
                $query->where('users.nick_name',$request->get('nick_name'));
            }
            if($request->get('title')){
                $query->where('posts.title','like','%'.$request->get('title').'%');
            }
            if($request->get('type')){
                $query->where('posts.type',$request->get('type'));
            }
            if($request->get('pay_type')){
                if($request->get('pay_type')==1){
                    $query->where('posts.payments',0.00);
                }else{
                    $query->where('posts.payments','>',0.00);
                }
            }
            if($request->get('status')){
                $query->where('posts.status',$request->get('status'));
            }
            if($request->get('start_time')){
                $query->where('posts.created_at','>=',$request->get('start_time'));
            }
            if($request->get('end_time')){
                $query->where('posts.created_at','<',$request->get('end_time'));
            }
        })->orderBy('posts.id', 'desc')->paginate(10);
        foreach($data as $key=>$value){
            #发帖类型,帖子状态
            $data[$key]['type_str'] = $this->postType($value['type']);
            $data[$key]['status_str'] = $this->postStatus($value['status']);
        }
        $condition = [
            'id'=>$request->get('id'),
            'user_id'=>$request->get('user_id'),
            'name_id'=>$request->get('name_id'),
            'nick_name'=>$request->get('nick_name'),
            'title'=>$request->get('title'),
            'type'=>$request->get('type'),
            'pay_type'=>$request->get('pay_type'),
            'status'=>$request->get('status'),
            'start_time'=>$request->get('start_time'),
            'end_time'=>$request->get('end_time'),
        ];
        return view('admin/post/index')->with(['data'=>$data,'condition'=>$condition]);
    }

    #查看
    public function edit(Request $request,$id)
    {
        #$disk = \Storage::disk('qiniu');
        #echo $disk->exists('14759839382149.jpg');die();
        view()->share('page_title','帖子编辑');
        $post = Post::find($id);
        #图片
        $images = PostImage::where('post_id',$id)->get();
        foreach($images as $key=>$value){
            $images[$key]['image'] =  env('App_IMAGE_URL').$value['image'];
        }
        #评论(每楼详细)
        $comments = Comment::where('post_id',$id)->where('reply_id',0)->where('status',1)->orderBy('created_at', 'asc')->paginate(3);
        #评论(每楼的回复详细)
        foreach($comments as $key=>$value){
            $user = User::find($value['user_id']);
            $comments[$key]['nick_name'] = $user['nick_name'];
            $reply_comment = Comment::where('post_id',$id)->where('reply_id',$value['id'])->where('status',1)->orderBy('created_at', 'asc')->get();
            foreach($reply_comment as $k=>$v){
                $user = User::find($v['user_id']);
                $reply_comment[$k]['nick_name'] = $user['nick_name'];
                $user = User::find($v['to_user_id']);
                $reply_comment[$k]['to_nick_name'] = $user['nick_name'];
            }
            $comments[$key]['reply'] = $reply_comment;
        }
        return view('admin/post/edit')->with(['data'=>$post,'images'=>$images,'comments'=>$comments]);

    }

    #更新,审核帖子
    public function update($id)
    {
        $rules = [
            'user_id'   =>  'required|numeric',
            'title'     =>  'required|between:1,20',
            'type'      =>  'required',
            'status'    =>  'required',
            'created_at'=>  'required',
        ];
        $data = Input::all();
        $validator = Validator::make($data,$rules);
        if($validator->fails()){
            return back()->withErrors($validator);
        }

        unset($data['_token']);
        $result = Post::where('id',$id)->update($data);
        if(!$result){
            return back()->with('errors','更新帖子失败');
        }
        return back()->with('success','更新帖子成功');
    }

    #删除评论
    public function delComment(Request $request)
    {
        $id = (int)$request->get('id');
        if(!$id){
            return CommonController::echoJson(400,'parameter error');
        }
        $rs_comment = Comment::where('id',$id)->update(['status'=>2]);
        $rs_reply = Comment::where('reply_id',$id)->update(['status'=>2]);
        if(!$rs_comment){
            return CommonController::echoJson(401,'del error');
        }
        return CommonController::echoJson(200,'成功');
    }

    public function create()
    {

    }

    public function show()
    {

    }

    public function delete()
    {

    }




}
