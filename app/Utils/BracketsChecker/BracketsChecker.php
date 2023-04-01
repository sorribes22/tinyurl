<?php

namespace App\Utils\BracketsChecker;

interface BracketsChecker {
	public function passes(string $token): bool;
}
