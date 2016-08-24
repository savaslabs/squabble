<?php

/**
 * @file
 * Tests for the Comment system.
 */

/**
 * Comment test case.
 */
class CommentTest extends TestCase
{

    /**
     * Test redirect from route to api/comments.
     *
     * @return void
     */
    public function testApiRedirect()
    {
        $this->call('GET', '/');
        $this->assertRedirectedTo('api/comments');
    }

    /**
     * Test API response.
     *
     * @return void
     */
    public function testApiResponse()
    {
        $this->seed();
        $response = $this->call('GET', '/api/comments');
        $this->assertResponseOk();
        $this->assertObjectHasAttribute('content', $response, 'Response as content');
        $content = $response->getContent();
        $this->assertJson($content, 'Received JSON');
        $decoded = json_decode($content, true);
        $this->assertCount(2, $decoded['data'], 'Two comments');
    }

    /**
     * Test API get by slug.
     *
     * @return void
     */
    public function testApiGetBySlug()
    {
        $slug = '2015/04/27/durham-restaurant-time-machine.html';
        $this->call('GET', sprintf('/api/comments/post/%s', $slug));
        $this->assertResponseStatus(404);

        $slug = urlencode('2015/04/27/durham-restaurant-time-machine.html');
        $response = $this->call('GET', sprintf('/api/comments/post/%s', $slug));
        $this->assertResponseOk();
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertCount(2, $decoded['data'], 'Two comments');
    }

    /**
     * Test posting comments.
     *
     * @return void
     */
    public function testApiPostComment()
    {
        $comment = array(
          'comment' => 'Something new',
          'name' => 'A test bot',
          'email' => 'test@bot.com',
          'ip' => '127.1.1.1',
          'slug' => '2015/04/27/durham-restaurant-time-machine.html',
          'nocaptcha' => 'owl',
        );
        $result = $this->call('POST', '/api/comments/new', $comment);
        $this->assertResponseOk();
        $data = json_decode($result->getContent(), true);
        $this->assertEquals(true, $data['success'], 'Successfully posted comment');

        $response = $this->call('GET', '/api/comments');
        $data = json_decode($response->getContent(), true);
        $this->assertCount(3, $data['data'], 'Returned 3 comments');
    }

  /**
   * Test posting a spam comment.
   *
   * @return void
   */
  public function testApiPostSpamComment()
  {
    $comment = array(
      'comment' => 'Spam',
      'name' => 'Spammer',
      'email' => 'spam@spam.com',
      'ip' => '127.1.1.1',
      'slug' => '2015/04/27/durham-restaurant-time-machine.html',
      'url' => 'http://spam.com',
      'nocaptcha' => 'owl',
    );
    $this->call('POST', '/api/comments/new', $comment);
    $this->assertResponseStatus(403);
    $result = $this->call('GET', '/api/comments');
    $this->assertNotContains('spam@spam.com', $result->getContent(), 'No spam posted', true);
  }

    /**
     * Test posting a no captcha comment.
     *
     * @return void
     */
    public function testApiPostNoCaptcha()
    {
        $comment = array(
            'comment' => 'No captcha',
            'name' => 'Someone',
            'email' => 'nocaptcha@nocaptcha.com',
            'ip' => '127.0.0.1',
            'slug' => '2015/04/27/durham-restaurant-time-machine.html',
        );
        $this->call('POST', '/api/comments/new', $comment);
        $this->assertResponseStatus(400);
        $result = $this->call('GET', '/api/comments');
        $this->assertNotContains('nocaptcha@nocaptcha.com', $result->getContent(), 'No nocaptcha posted', true);
        $comment['nocaptcha'] = 'OWL';
        $this->call('POST', '/api/comments/new', $comment);
        $this->assertResponseStatus(200);
        $comment['nocaptcha'] = 'an owl';
        $this->call('POST', '/api/comments/new', $comment);
        $this->assertResponseStatus(200);
    }

    public function testApiGetDeleteComment()
    {
        $comment = array(
            'comment' => 'Comment to delete',
            'name' => 'Someone soon to be forgotten',
            'email' => 'comment@delete.me',
            'ip' => '127.0.0.1',
            'nocaptcha' => 'owl',
            'slug' => '2015/04/27/durham-restaurant-time-machine.html',
        );
        $result = $this->call('POST', '/api/comments/new', $comment);
        $data = json_decode($result->getContent(), true);
        $this->assertResponseStatus(200);
        $result = $this->call('GET', sprintf('/api/comments/delete/%d/%s', $data['data'][0]['id'], 'nottherealtoken'));
        $this->assertResponseStatus(403);
        $this->call('GET', sprintf('/api/comments/delete/1000/test'));
        $this->assertResponseStatus(400);
        $url = sprintf('/api/comments/delete/%d/%s', $data['data'][0]['id'], urlencode($data['data'][0]['token']));
        $this->call('GET', $url);
        $this->assertResponseStatus(200);
    }

  /**
   * Test that Savasian field is set to 1 when comment email is @savasalabs.com.
   */
  public function testApiSavasianComment() {
    $comment = array(
      'comment' => 'Comment to delete',
      'name' => 'Someone soon to be forgotten',
      'email' => 'comment@savaslabs.com',
      'ip' => '127.0.0.1',
      'nocaptcha' => 'owl',
      'slug' => '2015/04/27/durham-restaurant-time-machine.html',
    );
    $result = $this->call('POST', '/api/comments/new', $comment);
    $data = json_decode($result->getContent(), true);
    $this->assertResponseStatus(200);
    $this->assertEquals($data['data'][0]['savasian'], "1");
  }

}
