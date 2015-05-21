<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use App\Helpers\CommentHelpers;

class CommentController extends Controller{

    public function index() {
        $comments = Comment::all();
        return ($comments) ? CommentHelpers::formatData($comments) : CommentHelpers::formatData(array(), FALSE);
    }

    public function getComment($id){
        $comment = Comment::find($id);
        return ($comment) ? CommentHelpers::formatData($comment) : CommentHelpers::formatData(array(), FALSE);
    }

    public function getCommentsForPost($slug) {
        $comment = Comment::where('slug', urldecode($slug))->get();
        return ($comment) ? CommentHelpers::formatData($comment) : CommentHelpers::formatData(array(), FALSE);
    }

    public function saveComment(Request $request){
        $commentData = array(
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'comment' => $request->input('comment'),
            'slug' => ltrim($request->input('slug'), '/'),
            'ip' => $request->getClientIp(),
        );
        return CommentHelpers::formatData(Comment::create($commentData));
    }

    public function getCommentsCount() {
        $comments = Comment::all();
        if (!$comments) {
            return CommentHelpers::formatData(array(), FALSE);
        }
        $commentData = [];
        foreach ($comments as $comment) {
            if (!isset($commentData[$comment->slug])) {
                $commentData[$comment->slug] = 1;
            }
            else {
                $commentData[$comment->slug]++;
            }
        }
        return CommentHelpers::formatData($commentData);
    }

}