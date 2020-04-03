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
    public function it_can_limit_the_amount_of_split_pieces()
    {
        $this->assertEquals(['', 'br', 'cadabra'], Regex::split('/a/', 'abracadabra', 3)->pieces());
    }

    /** @test */
    public function it_returns_the_whole_string_if_the_pattern_did_not_match()
    {
        $this->assertEquals(['abracadabra'], Regex::split('/z/', 'abracadabra')->pieces());
    }

    /** @test */
    public function it_can_retrieve_a_piece_from_the_array_by_index()
    {
        $this->assertEquals('br', Regex::split('/a/', 'abracadabra')->pieces()[1]);
    }
    
    /** @test */
    public function it_can_capture_delimiters()
    {
        $this->assertEquals(['a', 'br', 'a', 'c', 'a', 'd', 'a', 'br', 'a'], Regex::split('/a/', 'abracadabra', null, PREG_SPLIT_DELIM_CAPTURE)->pieces());
    }
    
    /** @test */
    public function it_can_ignore_empty_pieces()
    {
        $this->assertEquals(['br', 'c', 'd', 'br'], Regex::split('/a/', 'abracadabra', null, PREG_SPLIT_NO_EMPTY)->pieces());
    }
    
    /** @test */
    public function it_will_retrieve_the_split_pieces_only_with_offset_capture()
    {
        $this->assertEquals(['', 'br', 'c', 'd', 'br', ''], Regex::split('/a/', 'abracadabra', null, PREG_SPLIT_OFFSET_CAPTURE)->pieces());
    }
    
    /** @test */
    public function it_can_retrieve_the_offsets_with_offset_capture()
    {
        $this->assertEquals([[0, ''], [1, 'br'], [4, 'c'], [6, 'd'], [8, 'br'], [11, '']], Regex::split('/a/', 'abracadabra', null, PREG_SPLIT_OFFSET_CAPTURE)->offsets());
    }

    /** @test */
    public function it_returns_the_whole_string_if_the_pattern_did_not_match_with_offset_capture()
    {
        $this->assertEquals([[0, 'abracadabra']], Regex::split('/z/', 'abracadabra', null, PREG_SPLIT_OFFSET_CAPTURE)->offsets());
    }

    /** @test */
    public function it_returns_an_empty_array_of_offsets_without_offset_capture()
    {
        $this->assertEquals([], Regex::split('/a/', 'abracadabra')->offsets());
    }
}
