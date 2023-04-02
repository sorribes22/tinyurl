<?php

namespace App\Utils\BracketsChecker;

class StackAutomataBracketsChecker implements BracketsChecker
{
	public function passes(string $token): bool
	{
		$stack = [];

		if (strlen($token) == 0) return true;

		if (strlen($token) % 2 != 0) return false;

		foreach (str_split($token) as $char)
		{
			$size = sizeof($stack);

			if ($size == 0) $stack[] = $char;
			else if (in_array($char, ['(', '{', '['])) $stack[] = $char;
			else {
				match ($char) {
					')' => $match = $stack[$size - 1] == '(',
					'}' => $match = $stack[$size - 1] == '{',
					']' => $match = $stack[$size - 1] == '[',
				};

				if ($match) array_pop($stack);
				else $stack[] = $char;
			}
		}

		return sizeof($stack) == 0;
	}
}
