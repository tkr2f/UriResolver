<?php

use Tkr2f\UriResolver\UriResolver;

/**
 * Class UriResolverTest
 * @author Takashi Iwata <x.takashi.iwata.x@gmail.com>
 */
class UriResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UriResolver
     */
    protected $UriResolver;

    protected function setUp()
    {
        $this->UriResolver = new Tkr2f\UriResolver\UriResolver();
    }

    public function testResolveNormalPattern()
    {
        //RFC3986 5.4.1
        $this->assertEquals('g:h', $this->UriResolver->resolve('http://a/b/c/d;p?q', 'g:h'));
        $this->assertEquals('http://a/b/c/g', $this->UriResolver->resolve('http://a/b/c/d;p?q', 'g'));
        $this->assertEquals('http://a/b/c/g', $this->UriResolver->resolve('http://a/b/c/d;p?q', './g'));
        $this->assertEquals('http://a/b/c/g/', $this->UriResolver->resolve('http://a/b/c/d;p?q', 'g/'));
        $this->assertEquals('http://a/g', $this->UriResolver->resolve('http://a/b/c/d;p?q', '/g'));
        $this->assertEquals('http://g', $this->UriResolver->resolve('http://a/b/c/d;p?q', '//g'));
        $this->assertEquals('http://a/b/c/d;p?y', $this->UriResolver->resolve('http://a/b/c/d;p?q', '?y'));
        $this->assertEquals('http://a/b/c/g?y', $this->UriResolver->resolve('http://a/b/c/d;p?q', 'g?y'));
        $this->assertEquals('http://a/b/c/d;p?q#s', $this->UriResolver->resolve('http://a/b/c/d;p?q', '#s'));
        $this->assertEquals('http://a/b/c/g#s', $this->UriResolver->resolve('http://a/b/c/d;p?q', 'g#s'));
        $this->assertEquals('http://a/b/c/g?y#s', $this->UriResolver->resolve('http://a/b/c/d;p?q', 'g?y#s'));
        $this->assertEquals('http://a/b/c/;x', $this->UriResolver->resolve('http://a/b/c/d;p?q', ';x'));
        $this->assertEquals('http://a/b/c/g;x', $this->UriResolver->resolve('http://a/b/c/d;p?q', 'g;x'));
        $this->assertEquals('http://a/b/c/g;x?y#s', $this->UriResolver->resolve('http://a/b/c/d;p?q', 'g;x?y#s'));
        $this->assertEquals('http://a/b/c/d;p?q', $this->UriResolver->resolve('http://a/b/c/d;p?q', ''));
        $this->assertEquals('http://a/b/c/', $this->UriResolver->resolve('http://a/b/c/d;p?q', '.'));
        $this->assertEquals('http://a/b/c/', $this->UriResolver->resolve('http://a/b/c/d;p?q', './'));
        $this->assertEquals('http://a/b/', $this->UriResolver->resolve('http://a/b/c/d;p?q', '..'));
        $this->assertEquals('http://a/b/', $this->UriResolver->resolve('http://a/b/c/d;p?q', '../'));
        $this->assertEquals('http://a/b/g', $this->UriResolver->resolve('http://a/b/c/d;p?q', '../g'));
        $this->assertEquals('http://a/', $this->UriResolver->resolve('http://a/b/c/d;p?q', '../..'));
        $this->assertEquals('http://a/', $this->UriResolver->resolve('http://a/b/c/d;p?q', '../../'));
        $this->assertEquals('http://a/g', $this->UriResolver->resolve('http://a/b/c/d;p?q', '../../g'));
    }

    public function testResolvePeculiarPattern()
    {
        //RFC3986 5.4.2
        $this->assertEquals('http://a/g', $this->UriResolver->resolve('http://a/b/c/d;p?q', '../../../g'));
        $this->assertEquals('http://a/g', $this->UriResolver->resolve('http://a/b/c/d;p?q', '../../../../g'));
        $this->assertEquals('http://a/g', $this->UriResolver->resolve('http://a/b/c/d;p?q', '/./g'));
        $this->assertEquals('http://a/g', $this->UriResolver->resolve('http://a/b/c/d;p?q', '/../g'));
        $this->assertEquals('http://a/b/c/g.', $this->UriResolver->resolve('http://a/b/c/d;p?q', 'g.'));
        $this->assertEquals('http://a/b/c/.g', $this->UriResolver->resolve('http://a/b/c/d;p?q', '.g'));
        $this->assertEquals('http://a/b/c/g..', $this->UriResolver->resolve('http://a/b/c/d;p?q', 'g..'));
        $this->assertEquals('http://a/b/c/..g', $this->UriResolver->resolve('http://a/b/c/d;p?q', '..g'));
        $this->assertEquals('http://a/b/g', $this->UriResolver->resolve('http://a/b/c/d;p?q', './../g'));
        $this->assertEquals('http://a/b/c/g/', $this->UriResolver->resolve('http://a/b/c/d;p?q', './g/.'));
        $this->assertEquals('http://a/b/c/g/h', $this->UriResolver->resolve('http://a/b/c/d;p?q', 'g/./h'));
        $this->assertEquals('http://a/b/c/h', $this->UriResolver->resolve('http://a/b/c/d;p?q', 'g/../h'));
        $this->assertEquals('http://a/b/c/g;x=1/y', $this->UriResolver->resolve('http://a/b/c/d;p?q', 'g;x=1/./y'));
        $this->assertEquals('http://a/b/c/y', $this->UriResolver->resolve('http://a/b/c/d;p?q', 'g;x=1/../y'));
        $this->assertEquals('http://a/b/c/g?y/./x', $this->UriResolver->resolve('http://a/b/c/d;p?q', 'g?y/./x'));
        $this->assertEquals('http://a/b/c/g?y/../x', $this->UriResolver->resolve('http://a/b/c/d;p?q', 'g?y/../x'));
        $this->assertEquals('http://a/b/c/g#s/./x', $this->UriResolver->resolve('http://a/b/c/d;p?q', 'g#s/./x'));
        $this->assertEquals('http://a/b/c/g#s/../x', $this->UriResolver->resolve('http://a/b/c/d;p?q', 'g#s/../x'));
        $this->assertEquals('http:g', $this->UriResolver->resolve('http://a/b/c/d;p?q', 'http:g'));
    }
}