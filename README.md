# twicpics/url

[![Packagist][packagist-image]][packagist-url]
[![License][license-image]][license-url]

[![Build Status][travis-image]][travis-url]
[![Coverage Status][coveralls-image]][coveralls-url]
[![Code Style][codestyle-image]][codestyle-url]

`twicpics/url` provides a simple yet expressive fluent API to generate [TwicPics](https://www.twicpics.com) URLs.

Here are some examples of what it can do:

```php
$builder = new TwicPics\URL();

// Create a url in one pass
$onePassUrl = $builder->cover("1:1")->resize(700)->src(SRC_URL)->url();

// Pre-crop an image then apply different transformations to it
$precrop = $builder->src(SRC_URL)->focus("25p", "71p")->crop(560, 280);
$squareUrl = $precrop->cover("1:1")->url();
$landscapeUrl = $precrop->cover("16:9")->url();

// Prepare manipulations to be applied to different sources
$square = $builder->cover("1:1")->resize(300);
$landscape = $builder->cover("1:1")->resize(300);

$squaredUrl = $square->src(SRC_URL)->url();
$squaredPrecrop = $square->src($precrop)->url();

$landscapedUrl = $landscape->src(SRC_URL)->url();
$landscapedPrecrop = $landscape->src($precrop)->url();
```

## Installation

Use composer:

```
php composer.phar require twicpics/url
```

## Usage

`twicpics/url` exports a single class (`TwicPics\URL`) that will be autoloaded. Just create an instance of this class and you're good to go:

```php
// Get the builder
$builder = new TwicPics\URL();

// Use the builder
$myFirstUrl = $builder->src(MY_IMAGE_URL)->resize( 300 )->url();
```

The builder's API is fluent and each method call returns a new immutable object. As such you can re-use an existing object and create a totally new and independent URL:

```php
$authorizedAndSquared = $builder->auth(MY_TOKEN)->cover("1:1");

$url1 = $authorizedAndSquared->src(MY_IMAGE_URL_1)->url();
$url2 = $authorizedAndSquared->src(MY_IMAGE_URL_2)->url();
```

Last, but not least, any builder object can be used as a source image by another builder object. So you can create generic manipulations to be applied on different, eventually pre-transformed, images:

```php
$square500 = $builder->cover(500, 500);

// Use authentication for an image I don't own
$external = $builder->auth(MY_TOKEN)->src(URL_TO_IMAGE_I_DONT_OWN);

// Precrop an image I own
$precrop = $builder->src(URL_TO_IMAGE_I_OWN)->crop(
    [
        "x" => 150,
        "y" => 256,
        "width" => 700,
        "height" => 889
    ]
);

// square the image I don't own
$square500->src(external)->url();

// square the image I own
$square500->src(precop)->url();
```

## API

### auth

_auth( AUTHENTICATION_TOKEN )_

Adds an authentication token.

```php
$builder->auth("aaaaaaaa-aaaa-4aaa-aaaa-aaaaaaaaaaaa");
```

### contain

_contain( &lt;expr&gt; )_

_contain( &lt;width&gt; [, &lt;height&gt; ] )_

_contain( { width, height } )_

Adds a `contain` transformation.

```php
// These four lines are strictly equivalent
$builder->contain("500x400");
$builder->contain(500, 400);
$builder->contain(
    [
        "width" => 500,
        "height" => 400
    ]
);
$builder->contain(
    json_decode(
        '{
            "width": 500,
            "height": 400
        }'
    )
);
```

### containMax

_containMax( &lt;expr&gt; )_

_containMax( &lt;width&gt; [, &lt;height&gt; ] )_

_containMax( { width, height } )_

Adds a `contain-max` transformation.

```php
// These four lines are strictly equivalent
$builder->containMax("500x400");
$builder->containMax(500, 400);
$builder->containMax(
    [
        "width" => 500,
        "height" => 400
    ]
);
$builder->containMax(
    json_decode(
        '{
            "width": 500,
            "height": 400
        }'
    )
);
```

### containMin

_containMin( &lt;expr&gt; )_

_containMin( &lt;width&gt; [, &lt;height&gt; ] )_

_containMin( { width, height } )_

Adds a `contain-min` transformation.

```php
// These four lines are strictly equivalent
$builder->containMin("500x400");
$builder->containMin(500, 400);
$builder->containMin(
    [
        "width" => 500,
        "height" => 400
    ]
);
$builder->containMin(
    json_decode(
        '{
            "width": 500,
            "height": 400
        }'
    )
);
```

### cover

_cover( &lt;expr&gt; )_

_cover( &lt;width&gt; [, &lt;height&gt; ] )_

_cover( { width, height } )_

Adds a `cover` transformation.

```php
// These four lines are strictly equivalent
$builder->cover("500x400");
$builder->cover(500, 400);
$builder->cover(
    [
        "width" => 500,
        "height" => 400
    ]
);
$builder->cover(
    json_decode(
        '{
            "width": 500,
            "height": 400
        }'
    )
);
```

### coverMax

_coverMax( &lt;expr&gt; )_

_coverMax( &lt;width&gt; [, &lt;height&gt; ] )_

_coverMax( { width, height } )_

Adds a `cover-max` transformation.

```php
// These four lines are strictly equivalent
$builder->coverMax("500x400");
$builder->coverMax(500, 400);
$builder->coverMax(
    [
        "width" => 500,
        "height" => 400
    ]
);
$builder->coverMax(
    json_decode(
        '{
            "width": 500,
            "height": 400
        }'
    )
);
```

### coverMin

_coverMin( &lt;expr&gt; )_

_coverMin( &lt;width&gt; [, &lt;height&gt; ] )_

_coverMin( { width, height } )_

Adds a `cover-min` transformation.

```php
// These four lines are strictly equivalent
$builder->coverMin("500x400");
$builder->coverMin(500, 400);
$builder->coverMin(
    [
        "width" => 500,
        "height" => 400
    ]
);
$builder->coverMin(
    json_decode(
        '{
            "width": 500,
            "height": 400
        }'
    )
);
```

### crop

_crop( &lt;expr&gt; )_

_crop( &lt;width&gt;[, &lt;height&gt; [, &lt;x&gt; [, &lt;y&gt; ] ] ] )_

_crop( { x, y, width, height } )_

Adds a crop transformation.

```php
// The following four lines create the same crop without origin
$builder->crop("500x400");
$builder->crop(500, 400);
$builder->crop(
    [
        "width" => 500,
        "height" => 400
    ]
);
$builder->crop(
    json_decode(
        '{
            "width": 500,
            "height": 400
        }'
    )
);

// The following four lines create the same crop with origin
$builder->crop("500x400@15x20");
$builder->crop(500, 400, 15, 20);
$builder->crop(
    [
        "x" => 15,
        "y" => 20,
        "width" => 500,
        "height" => 400
    ]
);
$builder->crop(
    json_decode(
        '{
            "x": 15,
            "y": 20,
            "width": 500,
            "height": 400
        }'
    )
);
```

### focus

_focus( &lt;expr&gt; )_

_focus( &lt;x&gt; [, &lt;y&gt; ] )_

_focus( { x, y } )_

Sets the focus point.

```php
// These four lines set the exact same focus point
$builder->focus("67x987");
$builder->focus(67, 987);
$builder->focus(
    [
        "x" => 67,
        "y" => 987
    ]
);
$builder->focus(
    json_decode(
        '{
            "x": 67,
            "y": 987
        }'
    )
);
```

### format

_format( &lt;type&gt; [, &lt;quality&gt; ] )_

_format( { type, quality } )_

Sets the image format.

Accepted types are `"jpeg"`, `"png"` and `"webp"`. Only `jpeg` and `webp` accept a quality value.

```php
$builder->format( "jpeg", 45 );
$builder->format(
    [
        "type" => "jpeg",
        "quality" => 62
    ]
);
$builder->format( "png" );
$builder->format(
    json_decode(
        '{
            "type": "webp",
            "quality": 80,
        }'
    )
);
```

### jpeg

_jpeg( [ &lt;quality&gt; ] )_

Shortcut for `format("jpeg", $quality)`.

### max

_max( &lt;expr&gt; )_

_max( &lt;width&gt; [, &lt;height&gt; ] )_

_max( { width, height } )_

Adds a `max` transformation.

```php
// These four lines are strictly equivalent
$builder->max("500x400");
$builder->max(500, 400);
$builder->max(
    [
        "width" => 500,
        "height" => 400
    ]
);
$builder->max(
    json_decode(
        '{
            "width": 500,
            "height": 400
        }'
    )
);
```

### min

_min( &lt;expr&gt; )_

_min( &lt;width&gt; [, &lt;height&gt; ] )_

_min( { width, height } )_

Adds a `min` transformation.

```php
// These four lines are strictly equivalent
$builder->min("500x400");
$builder->min(500, 400);
$builder->min(
    [
        "width" => 500,
        "height" => 400
    ]
);
$builder->min(
    json_decode(
        '{
            "width": 500,
            "height": 400
        }'
    )
);
```

### png

_png()_

Shortcut for `format("png")`.

### resize

_resize( &lt;expr&gt; )_

_resize( &lt;width&gt; [, &lt;height&gt; ] )_

_resize( { width, height } )_

Adds a `resize` transformation.

```php
// These four lines are strictly equivalent
$builder->resize("500x400");
$builder->resize(500, 400);
$builder->resize(
    [
        "width" => 500,
        "height" => 400
    ]
);
$builder->resize(
    json_decode(
        '{
            "width": 500,
            "height": 400
        }'
    )
);
```

### resizeMax

_resizeMax( &lt;expr&gt; )_

_resizeMax( &lt;width&gt; [, &lt;height&gt; ] )_

_resizeMax( { width, height } )_

Adds a `resize-max` transformation.

```php
// These four lines are strictly equivalent
$builder->resizeMax("500x400");
$builder->resizeMax(500, 400);
$builder->resizeMax(
    [
        "width" => 500,
        "height" => 400
    ]
);
$builder->resizeMax(
    json_decode(
        '{
            "width": 500,
            "height": 400
        }'
    )
);
```

### resizeMin

_resizeMin( &lt;expr&gt; )_

_resizeMin( &lt;width&gt; [, &lt;height&gt; ] )_

_resizeMin( { width, height } )_

Adds a `resize-min` transformation.

```php
// These four lines are strictly equivalent
$builder->resizeMin("500x400");
$builder->resizeMin(500, 400);
$builder->resizeMin(
    [
        "width" => 500,
        "height" => 400
    ]
);
$builder->resizeMin(
    json_decode(
        '{
            "width": 500,
            "height": 400
        }'
    )
);
```

### src

_src( &lt;url&gt; )_

_src( &lt;builder object&gt; )_

Sets the source image on which the current manipulation has to be performed.

If a URL is provided than it will be used as the master image to transform.

```php
$builder->resize(300)->src(MY_IMAGE); // generated a 300 pixels-wide version of MY_IMAGE
```

If a builder object is provided than its source will be used as the new manipulation's source while its transformations will be prepended to the current ones.

```php
$precrop = $builder->src(MY_IMAGE)->crop(
    [
        "x": 150,
        "y": 256,
        "width": 700,
        "height": 889
    ]
);

// This will first crop MY_IMAGE then apply a cover=500x500
$builder->cover(500, 500)->src($precop);
```

### step

_step( &lt;expr&gt; )_

_step( &lt;width&gt; [, &lt;height&gt; ] )_

_step( { width, height } )_

Adds a `step` transformation.

```php
// These four lines are strictly equivalent
$builder->step("10x10");
$builder->step(10, 10);
$builder->step(
    [
        "width" => 10,
        "height" => 10
    ]
);
$builder->step(
    json_decode(
        '{
            "width": 10,
            "height": 10
        }'
    )
);
```

### \_\_toString

_\_\_toString()_

Generates the URL as a string. Note that you must have provided an image URL using `.src()` prior to this call or an exception will be thrown.

```php
$builder->__toString(); // throws an exception
$builder->src(MY_IMAGE_URL)->__toString(); // works
```

### url

_url()_

Alias of `__toString`.

### webp

_webp( [ &lt;quality&gt; ] )_

Shortcut for `format("webp", $quality)`.

## License

Copyright (c) 2018 [TwicPics](mailto:hello@twic.pics)
Licensed under the [MIT license][license-url].



[codestyle-image]: https://img.shields.io/badge/code%20style-PHPCS-brightgreen.svg?style=flat-square
[codestyle-url]: https://github.com/squizlabs/PHP_CodeSniffer
[coveralls-image]: https://img.shields.io/coveralls/TwicPics/php_url.svg?style=flat-square
[coveralls-url]: https://coveralls.io/github/TwicPics/php_url
[license-image]: https://poser.pugx.org/twicpics/url/license?format=flat-square
[license-url]: https://raw.githubusercontent.com/TwicPics/php_url/master/LICENSE
[packagist-image]: https://poser.pugx.org/twicpics/url/version?format=flat-square
[packagist-url]: https://packagist.org/packages/twicpics/url
[travis-image]: https://img.shields.io/travis/TwicPics/php_url.svg?style=flat-square
[travis-url]: https://travis-ci.org/TwicPics/php_url
