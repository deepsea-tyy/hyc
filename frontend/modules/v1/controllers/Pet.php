<?php

/**
 * @license Apache 2.0
 */

namespace Petstore30\controllers;

/**
 * Class Pet
 *
 * @package Petstore30\controllers
 *
 * @author  Donii Sergii <doniysa@gmail.com>
 */
class Pet
{
    /**
     * @OA\Post(
     *     path="/pet",
     *     tags={"pet"},
     *     summary="Add a new pet to the store",
     *     operationId="addPet",
     *     @OA\Response(
     *         response=405,
     *         description="Invalid input"
     *     ),
     *     security={
     *         {"petstore_auth": {"write:pets", "read:pets"}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/Pet"}
     * )
     */
    public function addPet()
    {
    }


    /**
     * @OA\Get(
     *     path="/pet/findByStatus",
     *     tags={"pet"},
     *     summary="Finds Pets by status",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="findPetsByStatus",
     *     deprecated=true,
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Status values that needed to be considered for filter",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *             type="array",
     *             default="available",
     *             @OA\Items(
     *                 type="string",
     *                 enum = {"available", "pending", "sold"},
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Pet")
     *         ),
     *         @OA\XmlContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Pet")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     ),
     *     security={
     *         {"petstore_auth": {"write:pets", "read:pets"}}
     *     }
     * )
     */
    public function findPetsByStatus()
    {
    }

}
