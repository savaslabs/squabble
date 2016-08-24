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
            $responseParams =array('message' => Spam, 'status' => 403);
        }
        if (!$request->input('name')) {
            $responseParams =array('message' => 'Name is required', 'status' => 400);
        }
        if (!$request->input('email')) {
            $responseParams =array('message' => 'Email is required', 'status' => 400);
        }
        if (!$request->input('comment')) {
            $responseParams =array('message' => 'Comment is required', 'status' => 400);
        }
        if (!$request->input('slug')) {
            // TODO: Check against website to ensure the slug is valid.
            $responseParams = array('message' => 'Slug is required', 'status' => 400);
        }
        if (!$request->input('nocaptcha')) {
            $responseParams = array('message' => 'No captcha response required', 'status' => 400);
        }
        if (stripos($request->input('nocaptcha'), getenv('NOCAPTCHA')) === FALSE) {
            // We don't return a 403 here, so that we can display the message to the end user.
            $responseParams = array(
              'message' => 'Sorry, our mascot is not a(n) ' . $request->input('nocaptcha'),
              'status' => 200
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
            'nocaptha' => $request->input('nocaptcha'),
            ),
            TRUE)));
          return CommentHelpers::formatData(array(), false, $responseParams['message'], $responseParams['status']);
        }

        return $next($request);
    }

}
