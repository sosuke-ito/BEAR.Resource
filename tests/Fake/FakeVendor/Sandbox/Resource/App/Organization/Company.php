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

class Company extends ResourceObject
{
    /**
     * @Link(rel="representative", href="app://self/organization/member?name={representative_name}", method="post", crawl="company")
     */
    public function onPost($representative_name, $name)
    {
        $this->body = [
            'id' => 0,
            'name' => $name,
            'representative_name' => $representative_name
        ];

        return $this;
    }
}
