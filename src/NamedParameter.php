<?php
/**
 * This file is part of the BEAR.Resource package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace BEAR\Resource;

use BEAR\Resource\Annotation\CompileCache;
use BEAR\Resource\Exception\ParameterException;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;

final class NamedParameter implements NamedParameterInterface
{
    /**
     * @var Cache
     */
    private $cache;

    /**
     * @var ParamHandlerInterface
     */
    private $handler;

    /**
     * @param Cache $cache
     *
     * @CompileCache("cache")
     */
    public function __construct(Cache $cache, ParamHandlerInterface $handler)
    {
        $this->cache = $cache;
        $this->handler = $handler;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(array $callable, array $query)
    {
        $id = __CLASS__ . get_class($callable[0]) . $callable[1];
        $names = $this->cache->fetch($id);
        if (! $names) {
            $names = $this->getParameterNames($callable);
            $this->cache->save($id, $names);
        }
        $parameters = [];
        foreach ($names as $name => $defaultValue) {
            $value = isset($query[$name]) ? $query[$name] : $defaultValue;
            if ($value instanceof Param) {
                $parameter = new \ReflectionParameter([$value->class, $value->method], $value->param);
                $value = $this->handler->handle($parameter);
            }
            $parameters[] = $value;
        };

        return $parameters;
    }

    private function getParameterNames(array $callable)
    {
        $parameters = (new \ReflectionMethod($callable[0], $callable[1]))->getParameters();
        $names = [];
        foreach ($parameters as $parameter) {
            $default = $parameter->isDefaultValueAvailable() === true ? $parameter->getDefaultValue() : new Param(get_class($callable[0]), $callable[1], $parameter->getName());
            $names[$parameter->name] = $default;
        }

        return $names;
    }
}
