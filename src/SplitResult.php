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

    /** @var array */
    protected $offsets;

    public function __construct(string $pattern, string $subject, bool $hasMatch, array $pieces, array $offsets = [])
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->hasMatch = $hasMatch;
        $this->pieces = $pieces;
        $this->offsets = $offsets;
    }

    /**
     * @param string $pattern
     * @param string $subject
     * @param int $limit
     * @param int $flags
     *
     * @return static
     *
     * @throws \Spatie\Regex\RegexFailed
     */
    public static function for(string $pattern, string $subject, $limit = -1, $flags = 0)
    {
        try {
            $pieces = preg_split($pattern, $subject, $limit, $flags);
        } catch (Exception $exception) {
            throw RegexFailed::split($pattern, $subject, $exception->getMessage());
        }

        $result = preg_match($pattern, $subject);

        if ($result === false) {
            throw RegexFailed::split($pattern, $subject, static::lastPregError());
        }

        $offsets = [];

        if ($flags & PREG_SPLIT_OFFSET_CAPTURE) {
            $offsets = $pieces;
            $pieces = [];
            
            foreach ($offsets as $piece) {
                $pieces[] = $piece[1];
            }
        }

        return new static($pattern, $subject, $result, $pieces, $offsets);
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

    /**
     * Return an array of the offsets.
     *
     * @return array
     */
    public function offsets(): array
    {
        return $this->offsets;
    }
}
