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
            $responseParams = array(array(), false, 'Spam', 403);
        }
        if (!$request->input('name')) {
            $responseParams = array(array(), false, 'Name is required', 400);
        }
        if (!$request->input('email')) {
            $responseParams = array(array(), false, 'Email is required', 400);
        }
        if (!$request->input('comment')) {
            $responseParams = array(array(), false, 'Comment is required', 400);
        }
        if (!$request->input('slug')) {
            // TODO: Check against website to ensure the slug is valid.
            $responseParams = array(array(), false, 'Slug is required', 400);
        }
        if (!$request->input('nocaptcha')) {
            $responseParams = array(array(), false, 'No captcha response required', 400);
        }
        if (stripos($request->input('nocaptcha'), getenv('NOCAPTCHA')) === FALSE) {
            // We don't return a 403 here, so that we can display the message to the end user.
            $responseParams = array(array(), false, 'Sorry, our mascot is not a(n) ' . $request->input('nocaptcha'));
        }

        if (!empty($responseParams)) {
          SlackHelpers::notifySlack(sprintf("Failed comment submission. %s\n\nFull request: %s", $responseParams[2], print_r(
            array(
            'url' => $request->input('url'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'comment' => $request->input('comment'),
            'slug' => $request->input('slug'),
            'nocaptha' => $request->input('nocaptcha'),
            ),
            TRUE)));
          return call_user_func_array(array('App\Helpers\CommentHelpers', 'formatData'), $responseParams);
        }

        return $next($request);
    }

}
