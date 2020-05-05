<?php

namespace App\Http\Controllers;
use App\User;
use App\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NotesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $notes = Auth::user()->notes()->orderBy('created_at','desc')->with('user:id,name')->paginate();
        return response()->json($notes);
    }

    public function create(Request $request){
        $this->validate($request,[
            'title'=> 'required|min:2',
            'body'=> 'required|min:3'
        ]);
        $title = $request->input('title');
        $body = $request->input('body');
        $new_note = [
            'title'=> $title,
            'body'=>$body,
            'user_id'=>Auth::id(),
        ];
        if(Note::create($new_note)){
            return response()->json(["msg"=>"you have successfully created new note"]);
        }else{
            return response()->json(["msg"=>"faild to create new note"],403);
        }
    }
    public function note($id){
        $note = Auth::user()->notes()->where('id',$id)->get()->first();
        return response()->json($note);
    }
    public function delete($id){
        Auth::user()->notes()->where('id',$id)->delete();
        return response()->json(['msg'=>'note successfully deleted']);
    }
    public function update(Request $request){
        $this->validate($request,[
            'title'=> 'required|min:2',
            'body'=> 'required|min:3'
        ]);
        $id = $request->input('id');
        $title = $request->input('title');
        $body = $request->input('body');
        Auth::user()->notes()->where('id',$id)->update([
            'title'=>$title,
            'body'=>$body,
        ]);
        return response()->json(['msg'=>'note successfully updated']);
    }
}
