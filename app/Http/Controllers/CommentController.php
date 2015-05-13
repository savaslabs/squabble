<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller{

    public function index() {
        $comments = Comment::all();
        return ($comments) ? $this->formatData($comments) : $this->formatData(array(), FALSE);
    }

    public function getComment($id){
        $comment = Comment::find($id);
        return ($comment) ? $this->formatData($comment) : $this->formatData(array(), FALSE);
    }

    public function getCommentsForPost($slug) {
        $comment = Comment::where('slug', urldecode($slug))->get();
        return ($comment) ? $this->formatData($comment) : $this->formatData(array(), FALSE);
    }

    public function saveComment(Request $request){
        // TODO: Sanitize input.
        $commentData = array(
            'name' => $request->input('name'),
            'title' => $request->input('title'),
            'email' => $request->input('email'),
            'comment' => $request->input('comment'),
            'slug' => ltrim($request->input('slug'), '/'),
            'ip' => $request->getClientIp(),
        );
        return $this->formatData(Comment::create($commentData));
    }

    public function getCommentsCount() {
        $comments = Comment::all();
        if (!$comments) {
            return $this->formatData(array(), FALSE);
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
        return $this->formatData($commentData);
    }

    protected function formatData($data, $success = TRUE) {
       return array(
           'success' => $success,
           'data' => $data,
       );
    }

}