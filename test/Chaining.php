<?php
/**
 * Chaining Unit Tests
 * 
 * PHP Version 5.3
 * 
 * @category UnitTest
 * @package  TwicPics/URL
 * @author   TwicPics <hello@twic.pics>
 * @license  https://raw.githubusercontent.com/TwicPics/php_url/master/LICENSE MIT
 * @link     https://www.github.com/TwicPics/php_url
 */
$builder = new TwicPics\URL();

/**
 * Chaining Unit Tests Class
 * 
 * @category UnitTest
 * @package  TwicPics/URL
 * @author   TwicPics <hello@twic.pics>
 * @license  https://raw.githubusercontent.com/TwicPics/php_url/master/LICENSE MIT
 * @link     https://www.github.com/TwicPics/php_url
 */
class Chaining extends PHPUnit\Framework\TestCase
{

    /**
     * Set up
     * 
     * @return null
     */
    public function setUp()
    {
        $this->builder = new TwicPics\URL();
    }

    /**
     * Tests chaining
     * 
     * @return null
     */
    public function testChaining()
    {
        $this->assertSame(
            $this->builder
                ->format("png")
                ->cover("1:1")
                ->resize(500)
                ->src("<END>")
                ->url(),
            "https://i.twic.pics/v1/cover=1:1/resize=500/format=png/<END>"
        );
    }

    /**
     * Tests forking
     * 
     * @return null
     */
    public function testForking()
    {
        $src = $this->builder->focus("X", "Y")->src("<END>");
        $this->assertSame(
            $src->cover("1:1")->url(),
            "https://i.twic.pics/v1/focus=XxY/cover=1:1/<END>"
        );
        $this->assertSame(
            $src->resize("W")->url(),
            "https://i.twic.pics/v1/focus=XxY/resize=W/<END>"
        );
    }

    /**
     * Tests forking by src
     * 
     * @return null
     */
    public function testForkingBySrc()
    {
        $src = $this->builder->focus("X", "Y")->src("<END>");
        $cover = $this->builder->cover("1:1");
        $resize = $this->builder->resize("W");
        $this->assertSame(
            $cover->src($src)->url(),
            "https://i.twic.pics/v1/focus=XxY/cover=1:1/<END>"
        );
        $this->assertSame(
            $resize->src($src)->url(),
            "https://i.twic.pics/v1/focus=XxY/resize=W/<END>"
        );
    }
}
