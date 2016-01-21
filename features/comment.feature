
  Feature: Comment system test
    Visit local site
    Add comment
    verify it appears on local site
    verify it appears in the json output from the comment server


  Scenario: Make a comment
    Given I am on "http://localhost:4000/2015/05/22/durham-living-wage.html"
    And I follow "Comments!"
    And I fill in "name" with "test-user"
    And I fill in "email" with "test-user@test.user.com"
    And I fill in "comment" with "behat test comment"
    And I fill in "nocaptcha" with "owl"
    And I press "Submit"
    And I reload the page
    And I follow "Comments!"
    Then I should see "behat test comment"
    Then I go to "http://localhost:8000/api/comments"
    And I should see "behat test comment"


  Scenario: Delete the comment
