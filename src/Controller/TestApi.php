<?php

namespace App\Controller;

use App\Controller\ApiController;
use App\Objects\Document;
use App\Objects\DocumentCollection;
use App\Objects\Stream;
use App\Objects\IdfIndex;

use App\Service\SimilarityChecker;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TestApi extends ApiController
{
    private $document;
    private $similarityChecker;

    public function __construct(SimilarityChecker $similarityChecker)
    {
        $this->similarityChecker = $similarityChecker;
    }

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

    /**
     * @Route("/api/idfIndex", name="calculate_idf_index", methods={"GET"})
     */
    public function idfIndex(Request $request, IdfIndex $idfIndex, Stream $stream) : JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data["streams"]) || count($data["streams"]) < 2) 
        {
            return $this->respondValidationError('You must provide at least one text file location.');
        }

        $documentCollection = $stream -> readMultipleDocuments($data["streams"]);
        $idfIndex -> calculateIdfIndex($documentCollection->getDocuments());

        return $this -> respond($idfIndex -> getIdfIndex());
    }

    /**
     * @Route("/api/search", name="search_documents", methods={"GET"})
     */
    public function search(Request $request) : JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data["streams"]) || count($data["streams"]) < 2) 
        {
            return $this->respondValidationError('You must provide at least one text file location.');
        }
        
        if (!isset($data["searchTerm"])) 
        { 
             return $this->respondValidationError('You must provide a search term.');
        }

        $result = $this->similarityChecker->searchDocuments($data["streams"], $data["searchTerm"]);

        return $this -> respond($result);
    }

    /**
     * @Route("/api/compare", name="compare_documents", methods={"GET"})
     */
    public function compare(Request $request, Stream $stream) : JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data["streams"]) || count($data["streams"]) < 2) 
        {
            return $this->respondValidationError('You must provide at least one text file location.');
        }
        
        $documentCollection = $stream -> readMultipleDocuments($data["streams"]);
        $result = [];

        for ($i=0; $i < count($documentCollection->getDocuments()); $i++) 
        { 
            $firstVector = $documentCollection ->getDocuments()[$i];
            $row = [];

            $result = $this->similarityChecker->cosineSimilarity($documentCollection ->getDocuments()[0]->getDocument(), $documentCollection ->getDocuments()[1]->getDocument());
        }
        
        //$result = $this->similarityChecker->cosineSimilarity($documentCollection ->getDocuments()[0]->getDocument(), $documentCollection ->getDocuments()[1]->getDocument());

        return $this -> respond($result);
    }
}
