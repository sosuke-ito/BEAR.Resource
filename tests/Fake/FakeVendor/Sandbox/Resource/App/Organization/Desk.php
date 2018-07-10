<?php

declare(strict_types=1);
/**
 * This file is part of the BEAR.Resource package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace FakeVendor\Sandbox\Resource\App\Organization;

use BEAR\Resource\ResourceObject;

class Desk extends ResourceObject
{
    public function onPost($name)
    {
        $this->body = [
            'id' => 0,
            'name' => $name
        ];

        return $this;
    }
}
