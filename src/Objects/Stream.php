<?php
 
namespace App\Objects;

use App\Objects\Document;
use App\Objects\DocumentCollection;

class Stream
{
    /**
     * @var The data to be fetched from the request
     */
    private $document;

    /**
     * @var A collection of fetched documents to be passed for further analysis
     */
    private $documentCollection;


    /**
     * @return App\Objects\Document
     */
    public function getDocument()
    {
        return $this->document;
    }

    public function __construct(Document $document, DocumentCollection $documentCollection)
    {
        $this->document = $document;
        $this->documentCollection = $documentCollection;
    }

    /**
     * @param stream $stream stream reference used for fetching text data
     *
     * @return App\Objects\Document
     */
    public function readDocument($stream)
    {
        $this->document = new Document();
        $handle = @fopen($stream, "r");
        $meta = stream_get_meta_data($handle);

        if ($handle) 
        {
            while (!feof($handle)) 
            {
                $line = fgets($handle);
                $this->document->tokenize($line);
                $this->document->calculateTermFrequency();
            }
            fclose($handle);
        }

        return $this->document;
    }

    /**
     * @param stream list $streamList list used for fetching data, each stream is a connection to a different document
     *
     * @return App\Objects\DocumentCollection
     */
    public function readMultipleDocuments($streamList)
    {
        for ($i=0; $i < count($streamList); $i++) 
        {
            $this -> readDocument($streamList[$i]);
            $this->documentCollection -> addDocument($this-> getDocument());
        }

        return $this->documentCollection;
    }
}

