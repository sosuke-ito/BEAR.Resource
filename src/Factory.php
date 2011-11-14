<?php
/**
 * BEAR.Resource;
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace BEAR\Resource;

use BEAR\Resource\Object as ResourceObject,
    Ray\Di\InjectorInterface;

/**
 * Resource object factory.
 *
 * @package BEAR.Resource
 * @author  Akihito Koriyama <akihito.koriyama@gmail.com>
 *
 * @Scope("singleton")
 */
class Factory implements ResourceFactory
{
    /**
     * Resource adapter
     *
     * @var array
     */
    private $resourceAdapters = array();

    /**
     * Construcotr
     *
     * @param Injector $injector
     * @param array $schemaAdapters
     *
     * @Inject
     * @Named("resourceAdapters=ResourceAdapters")
     */
    public function __construct(InjectorInterface $injector, array $resourceAdapters)
    {
        $this->injector = $injector;
        $this->resourceAdapters = $resourceAdapters;
    }

    /**
     * (non-PHPdoc)
     * @see BEAR\Resource.ResourceFactory::newInstance()
     */
    public function newInstance($uri, $defaultQuery = array())
    {
        $parsedUrl = parse_url($uri);
        $scheme = $parsedUrl['scheme'];
        if (!isset($this->resourceAdapters[$scheme])) {
            throw new Exception\InvalidScheme($scheme);
        }
        $adapter = $this->resourceAdapters[$scheme];
        if ($adapter instanceof Provider) {
            $adapter = $adapter->get($uri);
        }
        $adapter->uri = $uri;
        return $adapter;
    }
}