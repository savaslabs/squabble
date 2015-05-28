<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\CommentHelpers;

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
        // If we've got a bot, fake a successful request.
        if ($request->input('url')) {
            return CommentHelpers::formatData(array(), false, 'Spam', 403);
        }
        if (!$request->input('name')) {
            return CommentHelpers::formatData(array(), false, 'Name is required', 400);
        }
        if (!$request->input('email')) {
            return CommentHelpers::formatData(array(), false, 'Email is required', 400);
        }
        if (!$request->input('comment')) {
            return CommentHelpers::formatData(array(), false, 'Comment is required', 400);
        }
        if (!$request->input('slug')) {
            // TODO: Check against website to ensure the slug is valid.
            return CommentHelpers::formatData(array(), false, 'Slug is required', 400);
        }
        if (!$request->input('nocaptcha')) {
            return CommentHelpers::formatData(array(), false, 'No captcha response required', 400);
        }
        if (stripos($request->input('nocaptcha'), getenv('NOCAPTCHA')) === FALSE) {
            // We don't return a 403 here, so that we can display the message to the end user.
            return CommentHelpers::formatData(array(), false, 'Sorry, our mascot is not a(n) ' . $request->input('nocaptcha'));
        }
        return $next($request);
    }

}
