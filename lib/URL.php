<?php
/**
 * A library to build TwicPics URLs
 * 
 * PHP Version 5.3
 * 
 * @category Main
 * @package  TwicPics/URL
 * @author   TwicPics <hello@twic.pics>
 * @license  https://raw.githubusercontent.com/TwicPics/php_url/master/LICENSE MIT
 * @link     https://www.github.com/TwicPics/php_url
 */

namespace TwicPics;

const MAIN_URL = "https://i.twic.pics/v1/";
const R_AUTHENT
    = "/^[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89aAbB][a-f0-9]{3}-[a-f0-9]{12}$/";

/**
 * URL class
 * 
 * @category Main
 * @package  TwicPics/URL
 * @author   TwicPics <hello@twic.pics>
 * @license  https://raw.githubusercontent.com/TwicPics/php_url/master/LICENSE MIT
 * @link     https://www.github.com/TwicPics/php_url
 */
class URL
{
    /**
     * Tests if a value is "undefined" (false or null)
     * 
     * @param mixed $value the value to test
     * 
     * @return boolean
     */
    private static function _undefined( $value )
    {
        return $value === false || $value === null;
    }

    /**
     * Creates a couple string (VxV) given two values
     * 
     * @param mixed $v1 first value
     * @param mixed $v2 second value
     * 
     * @return string
     */
    private static function _couple( $v1, $v2 = null )
    {
        $noV1 = URL::_undefined($v1);
        $noV2 = URL::_undefined($v2);
        if ($noV1 && $noV2) {
            return null;
        }
        return $noV1 ?
            "-x" . ( ( string ) $v2 ) :
            (
                $noV2 ?
                    ( ( string ) $v1 ) :
                    ( ( string ) $v1 ) . "x" . ( ( string ) $v2 )
            );
    }

    /**
     * Handles varargs generically
     * 
     * @param string   $name  name of the method (for error messages)
     * @param mixed[]  $args  array of arguments to handle
     * @param string[] $props name of properties for map argument
     * 
     * @return mixed[] the normalized arguments
     */
    private static function _getArgs( $name, $args, $props )
    {
        $argsLength = count($args);
        $propsLength = count($props);
        if ($argsLength < 1 || $argsLength > $propsLength) {
            throw new \DomainException(
                "method " . $name . " requires 1 to " .
                ( ( string ) $propsLength ). " arguments"
            );
        }
        if ($argsLength === 1) {
            $first = $args[ 0 ];
            if (is_array($first)) {
                return array_map(
                    function ( $prop ) use ( $first ) {
                        return array_key_exists($prop, $first) ?
                            $first[ $prop ] :
                            null;
                    },
                    $props
                );
            }
            if (is_object($first)) {
                return array_map(
                    function ( $prop ) use ( $first ) {
                        return isset($first->$prop) ?
                            $first->$prop :
                            null;
                    },
                    $props
                );
            }
        }
        while ( count($args) < $propsLength ) {
            $args[] = null;
        }
        return $args;
    }

    private $_auth = null;
    private $_format = null;
    private $_manip = [];
    private $_src = null;

    /**
     * Converts to string URL
     * 
     * @return string
     */
    public function __toString()
    {
        if (URL::_undefined($this->_src)) {
            throw new \DomainException("cannot create url without a source");
        }
        $auth = $this->_auth;
        $format = $this->_format;
        $manip = $this->_manip;
        return MAIN_URL .
            ( count($manip) ? join("/", $manip) . "/" : "") .
            ( $format !== null ? "format=" . $format . "/" : "" ) .
            ( $auth !== null ? "auth:" . $auth . "/" : "" ) .
            $this->_src;
    }

    /**
     * Creates a duplicate of the object
     * 
     * @return URL
     */
    private function _clone()
    {
        $clone = new URL();
        $clone->_auth = $this->_auth;
        $clone->_format = $this->_format;
        $clone->_manip = $this->_manip;
        $clone->_src = $this->_src;
        return $clone;
    }

    /**
     * Creates a new URL object with an additional transformation
     * 
     * @param mixed $name  the name of the transformation
     * @param mixed $value the value of the transformation
     * 
     * @return URL
     */
    private function _transformation( $name, $value )
    {
        $clone = $this->_clone();
        $clone->_manip = array_merge(
            $clone->_manip,
            [ ( (string) $name ) . "=" . ( ( string ) $value ) ]
        );
        return $clone;
    }

    /**
     * Creates a new URL object with an additional resize-like transformation
     * 
     * @param string  $methodName the name of the method (for error messages)
     * @param mixed   $name       the name of the transformation
     * @param mixed[] $args       the args of the method
     * 
     * @return URL
     */
    private function _resizeTransformation( $methodName, $name, $args )
    {
        $sizeString = URL::_couple(
            ...URL::_getArgs(
                $methodName,
                $args,
                [ "width", "height" ]
            )
        );
        if ($sizeString === null) {
            throw new \DomainException(
                $methodName . ": at least a width or a height is needed"
            );
        }
        return $this->_transformation($name, $sizeString);
    }

    /**
     * Creates a new URL object with an authentication token
     * 
     * @param string $token the authentication token
     * 
     * @return URL
     */
    public function auth( $token = null )
    {
        if (!preg_match(R_AUTHENT, $token)) {
            throw new \DomainException("token " . $token . " is illformed");
        }
        $clone = $this->_clone();
        $clone->_auth = $token;
        return $clone;
    }

    /**
     * Creates a new URL object with an additional contain transformation
     * 
     * @param mixed[] ...$args the arguments
     * 
     * @return URL
     */
    public function contain( ...$args )
    {
        return $this->_resizeTransformation("contain", "contain", $args);
    }

    /**
     * Creates a new URL object with an additional contain-max transformation
     * 
     * @param mixed[] ...$args the arguments
     * 
     * @return URL
     */
    public function containMax( ...$args )
    {
        return $this->_resizeTransformation("containMax", "contain-max", $args);
    }

    /**
     * Creates a new URL object with an additional contain-min transformation
     * 
     * @param mixed[] ...$args the arguments
     * 
     * @return URL
     */
    public function containMin( ...$args )
    {
        return $this->_resizeTransformation("containMin", "contain-min", $args);
    }

    /**
     * Creates a new URL object with an additional cover transformation
     * 
     * @param mixed[] ...$args the arguments
     * 
     * @return URL
     */
    public function cover( ...$args )
    {
        return $this->_resizeTransformation("cover", "cover", $args);
    }

    /**
     * Creates a new URL object with an additional cover-max transformation
     * 
     * @param mixed[] ...$args the arguments
     * 
     * @return URL
     */
    public function coverMax( ...$args )
    {
        return $this->_resizeTransformation("coverMax", "cover-max", $args);
    }

    /**
     * Creates a new URL object with an additional cover-min transformation
     * 
     * @param mixed[] ...$args the arguments
     * 
     * @return URL
     */
    public function coverMin( ...$args )
    {
        return $this->_resizeTransformation("coverMin", "cover-min", $args);
    }

    /**
     * Creates a new URL object with an additional crop transformation
     * 
     * @param mixed[] ...$args the arguments
     * 
     * @return URL
     */
    public function crop( ...$args )
    {
        $args = URL::_getArgs(
            "crop",
            $args,
            [ "width", "height", "x", "y" ]
        );
        $sizeString = URL::_couple($args[ 0 ], $args[ 1 ]);
        if ($sizeString === null) {
            throw new \DomainException(
                "crop: at least a width or a height is needed"
            );
        }
        $coordString = URL::_couple($args[ 2 ], $args[ 3 ]);
        return $this->_transformation(
            "crop",
            $coordString === null ?
                $sizeString :
                $sizeString . "@" . $coordString
        );
    }

    /**
     * Creates a new URL object with the given focus point
     * 
     * @param mixed[] ...$args the arguments
     * 
     * @return URL
     */
    public function focus( ...$args )
    {
        $coordString = URL::_couple(
            ...URL::_getArgs(
                "focus",
                $args,
                [ "x", "y" ]
            )
        );
        if ($coordString === null) {
            throw new \DomainException(
                "focus: at least a x-coord or a y-coord is needed"
            );
        }
        return $this->_transformation("focus", $coordString);
    }

    private static $_formatQuality = [
        "jpeg" => true,
        "png" => false,
        "webp" => true
    ];

    /**
     * Creates a new URL object with a given format
     * 
     * @param mixed[] ...$args the arguments
     * 
     * @return URL
     */
    public function format( ...$args )
    {
        $args = URL::_getArgs(
            "format",
            $args,
            [ "type", "quality" ]
        );
        $format = ( ( string ) $args[ 0 ] );
        $quality = $args[ 1 ];
        if (URL::_undefined($format)) {
            throw new \DomainException("format expected");
        }
        if (!array_key_exists($format, URL::$_formatQuality)) {
            throw new \DomainException("unknow format '" . $format . "'");
        }
        $noQuality = URL::_undefined($quality);
        if (!$noQuality && !URL::$_formatQuality[ $format ]) {
            throw new \DomainException(
                "format '". $format .
                "' does not support quality specifier"
            );
        }
        $clone = $this->_clone();
        $clone->_format
            = $noQuality ?
                $format :
                $format . "-" . ( ( string ) $quality );
        return $clone;
    }

    /**
     * Creates a new URL object with the jpeg format
     * 
     * @param mixed? $quality the quality
     * 
     * @return URL
     */
    public function jpeg( $quality = null )
    {
        return $this->format("jpeg", $quality);
    }

    /**
     * Creates a new URL object with an additional max transformation
     * 
     * @param mixed[] ...$args the arguments
     * 
     * @return URL
     */
    public function max( ...$args )
    {
        return $this->_resizeTransformation("max", "max", $args);
    }

    /**
     * Creates a new URL object with an additional min transformation
     * 
     * @param mixed[] ...$args the arguments
     * 
     * @return URL
     */
    public function min( ...$args )
    {
        return $this->_resizeTransformation("min", "min", $args);
    }

    /**
     * Creates a new URL object with the png format
     * 
     * @return URL
     */
    public function png()
    {
        return $this->format("png");
    }

    /**
     * Creates a new URL object with an additional resize transformation
     * 
     * @param mixed[] ...$args the arguments
     * 
     * @return URL
     */
    public function resize( ...$args )
    {
        return $this->_resizeTransformation("resize", "resize", $args);
    }

    /**
     * Creates a new URL object with an additional resize-max transformation
     * 
     * @param mixed[] ...$args the arguments
     * 
     * @return URL
     */
    public function resizeMax( ...$args )
    {
        return $this->_resizeTransformation("resizeMax", "resize-max", $args);
    }

    /**
     * Creates a new URL object with an additional resize-min transformation
     * 
     * @param mixed[] ...$args the arguments
     * 
     * @return URL
     */
    public function resizeMin( ...$args )
    {
        return $this->_resizeTransformation("resizeMin", "resize-min", $args);
    }

    /**
     * Creates a new URL with a new source
     * 
     * @param mixed $src the source
     * 
     * @return URL
     */
    public function src( $src )
    {
        $clone = $this->_clone();
        if ($src instanceof URL) {
            $clone->_auth = $src->_auth !== null ? $src->_auth : $clone->_auth;
            if ($clone->_format === null) {
                $clone->_format = $src->_format;
            }
            $clone->_manip = array_merge($src->_manip, $clone->_manip);
            $clone->_src = $src->_src !== null ? $src->_src : $clone->_src;
        } else {
            $clone->_src = $src;
        }
        return $clone;
    }

    /**
     * Creates a new URL object with an additional step transformation
     * 
     * @param mixed[] ...$args the arguments
     * 
     * @return URL
     */
    public function step( ...$args )
    {
        return $this->_resizeTransformation("step", "step", $args);
    }

    /**
     * Converts to string URL(alias of __toString)
     * 
     * @return string
     */
    public function url()
    {
        return $this->__toString();
    }

    /**
     * Creates a new URL object with the webp format
     * 
     * @param mixed? $quality the quality
     * 
     * @return URL
     */
    public function webp( $quality = null )
    {
        return $this->format("webp", $quality);
    }
}
