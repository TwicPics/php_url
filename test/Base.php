<?php
/**
 * Base Unit Tests
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
 * Base Unit Tests Class
 * 
 * @category UnitTest
 * @package  TwicPics/URL
 * @author   TwicPics <hello@twic.pics>
 * @license  https://raw.githubusercontent.com/TwicPics/php_url/master/LICENSE MIT
 * @link     https://www.github.com/TwicPics/php_url
 */
class Base extends PHPUnit\Framework\TestCase
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
     * Generic test
     * 
     * @param string  $method   name of the method to test
     * @param mixed   $expected if null, an exception is expected else part
     *                          between domain and source URL
     * @param mixed[] ...$args  arguments for the method
     * 
     * @return null
     * 
     * @dataProvider list
     */
    public function testMethod( $method, $expected, ...$args )
    {
        if ($expected === null) {
            $this->expectException("DomainException");
            $this->builder->$method(...$args);
        } else {
            $expected = "https://i.twic.pics/v1/" . $expected . "/<END>";
            $newUrl = $this->builder->$method(...$args);
            $this->assertNotSame(
                $this->builder,
                $newUrl,
                "call creates a new object"
            );
            $this->assertSame($newUrl->src("<END>")->url(), $expected, $expected);
        }
    }

    /**
     * Returns the list of tests
     * 
     * @return mixed[][] the list of tests (args for testMethod)
     */
    public function list()
    {
        $tests = [];

        $tests[] = [
            "auth",
            "auth:aaaaaaaa-aaaa-4aaa-aaaa-aaaaaaaaaaaa",
            "aaaaaaaa-aaaa-4aaa-aaaa-aaaaaaaaaaaa"
        ];
        $tests[] = [ "auth", null, "not-a-valid-token" ];
        $tests[] = [ "auth", null ];

        $tests[] = [ "format", "format=jpeg", "jpeg" ];
        $tests[] = [ "format", "format=jpeg", [
            "type"=> "jpeg"
        ] ];
        $tests[] = [ "format", "format=jpeg", json_decode(
            '{
                "type": "jpeg"
            }'
        ) ];
        $tests[] = [ "format", "format=jpeg-80", "jpeg", 80 ];
        $tests[] = [ "format", "format=jpeg-80", [
            "quality" => 80,
            "type" => "jpeg"
        ] ];
        $tests[] = [ "format", "format=jpeg-80", json_decode(
            '{
                "quality": 80,
                "type": "jpeg"
            }'
        ) ];
        $tests[] = [ "format", "format=png", "png" ];
        $tests[] = [ "format", "format=png", [
            "type" => "png"
        ] ];
        $tests[] = [ "format", "format=png", json_decode(
            '{
                "type": "png"
            }'
        ) ];
        $tests[] = [ "format", null, "png", 80 ];
        $tests[] = [ "format", null, [
            "quality" => 80,
            "type" => "png"
        ] ];
        $tests[] = [ "format", null, json_decode(
            '{
                "quality": 80,
                "type": "png"
            }'
        ) ];
        $tests[] = [ "format", "format=webp", "webp" ];
        $tests[] = [ "format", "format=webp", [
            "type" => "webp"
        ] ];
        $tests[] = [ "format", "format=webp", json_decode(
            '{
                "type": "webp"
            }'
        ) ];
        $tests[] = [ "format", "format=webp-80", "webp", 80 ];
        $tests[] = [ "format", "format=webp-80", [
            "quality" => 80,
            "type" => "webp"
        ] ];
        $tests[] = [ "format", "format=webp-80", json_decode(
            '{
                "quality": 80,
                "type": "webp"
            }'
        ) ];
        $tests[] = [ "format", null, "unknown" ];
        $tests[] = [ "format", null, [
            "type" => "unknown"
        ] ];
        $tests[] = [ "format", null, json_decode(
            '{
                "type": "unknown"
            }'
        ) ];
        $tests[] = [ "format", null ];
        $tests[] = [ "format", null, [] ];
        $tests[] = [ "format", null, json_decode('{}') ];
        $tests[] = [ "format", null, null, 80 ];
        $tests[] = [ "format", null, [
            "quality" => 80
        ] ];
        $tests[] = [ "format", null, json_decode(
            '{
                "quality": 80
            }'
        ) ];

        $tests[] = [ "jpeg", "format=jpeg" ];
        $tests[] = [ "jpeg", "format=jpeg-80", 80 ];
        $tests[] = [ "png", "format=png" ];
        $tests[] = [ "png", "format=png", 80 ];
        $tests[] = [ "webp", "format=webp" ];
        $tests[] = [ "webp", "format=webp-80", 80 ];

        foreach ( 
            [
                "contain",
                "containMax",
                "containMin",
                "cover",
                "coverMax",
                "coverMin",
                "crop",
                "max",
                "min",
                "resize",
                "resizeMax",
                "resizeMin",
                "step",
            ] as $resizer
        ) {
            $nameInUrl = strtolower(
                preg_replace("/(.)(?=[A-Z])/u", "$1-", $resizer)
            );
            $tests[] = [ $resizer, $nameInUrl . "=W", "W" ];
            $tests[] = [ $resizer, $nameInUrl . "=W", [
                "width" => "W"
            ] ];
            $tests[] = [ $resizer, $nameInUrl . "=W", json_decode(
                '{
                    "width": "W"
                }'
            ) ];
            $tests[] = [ $resizer, $nameInUrl . "=WxH", "W", "H" ];
            $tests[] = [ $resizer, $nameInUrl . "=WxH", [
                "width" => "W",
                "height" => "H"
            ] ];
            $tests[] = [ $resizer, $nameInUrl . "=WxH", json_decode(
                '{
                    "width": "W",
                    "height": "H"
                }'
            ) ];
            $tests[] = [ $resizer, $nameInUrl . "=-xH", null, "H" ];
            $tests[] = [ $resizer, $nameInUrl . "=-xH", [
                "height" => "H"
            ] ];
            $tests[] = [ $resizer, $nameInUrl . "=-xH", json_decode(
                '{
                    "height": "H"
                }'
            ) ];
            $tests[] = [ $resizer, null ];
            $tests[] = [ $resizer, null, [] ];
            $tests[] = [ $resizer, null, json_decode('{}') ];
        }

        $tests[] = [ "crop", "crop=WxH@XxY", "W", "H", "X", "Y" ];
        $tests[] = [ "crop", "crop=WxH@XxY", [
            "width" => "W",
            "height" => "H",
            "x" => "X",
            "y" => "Y"
        ] ];
        $tests[] = [ "crop", "crop=WxH@XxY", json_decode(
            '{
                "width": "W",
                "height": "H",
                "x": "X",
                "y": "Y"
            }'
        ) ];
        $tests[] = [ "crop", "crop=W@XxY", "W", null, "X", "Y" ];
        $tests[] = [ "crop", "crop=W@XxY", [
            "width" => "W",
            "x" => "X",
            "y" => "Y"
        ] ];
        $tests[] = [ "crop", "crop=W@XxY", json_decode(
            '{
                "width": "W",
                "x": "X",
                "y": "Y"
            }'
        ) ];
        $tests[] = [ "crop", "crop=-xH@XxY", null, "H", "X", "Y" ];
        $tests[] = [ "crop", "crop=-xH@XxY", [
            "height" => "H",
            "x" => "X",
            "y" => "Y"
        ] ];
        $tests[] = [ "crop", "crop=-xH@XxY", json_decode(
            '{
                "height": "H",
                "x": "X",
                "y": "Y"
            }'
        ) ];
        $tests[] = [ "crop", "crop=WxH@X", "W", "H", "X" ];
        $tests[] = [ "crop", "crop=WxH@X", [
            "width" => "W",
            "height" => "H",
            "x" => "X"
        ] ];
        $tests[] = [ "crop", "crop=WxH@X", json_decode(
            '{
                "width": "W",
                "height": "H",
                "x": "X"
            }'
        ) ];
        $tests[] = [ "crop", "crop=W@X", "W", null, "X" ];
        $tests[] = [ "crop", "crop=W@X", [
            "width" => "W",
            "x" => "X"
        ] ];
        $tests[] = [ "crop", "crop=W@X", json_decode(
            '{
                "width": "W",
                "x": "X"
            }'
        ) ];
        $tests[] = [ "crop", "crop=-xH@X", null, "H", "X" ];
        $tests[] = [ "crop", "crop=-xH@X", [
            "height" => "H",
            "x" => "X",
        ] ];
        $tests[] = [ "crop", "crop=-xH@X", json_decode(
            '{
                "height": "H",
                "x": "X"
            }'
        ) ];
        $tests[] = [ "crop", "crop=WxH@-xY", "W", "H", null, "Y" ];
        $tests[] = [ "crop", "crop=WxH@-xY", [
            "width" => "W",
            "height" => "H",
            "y" => "Y"
        ] ];
        $tests[] = [ "crop", "crop=WxH@-xY", json_decode(
            '{
                "width": "W",
                "height": "H",
                "y": "Y"
            }'
        ) ];
        $tests[] = [ "crop", "crop=W@-xY", "W", null, null, "Y" ];
        $tests[] = [ "crop", "crop=W@-xY", [
            "width" => "W",
            "y" => "Y"
        ] ];
        $tests[] = [ "crop", "crop=W@-xY", json_decode(
            '{
                "width": "W",
                "y": "Y"
            }'
        ) ];
        $tests[] = [ "crop", "crop=-xH@-xY", null, "H", null, "Y" ];
        $tests[] = [ "crop", "crop=-xH@-xY", [
            "height" => "H",
            "y" => "Y"
        ] ];
        $tests[] = [ "crop", "crop=-xH@-xY", json_decode(
            '{
                "height": "H",
                "y": "Y"
            }'
        ) ];

        $tests[] = [ "focus", "focus=X", "X" ];
        $tests[] = [ "focus", "focus=X", [
            "x" => "X"
        ] ];
        $tests[] = [ "focus", "focus=X", json_decode(
            '{
                "x": "X"
            }'
        ) ];
        $tests[] = [ "focus", "focus=-xY", null, "Y" ];
        $tests[] = [ "focus", "focus=-xY", [
            "y" => "Y"
        ] ];
        $tests[] = [ "focus", "focus=-xY", json_decode(
            '{
                "y": "Y"
            }'
        ) ];
        $tests[] = [ "focus", "focus=XxY", "X", "Y" ];
        $tests[] = [ "focus", "focus=XxY", [
            "x" => "X",
            "y" => "Y"
        ] ];
        $tests[] = [ "focus", "focus=XxY", json_decode(
            '{
                "x": "X",
                "y": "Y"
            }'
        ) ];
        $tests[] = [ "focus", null ];
        $tests[] = [ "focus", null, [] ];
        $tests[] = [ "focus", null, json_decode('{}') ];

        $tests[] = [ "url", null ];

        return $tests;
    }
}
