<?php
 
namespace App\Service;

use App\Objects\DocumentCollection;
use App\Objects\IdfIndex;
use App\Objects\Stream;

class SimilarityChecker
{
    private $documentCollection;
    private $idfIndex;
    private $stream;

    public function __construct(DocumentCollection $documentCollection, IdfIndex $idfIndex, Stream $stream)
    {
        $this->documentCollection = $documentCollection;
        $this->idfIndex = $idfIndex;
        $this->stream = $stream;
    }
    
    public function compareDocumentSimilarity($streamList)
    {
        for ($i=0; $i < count($streamList); $i++) 
        {
            $this->stream -> readDocument($streamList[$i]);
            $this->documentCollection -> addDocument($this->stream -> getDocument());
        }

        $this->idfIndex -> calculateIdfIndex($this->documentCollection->getDocuments());
        $this->documentCollection -> calculateTfIdf($this->idfIndex->getIdfIndex);

        return $this->documentCollection;
    }

    public function searchDocuments($streamList, $searchTerm)
    {

        $this->documentCollection = $this->stream -> readMultipleDocuments($streamList);
        $this->idfIndex -> calculateIdfIndex($this->documentCollection->getDocuments());

        if (!$this->idfIndex->lookupTerm($this->documentCollection->getDocuments(), $searchTerm)) 
        {
            return false;
        }

        return $this->idfIndex->getLookupMatches();
    }

    public function calculateTfIdf($idfIndex)
    {
        for ($i=0; $i < count($this->documents); $i++) 
        { 
            foreach ($this->documents[$i]->getDocument() as $key => $value) 
            {
                $idfIndex[$key] * $value;
            }  
        } 
        return $documentData;
    }

    public function l2Norm($vector)
    {
        $result = 0;
        foreach ($vector as $key => $value) 
        {
            $result += $vector[$key]**2;
        }

        return sqrt($result);
    }

    public function cosineSimilarity($vector1, $vector2)
    {
        $result=0;
        foreach ($vector1 as $key => $value) 
        {
            if (array_key_exists($key, $vector2))
            {
                $result += ($value * $vector2[$key]);
            }
        }  
        if ($this->l2Norm($vector1) * $this->l2Norm($vector2)==0) {
            return 0;
        }
        return $result/($this->l2Norm($vector1) * $this->l2Norm($vector2));
    }
}

