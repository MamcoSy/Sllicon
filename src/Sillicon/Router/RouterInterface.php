<?php

namespace Sillicon\Router;

interface RouterInterface
{
    /**
     * Add a route to the routing table
     *
     * @param string $route
     * @param array $params
     * @return void
     */
    public function add( string $route, array $params ): void;

    /**
     * Dispatch route and create controller object and execute the method
     * on this controller object
     *
     * @param string $url
     * @return void
     */
    public function dispath( string $url ): void;
}
