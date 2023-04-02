<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowrtUrlRequest;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ShortUrlController extends Controller
{
	const ENDPOINT = "https://tinyurl.com/api-create.php";

	/**
	 * @throws HttpException
	 */
	public function __invoke(ShowrtUrlRequest $request): array
	{
		$response = Http::get(self::ENDPOINT."?url=".$request->get('url'));

		if ($response->status() != 200) throw new HttpException(502, 'Service unavailable');

		return ["url" => $response->body()];
	}
}
