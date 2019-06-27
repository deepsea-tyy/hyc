<?php

/**
 * @license Apache 2.0
 */

namespace Petstore30;

/**
 * Class Test
 *
 * @package Petstore30
 *
 * @author  Donii Sergii <doniysa@gmail.com>
 *
 * @OA\Schema(
 *     description="Test",
 *     title="Test",
 *     @OA\Xml(
 *         name="Test"
 *     )
 * )
 */
class Test
{
    /**
     * @OA\Property(
     *     format="int64",
     *     description="ID",
     *     title="ID"
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @OA\Property(
     *     description="Data",
     *     title="Data"
     * )
     *
     * @var string
     */
    private $data;
}
