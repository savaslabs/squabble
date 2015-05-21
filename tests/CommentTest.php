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
        $response = $this->call('GET', '/api/comments');
        $this->assertResponseOk();
        $this->assertObjectHasAttribute('content', $response, 'Response as content');
        $content = $response->getContent();
        $this->assertJson($content, 'Received JSON');
        $decoded = json_decode($content, true);
        $this->assertCount(2, $decoded, 'Two comments');
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
        $this->assertCount(2, $decoded, 'Two comments');
    }

    /**
     * Test posting comments.
     *
     * @return void
     */
    public function testApiPostComment()
    {
        $this->seed();
        $comment = array(
            'comment' => 'Something new',
            'name' => 'A test bot',
            'email' => 'test@bot.com',
            'ip' => '127.1.1.1',
            'slug' => '2015/04/27/durham-restaurant-time-machine.html',
        );
        $result = $this->call('POST', '/api/comments/new', $comment);
        $this->assertResponseOk();
        $data = json_decode($result->getContent(), true);
        $this->assertEquals(true, $data['success'], 'Successfully posted comment');

        $response = $this->call('GET', '/api/comments');
        $data = json_decode($response->getContent(), true);
        $this->assertCount(3, $data['data'], 'Returned 3 comments');
    }

}
