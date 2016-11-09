<?php

namespace OpenOrchestra\FunctionalTests\BackofficeBundle\Controller;

use OpenOrchestra\FunctionalTests\Utils\AbstractFormTest;
use OpenOrchestra\ModelInterface\Model\NodeInterface;
use OpenOrchestra\ModelInterface\Repository\NodeRepositoryInterface;

/**
 * Class EditNodeControllerTest
 *
 * @group backofficeTest
 */
class EditNodeControllerTest extends AbstractFormTest
{
    /**
     * @var NodeRepositoryInterface
     */
    protected $nodeRepository;
    protected $language = 'fr';
    protected $siteId = '2';

    /**
     * Set up the test
     */
    public function setUp()
    {
        parent::setUp();
        $this->nodeRepository = static::$kernel->getContainer()->get('open_orchestra_model.repository.node');
    }

    /**
     * @param string $expectedMeta
     * @param string $newMeta
     * @param string $nodeId
     *
     * @dataProvider provideMetaAndNodeId
     */
    public function testEditNode($expectedMeta, $newMeta, $nodeId)
    {
        $this->markTestSkipped();

        $nodeDocument = $this->nodeRepository->findInLastVersion($nodeId, $this->language, $this->siteId);

        $url = '/admin/node/form/' . $nodeDocument->getId();
        $crawler = $this->client->request('GET', $url);
        $formNode = $crawler->selectButton('Save')->form();
        $formNode['oo_node[metaKeywords]'] = $newMeta;

        $crawler = $this->submitForm($formNode);

        $this->assertContains('alert alert-success', $this->client->getResponse()->getContent());
        $formNode = $crawler->selectButton('Save')->form();
        $this->assertSame($expectedMeta, $formNode['oo_node[metaKeywords]']->getValue());
    }

    /**
     * @return array
     */
    public function provideMetaAndNodeId()
    {
        return array(
            array('foo', 'foo', NodeInterface::ROOT_NODE_ID),
            array('', 'bar', 'fixture_page_news'),
        );
    }
}
