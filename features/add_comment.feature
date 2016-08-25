Feature: Adding a comment via the API works

Scenario: Someone adds a valid comment
  When I send a POST request to "api/comments/new" with form data:
    """
    name=Tim
    email=t@s.com
    comment=A Comment!
    slug=fo/bar/bas.html
    nocaptcha=owl
    """
  Then the response code should be 200
  And the response should contain "A comment!"
  And the response should contain "t@s.com"
  And the response should contain "bas.html"
  And the response should contain "Success"

Scenario: Someone adds an invalid comment (missing name)
  When I send a POST request to "api/comments/new" with form data:
    """
    email=t@s.com
    comment=A Comment!
    slug=fo/bar/bas.html
    nocaptcha=owl
    """
  Then the response code should be 400
  And the response should contain "Name is required"

Scenario: Someone adds an invalid comment (missing email)
  When I send a POST request to "api/comments/new" with form data:
    """
    name=Tim
    comment=A Comment!
    slug=fo/bar/bas.html
    nocaptcha=owl
    """
  Then the response code should be 400
  And the response should contain "Email is required"

Scenario: Someone adds an invalid comment (missing comment)
  When I send a POST request to "api/comments/new" with form data:
    """
    name=Tim
    email=t@s.com
    slug=fo/bar/bas.html
    nocaptcha=owl
    """
  Then the response code should be 400
  And the response should contain "Comment is required"

Scenario: Someone adds an invalid comment (missing slug)
  When I send a POST request to "api/comments/new" with form data:
    """
    name=Tim
    email=t@s.com
    comment=A comment
    nocaptcha=owl
    """
  Then the response code should be 400
  And the response should contain "Slug is required"

Scenario: Someone adds an invalid comment (missing nocaptcha)
  When I send a POST request to "api/comments/new" with form data:
  """
  name=Tim
  email=t@s.com
  comment=A comment
  slug=fo/bar/bas.html
  """
  Then the response code should be 400
  And the response should contain "No captcha response required"

Scenario: Someone adds an invalid comment (wrong nocaptcha)
  When I send a POST request to "api/comments/new" with form data:
    """
    name=Tim
    email=t@s.com
    comment=A comment
    slug=fo/bar/bas.html
    nocaptcha=Tiger
    """
  Then the response code should be 400
  And the response should contain "Sorry, our mascot is not a(n) Tiger"

Scenario: A spambot tries to add a comment
  When I send a POST request to "api/comments/new" with form data:
    """
    name=Tim
    email=t@s.com
    comment=A Comment!
    slug=fo/bar/bas.html
    nocaptcha=owl
    url=test_url
    """
  Then the response code should be 403
  And the response should contain "Spam"
