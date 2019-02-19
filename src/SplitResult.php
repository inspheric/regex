<?php

namespace Spatie\Regex;

use Exception;

class SplitResult extends RegexResult
{
    /** @var string */
    protected $pattern;

    /** @var string */
    protected $subject;

    /** @var bool */
    protected $hasMatch;

    /** @var array */
    protected $pieces;

    public function __construct(string $pattern, string $subject, bool $hasMatch, array $pieces)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->hasMatch = $hasMatch;
        $this->pieces = $pieces;
    }

    /**
     * @param string $pattern
     * @param string $subject
     *
     * @return static
     *
     * @throws \Spatie\Regex\RegexFailed
     */
    public static function for(string $pattern, string $subject)
    {
        try {
            $pieces = preg_split($pattern, $subject);
        } catch (Exception $exception) {
            throw RegexFailed::split($pattern, $subject, $exception->getMessage());
        }

        $result = preg_match($pattern, $subject);

        if ($result === false) {
            throw RegexFailed::split($pattern, $subject, static::lastPregError());
        }

        return new static($pattern, $subject, $result, $pieces);
    }

    public function hasMatch(): bool
    {
        return $this->hasMatch;
    }

    /**
     * Return an array of the pieces.
     *
     * @return array
     */
    public function pieces(): array
    {
        return $this->pieces;
    }
}
