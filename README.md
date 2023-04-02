# Technical test
## Start server
If you have **docker** and **docker-compose** packages installed, you will only need to run this on the project root
to start the server:
```bash
docker compose up -d
```
It will start a docker stack ready to serve requests.

If PHPStorm is configured to use these containers as interpreters, develop and debug tasks will be easier.

## Brackets authentication
In order to implement custom authentication method without override the previous ones I've created a new middleware.
It has been registered in the App\Http\Kernel $middlewareAliases property:

```php
protected $middlewareAliases = [
    ...,
    'auth.brackets' => \App\Http\Middleware\BracketsAuthenticate::class,
]
```

## Stack Automata
The best solution for the problem I thought is to create a stack automata.

It consists in to add the open brackets on a stack and to pop it if they match the closing bracket.
If at the end of all iterations the stack is empty, the string will pass the test.

```php
public function passes(string $token): bool
{
    $stack = [];

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
```

## Binding an interface to implementation
To [bind an interface](https://laravel.com/docs/10.x/container#binding-interfaces-to-implementations) to a concrete
implementation I've had to register it on `register()` method from **AppServideProvider**.

Once Laravel knows how to resolve which implementation has to inject we can forget about implementations and invert
the dependency order. Now we only need to know the interface.

Class **App\Http\Middleware\BracketsAuthenticate**:
```php
public function __construct(private readonly BracketsChecker $bracketsChecker)
{
}
```

## Testing
Test Driven Development has been used in this project. You can find 4 simple tests in `tests/` folder that checks
that StackAutomataBracketsChecker and the API endpoint works as expected.

The HTTP requests to suggested endpoint are faked using the Http Laravel facade.

To run these tests you can use PHPStorm or exec this command:
```bash
docker exec -it tinyurl_api php artisan test
```

Once all tests passes, I've tested it though insomnia requests. The request export to curl is:
```bash
curl --request POST \
  --url http://localhost/api/v1/short-urls \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer {([])}' \
  --header 'Content-Type: application/json' \
  --data '{ "url": "https://youtu.be/dQw4w9WgXcQ" }'
```


