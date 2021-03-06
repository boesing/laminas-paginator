<?php

/**
 * @see       https://github.com/laminas/laminas-paginator for the canonical source repository
 * @copyright https://github.com/laminas/laminas-paginator/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-paginator/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Paginator\Adapter;

use Laminas\Paginator\Adapter\Callback;
use PHPUnit\Framework\TestCase;

/**
 * @covers  Laminas\Paginator\Adapter\Callback<extended>
 */
class CallbackTest extends TestCase
{
    public function testMustDefineTwoCallbacksOnConstructor()
    {
        $itemsCallback = function () {
            return [];
        };
        $countCallback = function () {
            return 0;
        };
        $adapter = new Callback($itemsCallback, $countCallback);

        $this->assertAttributeSame($itemsCallback, 'itemsCallback', $adapter);
        $this->assertAttributeSame($countCallback, 'countCallback', $adapter);
    }

    public function testShouldAcceptAnyCallableOnConstructor()
    {
        $itemsCallback = function () {
            return range(1, 10);
        };
        $countCallback = 'rand';
        $adapter = new Callback($itemsCallback, $countCallback);

        $this->assertAttributeInternalType('callable', 'itemsCallback', $adapter);
        $this->assertAttributeInternalType('callable', 'countCallback', $adapter);
    }

    public function testMustRunItemCallbackToGetItems()
    {
        $data = range(1, 10);
        $itemsCallback = function () use ($data) {
            return $data;
        };
        $countCallback = function () {
        };
        $adapter = new Callback($itemsCallback, $countCallback);

        $this->assertSame($data, $adapter->getItems(0, 10));
    }

    public function testMustPassArgumentsToGetItemCallback()
    {
        $data = [0, 1, 2, 3];
        $itemsCallback = function ($offset, $itemCountPerPage) {
            return range($offset, $itemCountPerPage);
        };
        $countCallback = function () {
        };
        $adapter = new Callback($itemsCallback, $countCallback);

        $this->assertSame($data, $adapter->getItems(0, 3));
    }

    public function testMustRunCountCallbackToCount()
    {
        $count = 1988;
        $itemsCallback = function () {
        };
        $countCallback = function () use ($count) {
            return $count;
        };
        $adapter = new Callback($itemsCallback, $countCallback);

        $this->assertSame($count, $adapter->count());
    }
}
