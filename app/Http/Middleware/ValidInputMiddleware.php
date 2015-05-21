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
            // TODO: Check against website.
            return CommentHelpers::formatData(array(), false, 'Slug is required', 400);
        }
        return $next($request);
    }

}
