<?php

namespace App\Objects;

class Document
{
    /**
     * @var The number of words inside the document
     */
    private $wordCount;

    /**
     * @var a 2d array containing the list of document tokens
     * (tokens can be: vectorized occourances, relativized occourances and idf weighted occourances)
     */
    private $tokens=[];

    /**
     * @return array of tokens
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * @param tokens $tokens to be set for this document
     *
     * @return token array
     */
    public function setTokens($tokens)
    {
        $this->tokens = $tokens;
        return $this->tokens;
    }

    /**
     * @return array of tokens
     */
    public function getDocument(){
        return $this->tokens;
    }
    /**
     * @return int word count
     */
    public function getWordCount()
    {
        return $this->wordCount;
    }

    /**
     * @param text $text to be split into word chunks (tokens)
     *
     * @return array of tokens
     */
    public function tokenize($text)
    {
        $this->tokens=preg_split('/\W/', strtolower($text), 0, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * @param relativize $relativize boolean value which determines if the token occourances should be vectorized
     *
     * @return sorted array of tokens which contains the list of tokens and their occourances (non-vectorized or vectorized)
     */
    public function calculateTermFrequency($relativize = true)
    {
        $this->wordCount = count($this->tokens);
        $this->tokens = array_count_values($this->tokens);

        if ($relativize) 
        {
            foreach($this->tokens as $key => $value) 
            {
                $this->tokens[$key] = $value/$this->wordCount;
            }
        }

        return arsort($this->tokens);
    }
}