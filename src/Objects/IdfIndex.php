<?php
 
namespace App\Objects;

class IdfIndex
{
    /**
     * @var array containing the IDF of all tokens inside a collection
     */
    private $idfIndex = [];
    
    private $lookupMatches = [];

    public function __construct()
    {
        $this->lookupMatches = ["searchTerm" => "", "matches" => []];
    }

    /**
     * @return returns the IDF array of a collection's tokens
     */
    public function getIdfIndex()
    {
        return $this->idfIndex;
    }
    
    public function getLookupMatches()
    {
        return $this->lookupMatches;
    }
    
    /**
     * @var $documents App\Objects\DocumentCollection object
     *
     * @return returns the sorted IDF array of a collection's tokens
     */
    public function calculateIdfIndex($documents)
    {
        $documentsCount = count($documents)-1;

        for ($i=0; $i < count($documents); $i++) 
        {
            foreach ($documents[$i]->getDocument() as $key => $value) 
            {
                if (!array_key_exists($key, $this->idfIndex))
                {
                    $this->idfIndex += [$key => []];
                }
                array_push($this->idfIndex[$key], $i);
            }
        }
        
        foreach ($this->idfIndex as $key => $value)
        {
            $this->idfIndex[$key] = log(count($documents) / count($this->idfIndex[$key]));
        }
        
        arsort($this->idfIndex);
        return $this->idfIndex;
    }

    public function lookupTerm($documents, $searchTerm)
    {
        $this->lookupMatches["searchTerm"] = $searchTerm;

        // $searchTerm=preg_split('/\W/', strtolower($searchTerm), 0, PREG_SPLIT_NO_EMPTY);

        // for ($i=0; $i < count($searchTerm); $i++) { 
            
        // }

        if (!array_key_exists($searchTerm, $this->idfIndex)) 
        {
            return false;
        }
        $matchValue=0;

        for ($i=0; $i < count($documents); $i++) 
        { 
            if (array_key_exists($searchTerm, $documents[$i]->getDocument()))
            {
                $matchValue = $this->idfIndex[$searchTerm] * $documents[$i]->getDocument()[$searchTerm];
            }
            else
            {
                $matchValue=0;
            }

            $this->lookupMatches["matches"] += ["Doc".$i => $matchValue];
        }

        return $this->lookupMatches;
    }
}

