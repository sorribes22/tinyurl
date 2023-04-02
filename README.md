## Bind an interface to implementation
To [bind an interface](https://laravel.com/docs/10.x/container#binding-interfaces-to-implementations) to a concret
implementation I've had to register it on `register()` method from **AppServideProvider**.

Once Laravel knows how to resolve which implementation has to inject we can forget about implementations and invert
the dependency order. Now we only need to know the interface.

Class **App\Http\Middleware\BracketsAuthenticate**:
```php
public function __construct(private readonly BracketsChecker $bracketsChecker)
{
}
```
