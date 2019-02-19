<?php

namespace Spatie\Regex\Test;

use Spatie\Regex\Regex;
use Spatie\Regex\RegexFailed;
use PHPUnit\Framework\TestCase;

class SplitTest extends TestCase
{
    /** @test */
    public function it_can_determine_if_a_split_was_made()
    {
        $this->assertTrue(Regex::split('/a/', 'abracadabra')->hasMatch());
        $this->assertFalse(Regex::split('/z/', 'abracadabra')->hasMatch());
    }

    /** @test */
    public function it_throws_an_exception_if_a_split_throws_an_error()
    {
        $this->expectException(RegexFailed::class);
        $this->expectExceptionMessage(
            RegexFailed::split('/abc', 'abc', 'preg_split(): No ending delimiter \'/\' found')->getMessage()
        );

        Regex::split('/abc', 'abc');
    }

    /** @test */
    public function it_throws_an_exception_if_a_split_throws_a_preg_error()
    {
        $this->expectException(RegexFailed::class);
        $this->expectExceptionMessage(
            RegexFailed::split('/(?:\D+|<\d+>)*[!?]/', 'foobar foobar foobar', 'PREG_BACKTRACK_LIMIT_ERROR')->getMessage()
        );

        Regex::split('/(?:\D+|<\d+>)*[!?]/', 'foobar foobar foobar');
    }

    /** @test */
    public function it_can_retrieve_the_split_pieces()
    {
        $this->assertEquals(['', 'br', 'c', 'd', 'br', ''], Regex::split('/a/', 'abracadabra')->pieces());
    }

    /** @test */
    public function it_returns_an_array_containing_the_original_string_if_pieces_are_queried_for_a_subject_that_didnt_match_a_pattern()
    {
        $this->assertEquals(['abracadabra'], Regex::split('/z/', 'abracadabra')->pieces());
    }

    /** @test */
    public function it_can_retrieve_a_piece_from_the_array_by_index()
    {
        $this->assertEquals('br', Regex::split('/a/', 'abracadabra')->pieces()[1]);
    }
}
