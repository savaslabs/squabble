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
        if (!$request->input('name')) {
            return $this->formatData(array(), false, 'Name is required', 400);
        }
        if (!$request->input('email')) {
            return $this->formatData(array(), false, 'Email is required', 400);
        }
        if (!$request->input('comment')) {
            return $this->formatData(array(), false, 'Comment is required', 400);
        }
        if (!$request->input('slug')) {
            // TODO: Check against website.
            return $this->formatData(array(), false, 'Slug is required', 400);
        }
        // TODO: Sanitize input.
        $commentData = array(
            'name' => $request->input('name'),
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

    protected function formatData($data, $success = TRUE, $message = '', $status_code = 200) {
        $content = array(
            'success' => $success,
            'data' => $data,
            'message' => $message,
        );
        return response($content, $status_code);
    }

}