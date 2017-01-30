<?php

namespace OpenOrchestra\FunctionalTests\ApiBundle\Controller;

use OpenOrchestra\FunctionalTests\Utils\AbstractAuthenticatedTest;

/**
 * Class ApiControllersTest
 *
 * @group apiFunctional
 */
class ApiControllersTest extends AbstractAuthenticatedTest
{

    /**
     * test duplicate Api
     *
     *
     * @dataProvider provideDuplicateApiElements
     */
    public function testDuplicateApi($repositoryName, $method, $value, $type, $url)
    {
        $repository = static::$kernel->getContainer()->get($repositoryName);
        $source = $repository->$method($value);
        $source = static::$kernel->getContainer()->get('open_orchestra_api.transformer_manager')->get($type)->transform($source);
        $source = static::$kernel->getContainer()->get('jms_serializer')->serialize(
            $source,
            'json'
            );
        $this->client->request("POST", $url, array(), array(), array(), $source);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame('application/json', $this->client->getResponse()->headers->get('content-type'));

        $element = $repository->$method(new \MongoRegex('/^'.$value.'_.*$/'));
        while (!is_null($element)) {
            static::$kernel->getContainer()->get('object_manager')->remove($element);
            static::$kernel->getContainer()->get('object_manager')->flush();
            $element = $repository->$method(new \MongoRegex('/^'.$value.'_.*$/'));
        }
    }

    /**
     * @return array
     */
    public function provideDuplicateApiElements()
    {
        return array(
            0  => array('open_orchestra_user.repository.group', 'findOneByName', 'Demo group', 'group', '/api/group/duplicate'),
            1  => array('open_orchestra_model.repository.content', 'findOneByContentId', '206_3_portes', 'content', '/api/content/duplicate'),
        );
    }

    /**
     * @param string $url
     * @param string $getParameter
     *
     * @dataProvider provideApiUrl
     */
    public function testApi($url, $getParameter = '', $method = 'GET')
    {
        $baseGetParameter = '?access_token=' . $this->getAccessToken();
        $this->client->request($method, $url . $baseGetParameter . $getParameter);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame('application/json', $this->client->getResponse()->headers->get('content-type'));
    }

    /**
     * @return array
     */
    public function provideApiUrl()
    {
        return array(
            1  => array('/api/node/show/root/2/fr'),
            5  => array('/api/node/list/not-published-by-author'),
            6  => array('/api/node/list/by-author'),
            7  => array('/api/node/list/2/fr'),
            8  => array('/api/block/list/shared/fr'),
            //8  => array('/api/content'),
            9  => array('/api/content/list/by-author'),
            10 => array('/api/content/list/not-published-by-author'),
            //11 => array('/api/content', '&content_type=news'),
            12 => array('/api/content-type'),
            13 => array('/api/site'),
            14 => array('/api/site/list/available'),
            // 15 => array('/api/theme'),
            17 => array('/api/group/delete-multiple', '', 'DELETE'),
            //18 => array('/api/redirection'),
            //19 => array('/api/status'),
            //20 => array('/api/status/list'),
            // 22 => array('/api/trashcan/list'),
            23 => array('/api/translation/tinymce'),
            24  => array('/api/node/list/tree/2/fr'),
            25  => array('/api/node/list/tree/2/fr/root'),
            26  => array('/api/group/user/list'),
            27  => array('/api/group/list'),
            28  => array('/api/block/list/block-component'),
            29  => array('/api/content-type/content/content-type-list'),
            30 => array('/api/content/delete-multiple', '', 'DELETE'),
            31 => array('/api/keyword/delete-multiple', "DELETE"),
            32 => array('/api/keyword'),
        );
    }
}
