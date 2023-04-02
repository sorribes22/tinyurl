<?php

namespace App\Http\Middleware;

use App\Utils\BracketsChecker\BracketsChecker;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BracketsAuthenticate
{
	public function __construct(private readonly BracketsChecker $bracketsChecker)
	{
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws \Illuminate\Auth\AuthenticationException
	 */
    public function handle(Request $request, Closure $next): Response
    {
		$bearerToken = $request->bearerToken();

		if ($bearerToken === null) $this->unauthenticated();

		if (!$this->bracketsChecker->passes($bearerToken)) $this->unauthenticated();

        return $next($request);
    }

	/**
	 * @throws \Illuminate\Auth\AuthenticationException
	 */
	protected function unauthenticated()
	{
		throw new AuthenticationException(
			'Unauthenticated.', [], '/'
		);
	}
}
