<?php

namespace Sisense\Api;

/**
 * Class Dashboards
 *
 * @package Sisense\Api
 */
class Dashboards extends AbstractApi
{
    protected $resourcePath = 'v1/dashboards';

    /**
     * Get a user's dashboards
     *
     * @param array $parameters
     * @return array
     */
    public function getOwnedDashboards(array $parameters = []) : array
    {
        $path = $this->getPath();

        return $this->get($path, ['query' => $parameters]);
    }

    /**
     * Get a specific dashboard
     *
     * @param array $parameters
     * @return array
     */
    public function getDashboardById(string $id, array $parameters = []) : array
    {
        $path = $this->getPath($id);

        return $this->get($path, ['query' => $parameters]);
    }

    /**
     * Get a dashboards as .dash file
     *
     * @param array $dashboardIds
     * @return array
     */
    public function exportDashboardsAsDASH(array $dashboardIds) : array
    {
        $path = $this->getPath('export');

        $options = [
            'query' => $dashboardIds,
            'headers' => [
                'Accept' => 'application/octet-stream',
            ],
            'decode' => false,
        ];

        return $this->get($path, $options);
    }
}
