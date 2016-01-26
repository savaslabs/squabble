<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Laracasts\Behat\Context\Migrator;
use Laracasts\Behat\Context\DatabaseTransactions;


/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext Implements Context, SnippetAcceptingContext
{

//    use DatabaseTransactions;

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
     *
     * Roll it back after the scenario.
     *
//     * @AfterScenario
//     */
//    public static function rollback()
//    {
//        DB::rollback();
//    }



}
