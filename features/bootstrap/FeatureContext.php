<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Laracasts\Behat\Context\Migrator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;


/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext Implements Context, SnippetAcceptingContext
{


    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        require_once __DIR__ . '/../../bootstrap/app.php';

    }

//    /**
//     *
//     * @BeforeSuite
//     */
//
//    public static function bootstrapLaravel()
//    {
//    }

//    /**
//     * Begin a database transaction.
//     *
//     * @BeforeScenario
//     */
//    public static function beginTransaction()
//    {
//        DB::beginTransaction();
//    }
//
//    /**
//     *
//     * Roll it back after the scenario.
//     *
//     * @AfterScenario
//     */
//    public static function rollback()
//    {
//        DB::rollback();
//    }

    /**
     *
     * Roll it back after the scenario.
     *
     * @BeforeScenario
     */
    public static function cleanDB()
    {
        Artisan::call('migrate:refresh');
    }



}
