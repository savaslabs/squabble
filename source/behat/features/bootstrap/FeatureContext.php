<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\RestTestingContext\RestContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{

    protected $restContext;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * Gather all defined contexts.
     *
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope) {
       $environment = $scope->getEnvironment();
       $this->restContext = $environment->getContext('Behat\RestTestingContext\RestContext');
    }

    /**
     * Check number of comments in response.
     *
     * @Then the response should contain :number comments total
     */
    public function theResponseShouldContainComments($number) {
      $responseData = $this->restContext->getResponseData();

      if (!isset($responseData['data'])) {
        throw new \Exception("Response did not have data property set.");
      }
      else {
        $responseNumber = count($responseData['data']);
        $message = sprintf("%d comments expected but %d found", $number, $responseNumber);
        if ($number != $responseNumber) {
          throw new \Exception($message);
        }
      }

    }

}
