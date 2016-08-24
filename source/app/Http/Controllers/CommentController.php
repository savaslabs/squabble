<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use App\Helpers\CommentHelpers;
use App\Helpers\SlackHelpers;

class CommentController extends Controller {

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

    /**
     * Get comments for a given post.
     *
     * This is a POST method. Pass in the 'slug' for a post and return
     * comments associated with it.
     */
    public function getCommentsByPost(Request $request) {
        $slug = ltrim($request->input('slug'), '/');
        $comments = Comment::where('slug', urldecode($slug))->get();
        return ($comments) ? CommentHelpers::formatData($comments) : CommentHelpers::formatData(array(), FALSE);
    }

    public function saveComment(Request $request){
        $commentData = array(
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'comment' => $request->input('comment'),
            'slug' => ltrim($request->input('slug'), '/'),
            'ip' => $request->getClientIp(),
        );

        if (preg_match('@savaslabs.com$', $commentData['email']) === 1) {
          $commentData['savasian'] = 1;
        }

        $commentData['token'] = md5(\Hash::make($commentData['comment'] . $commentData['email'] . $commentData['slug']));
        $comment = Comment::create($commentData);

        $commentData['created_at'] = $comment->created_at->toDateTimeString();
        $commentData['id'] = $comment->id;

        // Send mail.
        \Mail::send('new-comment', $commentData, function($message) {
            $message->from('squabble@savaslabs.com', 'Squabble Comments');
            $message->to('info@savaslabs.com', 'Savas Labs')->subject('New comment posted to site');
        });

        $commentLink = sprintf("<https://%s/%s|%s>", getenv('BASEURL'), $commentData['slug'], $commentData['slug']);
        $deleteUrl = sprintf("/api/comments/delete/%s/%s", $commentData['id'], urlencode($commentData['token']));
        $slackText = sprintf("New comment from %s on post %s:\n\n%s\n\nTo delete, use %s",
            $commentData['name'],
            $commentLink,
            $commentData['comment'],
            $deleteUrl
          );
        SlackHelpers::notifySlack($slackText);

        \Log::info(sprintf('Saved new comment with ID %d from IP %s', $comment->id, $request->getClientIp()));

        return CommentHelpers::formatData(array($commentData));
    }

    public function deleteComment($id, $token) {
        $comment = Comment::find($id);
        if (!$comment) {
            return CommentHelpers::formatData(array(), false, sprintf('Comment %d not found', $id), 400);
        }
        if (trim(urldecode($token)) == trim($comment->getAttribute('token'))) {
            $comment->delete();
            \Log::info(sprintf('Deleted comment #%d', $id));
            return CommentHelpers::formatData(array(), true, sprintf('Comment %d was deleted', $id));
        }
        \Log::error(sprintf('Unauthorized request to delete comment #%d', $id));
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
