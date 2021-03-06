<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Elastica\Tests\Field;

use FSi\Component\DataSource\Driver\Elastica\Tests\BaseTest;

class TimeTest extends BaseTest
{
    public function setUp()
    {
        $mapping = [
            'timestamp' => ['type' => 'date', 'format' => 'basic_time_no_millis'],
        ];
        $this->dataSource = $this->prepareIndex('time_index', 'time_index', $mapping, function ($fixture) {
            $time = new \DateTime($fixture['timestamp']);
            $fixture['timestamp'] = $time->format('HisO');

            return $fixture;
        });
    }

    public function testFilterByEmptyParameter()
    {
        $this->dataSource->addField('timestamp', 'time', 'eq');

        $result = $this->filterDataSource(['timestamp' => '']);
        $this->assertEquals(11, count($result));

        $result = $this->filterDataSource(['timestamp' => null]);
        $this->assertEquals(11, count($result));

        $result = $this->filterDataSource(['timestamp' => []]);
        $this->assertEquals(11, count($result));
    }

    public function testFilterByTimeEq()
    {
        $this->dataSource->addField('timestamp', 'time', 'eq');
        $result = $this->filterDataSource(['timestamp' => new \DateTime('T23:01:16+0200')]);

        $this->assertEquals(1, count($result));
    }

    public function testFilterByTimeGt()
    {
        $this->dataSource->addField('timestamp', 'time', 'gt');
        $result = $this->filterDataSource(['timestamp' => new \DateTime('T22:02:16+0200')]);

        $this->assertEquals(2, count($result));
    }

    public function testFilterByTimeGte()
    {
        $this->dataSource->addField('timestamp', 'time', 'gte');
        $result = $this->filterDataSource(['timestamp' => new \DateTime('T22:02:16+0200')]);

        $this->assertEquals(3, count($result));
    }

    public function testFilterByTimeLt()
    {
        $this->dataSource->addField('timestamp', 'time', 'lt');
        $result = $this->filterDataSource(['timestamp' => new \DateTime('T22:02:16+0200')]);

        $this->assertEquals(8, count($result));
    }

    public function testFilterByTimeLte()
    {
        $this->dataSource->addField('timestamp', 'time', 'lte');
        $result = $this->filterDataSource(['timestamp' => new \DateTime('T22:02:16+0200')]);

        $this->assertEquals(9, count($result));
    }

    public function testFilterByTimeBetween()
    {
        $this->dataSource->addField('timestamp', 'time', 'between');
        $result = $this->filterDataSource(
            [
                'timestamp' => [
                    'from' => new \DateTime('T14:10:16+0200'),
                    'to' => new \DateTime('T17:07:16+0200'),
                ]
            ]
        );

        $this->assertEquals(4, count($result));
    }
}
