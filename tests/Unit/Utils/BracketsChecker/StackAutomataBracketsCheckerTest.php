<?php

namespace Tests\Unit\Utils\BracketsChecker;

use App\Utils\BracketsChecker\StackAutomataBracketsChecker;
use Tests\TestCase;

class StackAutomataBracketsCheckerTest extends TestCase
{
	/** @test */
	public function can_check_if_string_pass_the_test(): void
	{
		$bracketsChecker = new StackAutomataBracketsChecker();
		$tests = [
			"{}" => true,
			"{}[]()" => true,
			"{)" => false,
			"[{]}" => false,
			"{([])}" => true,
			"(((((((()" => false,
		];

		foreach ($tests as $test => $expects) {
			$this->assertEquals($expects, $bracketsChecker->passes($test));
		}
	}
}
