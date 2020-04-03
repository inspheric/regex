<?php

namespace Spatie\Regex;

class Regex
{
    /**
     * @param string $pattern
     * @param string $subject
     *
     * @return \Spatie\Regex\MatchResult
     */
    public static function match(string $pattern, string $subject): MatchResult
    {
        return MatchResult::for($pattern, $subject);
    }

    /**
     * @param string $pattern
     * @param string $subject
     *
     * @return \Spatie\Regex\MatchAllResult
     */
    public static function matchAll(string $pattern, string $subject): MatchAllResult
    {
        return MatchAllResult::for($pattern, $subject);
    }

    /**
     * @param string|array $pattern
     * @param string|array|callable $replacement
     * @param string|array $subject
     * @param int $limit
     *
     * @return \Spatie\Regex\ReplaceResult
     */
    public static function replace($pattern, $replacement, $subject, $limit = -1): ReplaceResult
    {
        return ReplaceResult::for($pattern, $replacement, $subject, $limit);
    }

    /**
     * @param string $pattern
     * @param string $subject
     * @param int $limit
     * @param int $flags
     *
     * @return \Spatie\Regex\SplitResult
     */
    public static function split(string $pattern, string $subject, int $limit = -1, int $flags = 0): SplitResult
    {
        return SplitResult::for($pattern, $subject, $limit, $flags);
    }
}
