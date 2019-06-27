<?php

/**
 * @license Apache 2.0
 */

namespace Petstore30\controllers;

class Tesy
{
    /**
     * @OA\Post(
     *     path="/test",
     *     tags={"test"},
     *     summary="获取test",
     *     operationId="index",
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="The user name for login",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="请求成功",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                  type="string",
     *                  default="123"
     *              )
     *         ),
     *         @OA\XmlContent(ref="#/components/schemas/Test")
     *     ),
     *     security={
     *         {"petstore_auth": {"write:pets", "read:pets"}}
     *     },
     *     @OA\RequestBody(
     *         description="啊啊是",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */

}
