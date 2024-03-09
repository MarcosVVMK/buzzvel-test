<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\OpenApi (
 *      @OA\Info(
 *          version="1.0.0",
 *          title="Swagger Buzzvel test",
 *          description="API for Buzzvel",
 *          termsOfService="http://swagger.io/terms/",
 *          @OA\Contact(
 *             email="marcosvm000@gmail.com"
 *          )
 *      ),
 *  )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
