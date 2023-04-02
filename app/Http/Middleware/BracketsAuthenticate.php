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
		if (!$request->hasHeader('authorization')) $this->unauthenticated();

		$header = explode(' ', $request->header('authorization'));

		if (sizeof($header) != 2) $this->unauthenticated();

		if ($header[0] != 'Bearer') $this->unauthenticated();

		if (!$this->bracketsChecker->passes($header[1])) $this->unauthenticated();

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
