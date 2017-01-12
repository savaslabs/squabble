<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\CommentHelpers;
use App\Helpers\SlackHelpers;

class ValidInputMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $responseParams = array();

        // If we've got a bot, fake a successful request.
        if ($request->input('url')) {
            $responseParams = array('message' => 'Spam', 'status' => 403);
        }
        else if (!$request->input('name')) {
            $responseParams = array('message' => 'Please enter your name.', 'status' => 400);
        }
        else if (!$request->input('email')) {
            $responseParams = array('message' => 'Please enter your email.', 'status' => 400);
        }
        else if (!$request->input('comment')) {
            $responseParams = array('message' => 'Please include a comment.', 'status' => 400);
        }
        else if (!$request->input('slug')) {
            // TODO: Check against website to ensure the slug is valid.
            $responseParams = array('message' => 'Slug is required', 'status' => 400);
        }
        else if (!$request->input('nocaptcha')) {
            $responseParams = array(
              'message' => 'Please answer "What type of animal is the Savas logo?"',
              'status' => 400,
              'data' => array('error_field' => 'nocaptcha')
            );
        }
        else if (stripos($request->input('nocaptcha'), getenv('NOCAPTCHA')) === FALSE) {
            // We don't return a 403 here, so that we can display the message to the end user.
            $responseParams = array(
              'message' => 'Sorry, our logo is not a(n) ' . $request->input('nocaptcha') . '. Please try again!',
              'status' => 400,
              'error_field' => 'nocaptcha'
            );
        }

        if (!empty($responseParams)) {
          SlackHelpers::notifySlack(sprintf("Failed comment submission. %s\n\nFull request: %s", $responseParams['message'], print_r(
            array(
            'url' => $request->input('url'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'comment' => $request->input('comment'),
            'slug' => $request->input('slug'),
            'nocaptcha' => $request->input('nocaptcha'),
            ),
            TRUE)));
          return CommentHelpers::formatData(array(), false, $responseParams['message'], $responseParams['error_field'], $responseParams['status']);
        }

        return $next($request);
    }

}
