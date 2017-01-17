<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('BoostCakePaginatorHelper', 'BoostCake.View/Helper');

/**
 * BootstrapPaginatorHelper Test Case

 */
class BoostCakePaginatorHelperTest extends CakeTestCase
{

    /**
     * setUp method
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $View = new View();
        $this->Paginator = new BoostCakePaginatorHelper($View);
    }

    /**
     * tearDown method
     * @return void
     */
    public function tearDown()
    {
        unset($this->Paginator);

        parent::tearDown();
    }

    /**
     * testPaginationEmpty
     * @return void
     */
    public function testPaginationEmpty()
    {
        $this->Paginator->request->params['paging']['Post'] = [
            'page'      => 1,
            'current'   => 0,
            'count'     => 0,
            'prevPage'  => false,
            'nextPage'  => false,
            'pageCount' => 1,
            'order'     => null,
            'limit'     => 20,
            'options'   => [
                'page'       => 1,
                'conditions' => [],
            ],
            'paramType' => 'named',
        ];
        $numbers = $this->Paginator->pagination(['model' => 'Post']);
        $this->assertSame('', $numbers);
    }

    /**
     * testPaginationTwoModel
     * @return void
     */
    public function testPaginationTwoModel()
    {
        $this->Paginator->request->params['paging']['Post'] = [
            'page'      => 1,
            'current'   => 0,
            'count'     => 0,
            'prevPage'  => false,
            'nextPage'  => false,
            'pageCount' => 1,
            'order'     => null,
            'limit'     => 20,
            'options'   => [
                'page'       => 1,
                'conditions' => [],
            ],
            'paramType' => 'named',
        ];
        $this->Paginator->request->params['paging']['Article'] = [
            'page'      => 1,
            'current'   => 0,
            'count'     => 40,
            'prevPage'  => false,
            'nextPage'  => true,
            'pageCount' => 2,
            'order'     => null,
            'limit'     => 20,
            'options'   => [
                'page'       => 1,
                'conditions' => [],
            ],
            'paramType' => 'named',
        ];

        $result = $this->Paginator->pagination([
            'model' => 'Article',
            'div'   => 'pagination',
        ]);

        $version = (float)Configure::version();
        $pageOne = '/index/page:1';
        if ($version >= 2.4) {
            $pageOne = '/';
        }

        $this->assertTags($result, [
            'div' => ['class' => 'pagination'],
            'ul'  => [],
            ['li' => ['class' => 'disabled']],
            ['a' => ['href' => $pageOne]],
            '&lt;',
            '/a',
            '/li',
            ['li' => ['class' => 'current disabled']],
            ['a' => ['href' => '#']],
            '1',
            '/a',
            '/li',
            ['li' => []],
            ['a' => ['href' => '/index/page:2']],
            '2',
            '/a',
            '/li',
            ['li' => []],
            ['a' => ['href' => '/index/page:2', 'rel' => 'next']],
            '&gt;',
            '/a',
            '/li',
            '/ul',
            '/div',
        ]);
    }

    /**
     * testPaginationTwo
     * @return void
     */
    public function testPaginationTwo()
    {
        $this->Paginator->request->params['paging']['Post'] = [
            'page'      => 1,
            'current'   => 0,
            'count'     => 40,
            'prevPage'  => false,
            'nextPage'  => true,
            'pageCount' => 2,
            'order'     => null,
            'limit'     => 20,
            'options'   => [
                'page'       => 1,
                'conditions' => [],
            ],
            'paramType' => 'named',
        ];

        $result = $this->Paginator->pagination([
            'model' => 'Post',
            'div'   => 'pagination',
        ]);

        $version = (float)Configure::version();
        $pageOne = '/index/page:1';
        if ($version >= 2.4) {
            $pageOne = '/';
        }

        $this->assertTags($result, [
            'div' => ['class' => 'pagination'],
            'ul'  => [],
            ['li' => ['class' => 'disabled']],
            ['a' => ['href' => $pageOne]],
            '&lt;',
            '/a',
            '/li',
            ['li' => ['class' => 'current disabled']],
            ['a' => ['href' => '#']],
            '1',
            '/a',
            '/li',
            ['li' => []],
            ['a' => ['href' => '/index/page:2']],
            '2',
            '/a',
            '/li',
            ['li' => []],
            ['a' => ['href' => '/index/page:2', 'rel' => 'next']],
            '&gt;',
            '/a',
            '/li',
            '/ul',
            '/div',
        ]);

        $result = $this->Paginator->pagination([
            'model' => 'Post',
            'ul'    => 'pagination',
        ]);

        $version = (float)Configure::version();
        $pageOne = '/index/page:1';
        if ($version >= 2.4) {
            $pageOne = '/';
        }

        $this->assertTags($result, [
            'ul' => ['class' => 'pagination'],
            ['li' => ['class' => 'disabled']],
            ['a' => ['href' => $pageOne]],
            '&lt;',
            '/a',
            '/li',
            ['li' => ['class' => 'current disabled']],
            ['a' => ['href' => '#']],
            '1',
            '/a',
            '/li',
            ['li' => []],
            ['a' => ['href' => '/index/page:2']],
            '2',
            '/a',
            '/li',
            ['li' => []],
            ['a' => ['href' => '/index/page:2', 'rel' => 'next']],
            '&gt;',
            '/a',
            '/li',
            '/ul',
        ]);
    }

    /**
     * testNumbersEmpty
     * @return void
     */
    public function testNumbersEmpty()
    {
        $this->Paginator->request->params['paging']['Post'] = [
            'page'      => 1,
            'current'   => 0,
            'count'     => 0,
            'prevPage'  => false,
            'nextPage'  => false,
            'pageCount' => 1,
            'order'     => null,
            'limit'     => 20,
            'options'   => [
                'page'       => 1,
                'conditions' => [],
            ],
            'paramType' => 'named',
        ];
        $numbers = $this->Paginator->numbers(['model' => 'Post']);
        $this->assertSame('', $numbers);
    }

    /**
     * testNumbersSimple
     * @return void
     */
    public function testNumbersSimple()
    {
        $this->Paginator->request->params['paging']['Post'] = [
            'page'      => 1,
            'current'   => 20,
            'count'     => 100,
            'prevPage'  => false,
            'nextPage'  => true,
            'pageCount' => 5,
            'order'     => null,
            'limit'     => 20,
            'options'   => [
                'page'       => 1,
                'conditions' => [],
            ],
            'paramType' => 'named',
        ];

        $result = $this->Paginator->numbers(['model' => 'Post']);
        $this->assertTags($result, [
            ['li' => ['class' => 'current disabled']],
            ['a' => ['href' => '#']],
            '1',
            '/a',
            '/li',
            ['li' => []],
            ['a' => ['href' => '/index/page:2']],
            '2',
            '/a',
            '/li',
            ['li' => []],
            ['a' => ['href' => '/index/page:3']],
            '3',
            '/a',
            '/li',
            ['li' => []],
            ['a' => ['href' => '/index/page:4']],
            '4',
            '/a',
            '/li',
            ['li' => []],
            ['a' => ['href' => '/index/page:5']],
            '5',
            '/a',
            '/li',
        ]);
    }

    /**
     * testNumbersElipsis
     * @return void
     */
    public function testNumbersElipsis()
    {
        $this->Paginator->request->params['paging']['Post'] = [
            'page'      => 10,
            'current'   => 20,
            'count'     => 1000,
            'prevPage'  => true,
            'nextPage'  => true,
            'pageCount' => 200,
            'order'     => null,
            'limit'     => 20,
            'options'   => [
                'page'       => 1,
                'conditions' => [],
            ],
            'paramType' => 'named',
        ];

        $result = $this->Paginator->numbers([
            'model'   => 'Post',
            'modulus' => 8,
            'first'   => 1,
            'last'    => 1,
        ]);

        $version = (float)Configure::version();
        $pageOne = '/index/page:1';
        if ($version >= 2.4) {
            $pageOne = '/';
        }

        $this->assertTags($result, [
            ['li' => []],
            ['a' => ['href' => $pageOne]],
            '1',
            '/a',
            '/li',
            ['li' => ['class' => 'disabled']],
            ['a' => ['href' => '#']],
            '…',
            '/a',
            '/li',
            ['li' => []],
            ['a' => ['href' => '/index/page:6']],
            '6',
            '/a',
            '/li',
            ['li' => []],
            ['a' => ['href' => '/index/page:7']],
            '7',
            '/a',
            '/li',
            ['li' => []],
            ['a' => ['href' => '/index/page:8']],
            '8',
            '/a',
            '/li',
            ['li' => []],
            ['a' => ['href' => '/index/page:9']],
            '9',
            '/a',
            '/li',
            ['li' => ['class' => 'current disabled']],
            ['a' => ['href' => '#']],
            '10',
            '/a',
            '/li',
            ['li' => []],
            ['a' => ['href' => '/index/page:11']],
            '11',
            '/a',
            '/li',
            ['li' => []],
            ['a' => ['href' => '/index/page:12']],
            '12',
            '/a',
            '/li',
            ['li' => []],
            ['a' => ['href' => '/index/page:13']],
            '13',
            '/a',
            '/li',
            ['li' => []],
            ['a' => ['href' => '/index/page:14']],
            '14',
            '/a',
            '/li',
            ['li' => ['class' => 'disabled']],
            ['a' => ['href' => '#']],
            '…',
            '/a',
            '/li',
            ['li' => []],
            ['a' => ['href' => '/index/page:200']],
            '200',
            '/a',
            '/li',
        ]);
    }

    /**
     * testPager
     * @return void
     */
    public function testPager()
    {
        $this->Paginator->request->params['paging']['Post'] = [
            'page'      => 10,
            'current'   => 20,
            'count'     => 1000,
            'prevPage'  => true,
            'nextPage'  => true,
            'pageCount' => 200,
            'order'     => null,
            'limit'     => 20,
            'options'   => [
                'page'       => 1,
                'conditions' => [],
            ],
            'paramType' => 'named',
        ];

        $result = $this->Paginator->pager();
        $this->assertTags($result, [
            'ul' => ['class' => 'pager'],
            ['li' => ['class' => 'previous']],
            ['a' => ['href' => '/index/page:9', 'rel' => 'prev']],
            'Previous',
            '/a',
            '/li',
            ['li' => ['class' => 'next']],
            ['a' => ['href' => '/index/page:11', 'rel' => 'next']],
            'Next',
            '/a',
            '/li',
            '/ul',
        ]);
    }

}
