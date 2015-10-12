<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link    http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\AnonymousPiwikUsageMeasurement\tests\Fixtures;

use Piwik\API\Request;
use Piwik\Tests\Framework\Fixture;
use Piwik\Plugins\AnonymousPiwikUsageMeasurement\Settings;
use Piwik\Plugins\AnonymousPiwikUsageMeasurement\Tasks;
use Piwik\Plugins\AnonymousPiwikUsageMeasurement\Tracker\CustomVariables;
use Piwik\Plugins\AnonymousPiwikUsageMeasurement\Tracker\Events;
use Piwik\Plugins\AnonymousPiwikUsageMeasurement\Tracker\Targets;
use Piwik\Plugins\AnonymousPiwikUsageMeasurement\Tracker\Trackers;

/**
 * Generates tracker testing data for our TasksTest
 *
 * This Simple fixture adds one website and tracks one visit with couple pageviews and an ecommerce conversion
 */
class SendSystemReportTaskFixture extends Fixture
{
    public $dateTime = '2013-01-23 01:23:45';
    public $idSite = 1;

    public function setUp()
    {
        $this->setUpWebsite();
        $this->executeSomeApiMethods();

        $settings = new Settings();
        $settings->trackToPiwik->setValue(false);
        $settings->ownPiwikSiteId->setValue($this->idSite);
        $targets  = new Targets($settings);
        $trackers = new Trackers($targets);
        $customVars = new CustomVariables();

        $task = new Tasks($trackers, $customVars, new Events());
        $task->sendSystemReport();
    }

    public function tearDown()
    {
        // empty
    }

    private function setUpWebsite()
    {
        if (!self::siteCreated($this->idSite)) {
            $idSite = self::createWebsite($this->dateTime, $ecommerce = 1);
            $this->assertSame($this->idSite, $idSite);
        }
    }

    private function executeSomeApiMethods()
    {
        Request::processRequest('API.getPiwikVersion');
        Request::processRequest('API.getSettings');
        Request::processRequest('UsersManager.getUsers');
        Request::processRequest('API.getPiwikVersion');
    }

}