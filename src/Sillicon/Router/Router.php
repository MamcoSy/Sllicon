<?php

namespace Sillicon\Router;

use Exception;

class Router implements RouterInterface
{
    /**
     * Collection of routes
     * @var array
     */
    protected array $routeCollection;

    /**
     * Contain Matched Params of a route
     * @var array
     */
    protected array $params;

    /**
     * Controller Suffix that will be added in the controller name
     * @var string
     */
    protected string $controllerSuffix;

    /**
     * @inheritDoc
     */
    public function add( string $route, array $params ): void
    {
        $this->routeCollection[$route] = $params;
    }

    /**
     * @inheritDoc
     */
    public function dispath( string $url ): void
    {
        if ( $this->match( $url ) ) {
            $controllerName = $this->params['controller'];
            $controllerName = $this->transformToUperCamelCase( $controllerName );
            $controllerName = $this->getNamespace( $controllerName ) . $controllerName;
            if ( class_exists( $controllerName ) ) {
                $controllerObject = new $controllerName;
                $action           = $this->params['action'];
                $action           = $this->transformToCamelCase( $action );
                if ( method_exists( $controllerObject, $action ) && is_callable( [$controllerObject, $action] ) ) {
                    $controllerObject->$action;
                } else {
                    throw new Exception( "method not found", 1 );
                }
            } else {
                throw new Exception( "class not found", 1 );
            }
        } else {
            throw new Exception( "404 error", 1 );
        }
    }

    /**
     * Match te given url witch the routes in routeCollection
     * if route is found $this->params will be set
     *
     * @param string $url
     * @return boolean
     */
    private function match( string $url ): bool
    {
        foreach ( $this->routeCollection as $route => $params ) {
            if ( preg_match( $route, $url, $matches ) ) {
                foreach ( $matches as $key => $value ) {
                    if ( is_string( $key ) ) {
                        $params[$key] = $value;
                    }
                }
                $this->params = $params;

                return true;
            }

            return false;
        }
    }

    /**
     * Trasform the given string to UpperCamelCase
     *
     * @param string $string
     * @return string
     */
    private function transformToUperCamelCase( string $string ): string
    {
        return str_replace( ' ', '', ucwords( str_replace( '-', '', $string ) ) );
    }

    /**
     * Trasform the given string to CamelCase
     *
     * @param string $string
     * @return string
     */
    private function transformToCamelCase( string $string ): string
    {
        return lcfirst( $this->transformToUperCamelCase( $string ) );
    }

    /**
     * Getting the namespace for the given controller class
     *
     * @param string $calssName
     * @return string
     */
    public function getNamespace( string $calssName ): string
    {
        $defaultNamespace = 'App\\Controller\\';
        if ( array_key_exists( 'namespace', $this->params ) ) {
            $defaultNamespace .= $this->params['namespace'];
        }

        return $defaultNamespace;
    }
}
