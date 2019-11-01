<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\Resource;

class GenericResponse extends Resource
{
    /**
     * @var string
     */
    protected $context;

    /**
     * @var array
     */
    protected $extras;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * GenericResponse constructor.
     *
     * @param       $context
     * @param       $resource
     * @param array $extras
     * @param int   $statusCode
     */
    public function __construct($context, $resource, array $extras = [], int $statusCode = 200)
    {
        parent::__construct($resource);

        $this->context    = $context;
        $this->extras     = $extras;
        $this->statusCode = $statusCode;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource;
    }

    /**
     * Get any additional data that should be returned with the resource array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function with($request)
    {
        return array_merge(['context' => $this->context], $this->extras);
    }

    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Http\Response $response
     *
     */
    public function withResponse($request, $response)
    {
        $response->setStatusCode($this->statusCode);
    }
}
