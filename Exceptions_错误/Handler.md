```php
namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
                    抱歉！你访问的页面不存在！<br><br>>>>> <a href='/'>返回首页</a>";
            exit();
        }
        
        /*
        if (!$request->ajax()) {
            // 内部错误 和 接口错误
            if (($exception instanceof \ErrorException)
                || ($exception instanceof JsonException && $exception->getCode() == 100001)) {

                return response()->view('wap.errors.500');
            }

            // 404错误 和 模板错误
            if (($exception instanceof NotFoundHttpException)) {

                $host = $request->header('host');
                $referer = $request->header('referer');

                $is_first = true;
                if ($referer && (strpos($referer, $host) !== false)) {
                    $is_first = false;
                }

                return response()->view('wap.errors.404', compact('is_first'));
            }
        }
        */
        
        //此处用于错误数据的转化
        else if ($exception instanceof JsonException) {
            $err_msg = $exception->getErrorMsg();

            $response = response()->json($err_msg);

            return $response;
        }

        if($exception instanceof ApiException){
            $err_msg = $exception->getErrorMsg();
            return response()->json($err_msg);
        }

        return parent::render($request, $exception);
    }
}

```
