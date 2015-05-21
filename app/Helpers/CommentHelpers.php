<?php

namespace App\Helpers;
use Illuminate\Http\Request;

class CommentHelpers {
    public static function formatData($data, $success = true, $message = '', $status_code = 200) {
        $content = array(
            'success' => $success,
            'data' => $data,
            'message' => $message,
        );
        return response($content, $status_code);
    }

}