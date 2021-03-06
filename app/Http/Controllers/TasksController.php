<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            // ユーザの投稿の一覧を作成日時の降順で取得
            // （後のChapterで他ユーザの投稿も取得するように変更しますが、現時点ではこのユーザの投稿のみ取得します）
            $tasks = $user->tasks()->orderBy('created_at', 'asc')->paginate(10);

            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }

        // Welcomeビューでそれらを表示
        return view('welcome', $data);
    } 
        
       // $data = [];
       // if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
          // $tasks = Task::all();

        // メッセージ一覧ビューでそれを表示
        //return view('tasks.index', [
            //'tasks' => $tasks,
    
   // ]);
       // }}
    
           // $user = \Auth::user();
            // ユーザの投稿の一覧を作成日時の降順で取得
            // （後のChapterで他ユーザの投稿も取得するように変更しますが、現時点ではこのユーザの投稿のみ取得します）
          // $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);

//$data = [
              //  'user' => $user,
               // 'tasks' => $tasks,
                
          //  ];
        

        // Welcomeビューでそれらを表示
     //   return view('welcome', $data);
       

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $task = new Task;
        
        return view('tasks.create',[
            'task' => $task,
            ]);
    }


    public function store(Request $request)
    {
        
        $request->validate([
            'content' => 'required',
            'status' => 'required|max:10',
            ]);
            
            
        //$task = new Task;
        //$task->user_id = $request->user()->id;
        //$task->content = $request->content;
        //$task->status = $request->status;
        //$task->save();

        // 認証済みユーザ（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $request->user()->tasks()->create([
            'content' => $request->content,
            'status' => $request->status,
        ]);
        
        return redirect('/');

        // 前のURLへリダイレクトさせる
        //return back();
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
        
        return view('tasks.show', [
            'task' => $task,
        ]);
        //
    }
        return redirect ('/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {

       
        return view('tasks.edit', [
            'task' => $task,
        ]);
    }
        return redirect ('/');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
            
        $request->validate([
            'content' => 'required',
            'status' => 'required|max:10',
            ]);
        $task = Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
        
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();
    }

        return redirect('/');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function destroy($id)
    {
        
        $task = \App\Task::findOrFail($id);
      
       // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }
        //$task->delete();
        
        return redirect('/');

        // 前のURLへリダイレクトさせる
        //return back();
    }
}
