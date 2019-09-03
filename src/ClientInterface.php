<?php

namespace Sisense;

use InvalidArgumentException;

interface ClientInterface
{
    /**
     * @param string $name
     *
     * @throws InvalidArgumentException
     *
     * @return Api\AbstractApi
     */
    public function api($name);
}
