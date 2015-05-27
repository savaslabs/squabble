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
        return ($comment) ? CommentHelpers::formatData(array($comment)) : CommentHelpers::formatData(array(), FALSE);
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
        $commentData['token'] = \Hash::make($commentData['comment'] . $commentData['email'] . $commentData['slug']);
        $comment = Comment::create($commentData);
        $commentData['id'] = $comment->id;
        \Mail::send('new-comment', $commentData, function($message) {
            $message->to('info@savaslabs.com', 'Savas Labs')->subject('New comment posted to site');
        });
        return CommentHelpers::formatData(array($commentData));
    }

    public function deleteComment($id, $token) {
        $comment = Comment::find($id);
        if (!$comment) {
            return CommentHelpers::formatData(array(), false, sprintf('Comment %d not found', $id), 400);
        }
        if (trim(urldecode($token)) == trim($comment->getAttribute('token'))) {
            $comment->delete();
            return CommentHelpers::formatData(array(), true, sprintf('Comment %d was deleted', $id));
        }
        return CommentHelpers::formatData(array(), false, null, 403);
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
        return CommentHelpers::formatData(array($commentData));
    }

}