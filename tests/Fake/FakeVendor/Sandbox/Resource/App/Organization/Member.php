<?php

declare(strict_types=1);
/**
 * This file is part of the BEAR.Resource package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace FakeVendor\Sandbox\Resource\App\Organization;

use BEAR\Resource\Annotation\Link;
use BEAR\Resource\ResourceObject;

class Member extends ResourceObject
{
    /**
     * @Link(rel="desk", href="app://self/organization/desk?name={name}'s%20desk", method="post", crawl="company")
     */
    public function onPost($name)
    {
        $this->body = [
            'id' => 0,
            'name' => $name
        ];

        return $this;
    }
}
