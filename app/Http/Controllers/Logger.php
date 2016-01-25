<?php
/**
 * Created by PhpStorm.
 * User: will
 * Date: 1/25/16
 * Time: 1:01 PM
 */


namespace App\Http\Controllers;

use Log;
use App\User;
use App\Http\Controllers\Controller;
use Monolog\Logger;
use Monolog\Handler\SlackHandler;
use Monolog\Formatter\LineFormatter;

class UserController extends Controller
{
  public function log() {
    $monolog = new Logger('slack-logger');

    $slackHandler = new SlackHandler(
      getenv('SLACK_TOKEN'),
        '#savaslabs-com'
      );
    $slackHandler->setLevel(Logger::DEBUG);
    $monolog->pushHandler($slackHandler);
    $slackHandler->setFormatter(new LineFormatter());
  }
}