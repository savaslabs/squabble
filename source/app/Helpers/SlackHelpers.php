<?php

namespace App\Helpers;

use GuzzleHttp\Client;

/**
 * Class SlackHelpers
 * @package App\Helpers
 *
 * Helper functions to post to slack.
 */
class SlackHelpers {

  /**
   * Post a message to slack via a webhook.
   */
  public static function notifySlack($message) {
    // Post to Slack.
    if ($slackPostUrl = getenv('SLACK_WEBHOOK_URL')) {

      $requestParameters = array(
        'text' => $message,
        'username' => 'Squabble',
      );

      $client = new Client();
      $client->request('POST', $slackPostUrl, [
        'body' => json_encode($requestParameters),
      ]);
    }
  }
}
