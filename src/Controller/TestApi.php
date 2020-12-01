<?php

namespace App\Controller;

use App\Controller\ApiController;
use App\Objects\Document;
use App\Objects\DocumentCollection;
use App\Objects\Stream;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TestApi extends ApiController
{
    private $document;
    private $similarityChecker;

    /**
     * @Route("/api/termFrequencies", name="calculate_term_frequencies", methods={"GET"})
     */
    public function calculateTermFrequencies(Request $request, Stream $stream) : JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data["streams"]) || count($data["streams"]) < 1) 
        {
            return $this->respondValidationError('You must provide at least one text file location.');
        }

        $documentCollection = $stream -> readMultipleDocuments($data["streams"]);

        return $this -> respond($documentCollection->getDocumentData());
    }
}
