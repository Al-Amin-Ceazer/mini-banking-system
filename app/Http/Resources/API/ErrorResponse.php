<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\Resource;
use Symfony\Component\HttpFoundation\Response;

class ErrorResponse extends Resource
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

    public function __construct($context, array $extras = [], int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        $stub = collect();
        parent::__construct($stub);

        $this->context    = $context;
        $this->extras     = $extras;
        $this->statusCode = $statusCode;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
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
     * @param \Illuminate\Http\Request  $request
     * @param \Illuminate\Http\Response $response
     */
    public function withResponse($request, $response)
    {
        $response->setStatusCode($this->statusCode);
    }
}
