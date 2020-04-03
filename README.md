# Making regex great again

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/regex.svg?style=flat-square)](https://packagist.org/packages/spatie/regex)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/regex/master.svg?style=flat-square)](https://travis-ci.org/spatie/regex)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/regex.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/regex)
[![StyleCI](https://styleci.io/repos/65915598/shield)](https://styleci.io/repos/65915598)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/regex.svg?style=flat-square)](https://packagist.org/packages/spatie/regex)

Php's built in `preg_*` functions require some odd patterns like passing variables by reference and treating `false` or `null` values as errors. `spatie/regex` provides a cleaner interface for `preg_match`, `preg_match_all`, `preg_replace`, `preg_replace_callback` and `preg_split`.

```php
use Spatie\Regex\Regex;

// Using `match`
Regex::match('/a/', 'abc'); // `MatchResult` object
Regex::match('/a/', 'abc')->hasMatch(); // true
Regex::match('/a/', 'abc')->result(); // 'a'

// Capturing groups with `match`
Regex::match('/a(b)/', 'abc')->result(); // 'ab'
Regex::match('/a(b)/', 'abc')->group(1); // 'b'

// Setting defaults
Regex::match('/a(b)/', 'xyz')->resultOr('default'); // 'default'
Regex::match('/a(b)/', 'xyz')->groupOr(1, 'default'); // 'default'

// Using `matchAll`
Regex::matchAll('/a/', 'abcabc')->hasMatch(); // true
Regex::matchAll('/a/', 'abcabc')->results(); // Array of `MatchResult` objects

// Using replace
Regex::replace('/a/', 'b', 'abc')->result(); // 'bbc';
Regex::replace('/a/', function (MatchResult $result) {
    return $result->result() . 'Hello!';
}, 'abc')->result(); // 'aHello!bc';

// Using `split`
Regex::split('/a/', 'abracadabra')->hasMatch(); // true
Regex::split('/a/', 'abracadabra')->pieces(); // Array of pieces ['', 'br', 'c', 'd', 'br', '']
```

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## Installation

You can install the package via composer:

``` bash
composer require spatie/regex
```

## Usage

### Matching a pattern once

Matches a pattern on a subject. Returns a `MatchResult` object for the first match.

```php
/**
 * @param string $pattern
 * @param string $subject
 *
 * @return \Spatie\Regex\MatchResult
 */
Regex::match(string $pattern, string $subject): MatchResult
```

#### `MatchResult::hasMatch(): bool`

Checks if the pattern matches the subject.

```php
Regex::match('/abc/', 'abc')->hasMatch(); // true
Regex::match('/def/', 'abc')->hasMatch(); // false
```

#### `MatchResult::result(): string`

Return the full match that was made. Returns `null` if no match was made.

```php
Regex::match('/abc/', 'abc')->result(); // 'abc'
Regex::match('/def/', 'abc')->result(); // null
```

#### `MatchResult::group(int $id): string`

Return the contents of a captured group (with a 1-based index). Throws a `RegexFailed` exception if the group doesn't exist.

```php
Regex::match('/a(b)c/', 'abc')->group(1); // 'b'
Regex::match('/a(b)c/', 'abc')->group(2); // `RegexFailed` exception
```

### Matching all occurences of a pattern

Matches a pattern on a subject. Returns a `MatchAllResult` object containing all matches.

```php
/**
 * @param string $pattern
 * @param string $subject
 *
 * @return \Spatie\Regex\MatchAllResult
 */
public static function matchAll(string $pattern, string $subject): MatchAllResult
```

#### `MatchAllResult::hasMatch(): bool`

Checks if the pattern matches the subject.

```php
Regex::matchAll('/abc/', 'abc')->hasMatch(); // true
Regex::matchAll('/abc/', 'abcabc')->hasMatch(); // true
Regex::matchAll('/def/', 'abc')->hasMatch(); // false
```

#### `MatchAllResult::results(): array`

Returns an array of `MatchResult` objects.

```php
$results = Regex::matchAll('/ab([a-z])/', 'abcabd')->results();

$results[0]->result(); // 'abc'
$results[0]->group(1); // 'c'
$results[1]->result(); // 'abd'
$results[1]->group(1); // 'd'
```

### Replacing a pattern in a subject

Replaces a pattern in a subject. Returns a `ReplaceResult` object.

```php
/**
 * @param string|array $pattern
 * @param string|array|callable $replacement
 * @param string|array $subject
 * @param int $limit
 *
 * @return \Spatie\Regex\ReplaceResult
 */
public static function replace($pattern, $replacement, $subject, $limit = -1): ReplaceResult
```

#### `ReplaceResult::result(): mixed`

```php
Regex::replace('/a/', 'b', 'abc')->result(); // 'bbc'
```

`Regex::replace` also works with callables. The callable will receive a `MatchResult` instance as it's argument.

```php
Regex::replace('/a/', function (MatchResult $matchResult) {
    return str_repeat($matchResult->result(), 2);
}, 'abc')->result(); // 'aabc'
```

Patterns, replacements and subjects can also be arrays. `Regex::replace` behaves exactly like [`preg_replace`](http://php.net/manual/en/function.preg-replace.php) in those instances.

### Splitting using a pattern

Splits a subject using a pattern. Returns a `SplitResult` object.

```php
/**
 * @param string $pattern
 * @param string $subject
 * @param int $limit
 * @param int $flags
 *
 * @return \Spatie\Regex\SplitResult
 */
Regex::split(string $pattern, string $subject, $limit = -1, $flags = 0): SplitResult
```

The valid flags are `PREG_SPLIT_NO_EMPTY`, `PREG_SPLIT_DELIM_CAPTURE` and `PREG_SPLIT_OFFSET_CAPTURE`, exactly like [`preg_split`](http://php.net/manual/en/function.preg-split.php).

For the special case of `PREG_SPLIT_OFFSET_CAPTURE`, see `offsets()` below.

#### `SplitResult::hasMatch(): bool`

Checks if the pattern matches the subject.

```php
Regex::split('/a/', 'abracadabra')->hasMatch(); // true
Regex::split('/z/', 'abracadabra')->hasMatch(); // false
```

#### `SplitResult::pieces(): array`

Return an array of the pieces that the string was split into. Returns an array containing only the original string if no match was made. 

Note that even if the `PREG_SPLIT_OFFSET_CAPTURE` flag was used, only the actual string pieces will be returned by `pieces()`. To return the strings along with their offsets (i.e. the default behaviour of `preg_split` with `PREG_SPLIT_OFFSET_CAPTURE`), use `offsets()` below.

```php
Regex::split('/a/', 'abracadabra')->pieces(); // ['', 'br', 'c', 'd', 'br', '']
Regex::split('/z/', 'abracadabra')->pieces(); // ['abracadabra']
```

#### `SplitResult::offsets(): array`

Return an array of the pieces that the string was split into and the string offsets, if the `PREG_SPLIT_OFFSET_CAPTURE` flag was used.

Returns an empty array if the flag was not used.

```php
Regex::split('/a/', 'abracadabra', null, PREG_SPLIT_OFFSET_CAPTURE)->offsets(); // [[0, ''], [1, 'br'], [4, 'c'], [6, 'd'], [8, 'br'], [11, '']]
Regex::split('/z/', 'abracadabra', null, PREG_SPLIT_OFFSET_CAPTURE)->offsets(); // [[0, 'abracadabra']]
Regex::split('/z/', 'abracadabra')->offsets(); // []
```

### Error handling

If anything goes wrong in a `Regex` method, a `RegexFailed` exception gets thrown. No need for checking `preg_last_error()`.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Sebastian De Deyne](https://github.com/sebastiandedeyne)
- [All Contributors](../../contributors)

## Support us

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie).
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
