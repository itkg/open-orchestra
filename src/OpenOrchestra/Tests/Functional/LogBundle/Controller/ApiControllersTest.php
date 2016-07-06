<?php

namespace OpenOrchestra\FunctionalTests\LogBundle\Controller;

use OpenOrchestra\FunctionalTests\Utils\AbstractAuthentificatedTest;

/**
 * Class ApiControllersTest
 *
 * @group apiFunctional
 */
class ApiControllersTest extends AbstractAuthentificatedTest
{
    /**
     * @param string $url
     *
     * @dataProvider provideApiUrl
     */
    public function testApi($url)
    {
        $this->client->request('GET', $url . '?access_token=' . $this->getAccessToken());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame('application/json', $this->client->getResponse()->headers->get('content-type'));
    }

    /**
     * @return array
     */
    public function provideApiUrl()
    {
        return array(
            array('/api/log'),
        );
    }
}
