<?php
 
namespace App\Objects;

class IdfIndex
{
    /**
     * @var array containing the IDF of all tokens inside a collection
     */
    private $idfIndex = [];

    /**
     * @return returns the IDF array of a collection's tokens
     */
    public function getIdfIndex()
    {
        return $this->idfIndex;
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
}

