<?php
namespace App\Lib;

use Illuminate\Routing\ResourceRegistrar;

class CustomRegistrar extends ResourceRegistrar
{
    protected $resourceDefaults = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy', 'translate', 'translation'];
    protected function addResourceTranslate($name, $base, $controller, $options)
    {
        $name = $this->getShallowName($name, $options);

        $uri = $this->getResourceUri($name).'/{'.$base.'}/'.'translate';

        $action = $this->getResourceAction($name, $controller, 'translate', $options);

        return $this->router->get($uri, $action);
    }
    protected function addResourceTranslation($name, $base, $controller, $options)
    {
        $name = $this->getShallowName($name, $options);

        $uri = $this->getResourceUri($name).'/{'.$base.'}/'.'translate';

        $action = $this->getResourceAction($name, $controller, 'translation', $options);

        return $this->router->match(['PUT', 'PATCH'], $uri, $action);
    }
}
