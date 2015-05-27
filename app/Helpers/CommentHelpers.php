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
        // Do not supply the token in the API response.
        foreach ($data as &$datum) {
            unset($datum['token']);
        }
        $content = array(
            'success' => $success,
            'data' => $data,
            'message' => $message,
        );
        return response($content, $status_code);
    }

}