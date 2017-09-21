http://stackoverflow.com/questions/29163564/pass-a-custom-message-or-any-other-data-to-laravel-404-blade-php
https://scotch.io/tutorials/creating-a-laravel-404-page-using-custom-exception-handlers
https://mattstauffer.co/blog/laravel-5.0-custom-error-pages

在 app/Exceptions/Handler.php 中
<?php

//abort(404, 'not found');

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

public function render($request, Exception $e)
{
    if ($e instanceof NotFoundHttpException )  {
    	//除了后台外
        if (!$request->is('admin/*')) {
            return response()->view('client.errors.404', [], 404);
        }
    }



    //check if exception is an instance of ModelNotFoundException.
    //if ($e instanceof ModelNotFoundException or $e instanceof NotFoundHttpException) {
    if ($e instanceof ModelNotFoundException) {
        // ajax 404 json feedback
        if ($request->ajax()) {
            return response()->json(['error' => 'Not Found'], 404);
        }

        // normal 404 view page feedback
        return response()->view('errors.missing', [], 404);
    }


    /*

    if ($e instanceof CustomException) {
        return response()->view('errors.custom', [], 500);
    }

	if ($e instanceof HttpResponseException) {
        return $e->getResponse();
    } elseif ($e instanceof ModelNotFoundException) {
        $e = new NotFoundHttpException($e->getMessage(), $e);
    } elseif ($e instanceof AuthenticationException) {
        return $this->unauthenticated($request, $e);
    } elseif ($e instanceof AuthorizationException) {
        $e = new HttpException(403, $e->getMessage());
    } elseif ($e instanceof ValidationException && $e->getResponse()) {
        return $e->getResponse();
    }

    if ($this->isHttpException($e)) {
        return $this->toIlluminateResponse($this->renderHttpException($e), $e);
    } else {
        return $this->toIlluminateResponse($this->convertExceptionToResponse($e), $e);
    }
    */

    return parent::render($request, $e);
}

?>