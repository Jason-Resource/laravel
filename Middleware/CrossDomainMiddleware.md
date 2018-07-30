```php
namespace App\Http\Middleware;

use Closure;

class CrossDomainMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        if ($response instanceof View) {
            return $response->withHeader("Access-Control-Allow-Origin",$request->header('origin'));
        } else {
            return $response->withHeaders([
                'Access-Control-Allow-Origin'       => $request->header('origin') ? : '*',
                'Access-Control-Allow-Credentials'  => 'true',
                'Access-Control-Allow-Headers'  => 'Content-Type,XFILENAME,XFILECATEGORY,XFILESIZE',
            ]);
        }
    }
}

```
