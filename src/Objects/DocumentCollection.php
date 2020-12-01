<?php

namespace App\Objects;

use App\Service\IdfIndex;

class DocumentCollection
{
    /**
     * @var list of documents belonging to this collection
     */
    private $documents = [];

    /**
     * @var $document document object to be added to the collection
     */
    public function addDocument($document)
    {
        array_push($this->documents, $document);
    }

    /**
     * @return returns an array of  App\Objects\Document objects
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * @return returns an array of  App\Objects\Document token values
     */
    public function getDocumentData()
    {
        //return $this->documents;
        $documentData = [];
        for ($i=0; $i < count($this->documents); $i++) 
        { 
            array_push($documentData, $this->documents[$i]->getDocument());
        }

        return $documentData;
    }

    public function calculateTfIdf($idfIndex)
    {
        for ($i=0; $i < count($this->documents); $i++) 
        { 
            foreach ($this->documents[$i]->getDocument() as $key => $value) 
            {
                $this->documents[$i] = $idfIndex[$key] * $value;
            }  
        } 
        return $documentData;
    }
}