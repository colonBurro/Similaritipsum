<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class ApiController extends AbstractController
{
	/**
     * @var integer HTTP status code - 200 (OK) by default
     */
    protected $statusCode = 200;

	/**
     * @var The data to be fetched from the request
     */
    protected $data;

    /**
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param integer $statusCode the status code
     *
     * @return self
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param array $data
     * @param array $headers
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * @param string $errors
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondWithErrors($errors, $headers = [])
    {
        $data = [
            'errors' => $errors,
        ];

        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }
    /**
	 * @param string $message
	 *
	 * @return Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function respondValidationError($message = 'Validation errors')
	{
	    return $this->setStatusCode(422)->respondWithErrors($message);
	}

	/**
	 * @param string $message
	 *
	 * @return Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function respondNotFound($message = 'Not found!')
	{
	    return $this->setStatusCode(404)->respondWithErrors($message);
	}
}
