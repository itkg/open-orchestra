<?php

namespace OpenOrchestra\FunctionalTests\UserAdminBundle\Repository;

use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractKernelTestCase;
use OpenOrchestra\Pagination\Configuration\FinderConfiguration;
use OpenOrchestra\Pagination\Configuration\PaginateFinderConfiguration;
use OpenOrchestra\UserBundle\Repository\UserRepository;

/**
 * Class UserRepositoryTest
 *
 * @group integrationTest
 */
class UserRepositoryTest extends AbstractKernelTestCase
{
    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * Set up test
     */
    protected function setUp()
    {
        parent::setUp();

        static::bootKernel();
        $this->repository = static::$kernel->getContainer()->get('open_orchestra_user.repository.user');
    }

    /**
     * @param array  $descriptionEntity
     * @param array  $search
     * @param array  $order
     * @param int    $skip
     * @param int    $limit
     * @param int    $count
     *
     * @dataProvider providePaginateAndSearch
     */
    public function testFindForPaginate($descriptionEntity, $search, $order, $skip, $limit, $count)
    {
        $this->markTestSkipped('To unskip when group list is refacto');
        $configuration = PaginateFinderConfiguration::generateFromVariable($descriptionEntity, $search);
        $configuration->setPaginateConfiguration($order, $skip, $limit);
        $users = $this->repository->findForPaginate($configuration);
        $this->assertCount($count, $users);
    }

    /**
     * @return array
     */
    public function providePaginateAndSearch()
    {
        $descriptionEntity = $this->getDescriptionColumnEntity();

        return array(
            array($descriptionEntity, null, null, 0 ,5 , 5),
            array($descriptionEntity, $this->generateSearchProvider('admin'), null, 0 ,5 , 2),
            array($descriptionEntity, $this->generateSearchProvider('fakeUsername'), null, 0 ,5 , 0),
            array($descriptionEntity, $this->generateSearchProvider('', 'user'), null, 0 ,5 , 5),
        );
    }

    /**
     * test count all user
     */
    public function testCount()
    {
        $configuration = FinderConfiguration::generateFromVariable($this->getDescriptionColumnEntity(), array());
        $users = $this->repository->count($configuration);
        $this->assertEquals(9, $users);
    }

    /**
     * @param array  $descriptionEntity
     * @param array  $search
     * @param int    $count
     *
     * @dataProvider provideColumnsAndSearchAndCount
     */
    public function testCountWithFilter($descriptionEntity, $search, $count)
    {
        $configuration = FinderConfiguration::generateFromVariable($descriptionEntity, $search);
        $users = $this->repository->countWithFilter($configuration);
        $this->assertEquals($count, $users);
    }

    /**
     * @return array
     */
    public function provideColumnsAndSearchAndCount()
    {
        $descriptionEntity = $this->getDescriptionColumnEntity();

        return array(
            array($descriptionEntity, null, 9),
            array($descriptionEntity, $this->generateSearchProvider('admin'), 2),
            array($descriptionEntity, $this->generateSearchProvider('user'), 5),
            array($descriptionEntity, $this->generateSearchProvider('', 'admin'), 2),
        );
    }

    /**
     * @param $username
     * @param $groupName
     * @param $countUser
     *
     * @dataProvider provideUserAndGroup
     */
    public function testFindByIncludedUsernameWithoutGroup($username, $groupName, $countUser)
    {
        $group =  static::$kernel->getContainer()->get('open_orchestra_user.repository.group')->findOneByName($groupName);
        $users = $this->repository->findByIncludedUsernameWithoutGroup($username, $group);

        $this->assertCount($countUser, $users);
    }

    public function provideUserAndGroup()
    {
        return array(
            array('de', 'Empty group', 2),
            array('admi', 'Empty group', 2),
            array('user', 'Empty group', 5),
            array('fakeUser', 'Demo group', 0)
        );
    }

    /**
     * Generate columns of content with search value
     *
     * @param string $searchUsername
     * @param string $globalSearch
     *
     * @return array
     */
    protected function generateSearchProvider($searchUsername = '', $globalSearch = '')
    {
        $search = array();
        if (!empty($searchUsername)) {
            $search['columns'] = array('username' => $searchUsername);
        }
        if (!empty($globalSearch)) {
            $search['global'] = $globalSearch;
        }

        return $search;
    }

    /**
     * Generate relation between columns names and entities attributes
     *
     * @return array
     */
    protected function getDescriptionColumnEntity()
    {
        return array(
            'username' => array('key' => 'username', 'field' => 'username', 'type' => 'string')
        );
    }

}
