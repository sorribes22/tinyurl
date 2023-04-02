<?php

namespace Tests\Feature\Http\Controller;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ShortUrlControllerTest extends TestCase
{
	public function setUp(): void
	{
		parent::setUp();

		$this->withHeader('Authorization', 'Bearer {([])}');
	}

	/** @test */
	public function can_cut_an_url()
	{
		Http::fake([
			'https://tinyurl.com/*' => Http::response('https://tinyurl.com/2634hs2a')
		]);

		$body = [
			'url' => 'https://youtu.be/dQw4w9WgXcQ'
		];

		$response = $this->postJson('api/v1/short-urls', $body);

		$response->assertSuccessful();
		$response->assertJson([
			'url' => 'https://tinyurl.com/2634hs2a'
		]);
	}

	/** @test */
	public function can_handle_http_errors()
	{
		Http::fake([
			'https://tinyurl.com/*' => Http::response('error', 500)
		]);

		$body = [
			'url' => 'https://youtu.be/dQw4w9WgXcQ'
		];

		$response = $this->postJson('api/v1/short-urls', $body);

		$response->assertStatus(502);
		$response->assertJson([
			'message' => 'Service unavailable'
		]);
	}

	/** @test */
	public function validates_url()
	{
		$body = [
			'url' => 'Some random string'
		];

		$response = $this->postJson('api/v1/short-urls', $body);

		$response->assertStatus(422);
	}
}
