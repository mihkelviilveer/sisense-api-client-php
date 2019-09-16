<?php

namespace Tests\Api;

/**
 * Class DashboardsTest
 */
class DashboardsTest extends BaseApiTest
{
    /**
     * @covers \Sisense\Api\Dashboards::getOwnedDashboards
     */
    public function testGetOwnedDashboards()
    {
        $this->expects('v1/dashboards/', 'GET', ['query' => ['foo' => 'bar']]);

        $this->clientMock->dashboards->getOwnedDashboards(['foo' => 'bar']);
    }
}
