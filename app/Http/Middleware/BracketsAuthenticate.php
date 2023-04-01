<?php

namespace App\Http\Middleware;

use App\Utils\BracketsChecker\StackAutomataBracketsChecker;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BracketsAuthenticate
{
	public function __construct(private StackAutomataBracketsChecker $bracketsChecker)
	{
	}

	/**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
		if (!$request->hasHeader('authorization')) $this->unauthenticated();

		$header = explode(' ', $request->header('authorization'));

		if (sizeof($header) != 2) $this->unauthenticated();

		if ($header[0] != 'Bearer') $this->unauthenticated();

		if (!$this->bracketsChecker->passes($header[1])) $this->unauthenticated();

        return $next($request);
    }

	protected function unauthenticated()
	{
		throw new AuthenticationException(
			'Unauthenticated.', [], '/'
		);
	}
}
