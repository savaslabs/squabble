<?php

namespace App\Helpers;
use Illuminate\Http\Request;

/**
 * Helper methods.
 */
class CommentHelpers {

    /**
     * Format data for responses to requests.
     */
    public static function formatData($data, $success = true, $message = '', $status_code = 200) {
        $content = array(
            'success' => $success,
            'data' => $data,
            'message' => $message,
        );
        if ($status_code !== 200 && $message) {
            \Log::warning($message, array('status_code' => $status_code, 'data' => $data));
        }
        if ($status_code == 200 && $message) {
            \Log::info($message, array('status_code' => $status_code, 'data' => $data));
        }
        return response($content, $status_code)
            ->header('Access-Control-Allow-Origin', '*');
    }

}
