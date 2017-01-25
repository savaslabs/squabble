Feature: Retrieving comments works via the API

Scenario: Get all comments via the API
  When I send a GET request to "api/comments"
  Then the response should contain json:
  """
  {
      "success": true
  }
  """
  And the response should contain "A Comment!"
  # TODO: Test this more thoroughly. This is hard to do because of not being able to bulk delete comments easily.

Scenario: Get a single comment via the API
  When I send a GET request to "api/comments/id/1"
  Then the response should contain "bas.html"
  And the response should contain "A Comment!"

Scenario: Get comment count for a given post
  Given I send a GET request to "api/comments/post?slug=fo%2Fbar%2Fa_test_slug3.html"
  Then the response should contain json:
    """
    {
    "success": true,
    "data": [],
    "message": ""
    }
    """
  Given I send a POST request to "api/comments/new" with form data:
    """
    name=Tim
    email=t@s.com
    comment=A Comment!
    slug=fo/bar/a_test_slug3.html
    nocaptcha=owl
    """
  And I send a GET request to "api/comments/post?slug=fo%2Fbar%2Fa_test_slug3.html"
  Then the response should contain "A Comment!"
  And the response should contain "a_test_slug3.html"
