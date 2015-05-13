<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentController extends Controller{


    public function index() {
        return Comment::all();
    }

    public function getComment($id){
        return Comment::find($id);
    }

    public function getCommentsForPost($slug) {
        $slug = urldecode($slug);
        $comments = Comment::all();
        $commentsForPost = [];
        foreach ($comments as $comment) {
            if ($comment['slug'] == $slug) {
                $commentsForPost[] = $comment;
            }
        }
        return $commentsForPost;
    }

    public function saveComment(Request $request){
        $commentData = array(
            'name' => $request->input('name'),
            'title' => 'test',
            'email' => $request->input('email'),
            'comment' => $request->input('comment'),
            'slug' => ltrim($request->input('slug'), '/'),
        );
        return Comment::create($commentData);
    }

}