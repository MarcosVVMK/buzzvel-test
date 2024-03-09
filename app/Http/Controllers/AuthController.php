<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HttpResponses;

    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Authentication"},
     *     summary="Authenticate user and generate access token",
     *     operationId="loginUser",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="email",
     *                     example="user@example.com"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     format="password",
     *                     example="secret"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Authorized",
     *         @OA\JsonContent(
     *             type="object",
     *             example={"message": "Authorized!", "token": "generated_token"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Not Authorized"
     *     )
     * )
     */
    public function login(Request $request)
    {
        if (! Auth::attempt( $request->only('email', 'password') ) ){

            return $this->response( 'Not Authorized!', 403 );

        }

        return $this->response('Authorized!', 200, [
            'token' => $request->user()->createToken('holiday-plans')->plainTextToken
        ]);
    }


    /**
     * @OA\Post(
     *     path="/logout",
     *     tags={"Authentication"},
     *     summary="Revoke user access token",
     *     operationId="logoutUser",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Token Revoked",
     *         @OA\JsonContent(
     *             type="object",
     *             example={"message": "Token Revoked"}
     *         )
     *     ),
     *     @OA\Parameter(
     *           name="Authorization",
     *           in="header",
     *           required=true,
     *           description="Bearer token",
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->response( 'Token Revoked', 200 );
    }


}
