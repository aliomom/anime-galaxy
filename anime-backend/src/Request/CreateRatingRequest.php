<?php

namespace App\Request;

use DateTime;

class CreateRatingRequest
{
    private $userID;
    public  $animeID;
    private $rateValue;
    public $creationDate;

    public function __construct($creationDate)
    {
        $this->creationDate = new DateTime($creationDate);
    }

    /**
     * @param mixed $userID
     */
    public function setUserId($userID)
    {
        $this->userID = $userID;

        return $this;
    }

    /**
     * @return mixed
     */  
    public function getAnimeId()
    {
        return $this->animeID;
    }

    /**
     * @param mixed $animeID
     */
    public function setAnimeId($animeID)
    {
        $this->animeID = $animeID;

        return $this;
    }

    /**
     * @return mixed
     */ 
    public function getRateValue()
    {
        return $this->rateValue;
    }

   /**
     * @param mixed $rateValue
     */
    public function setRateValue($rateValue)
    {
        $this->rateValue = $rateValue;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreationDate(): DateTime
    {
        return $this->creationDate;
    }

    /**
     * @param DateTime $creationDate
     */
    public function setCreationDate(DateTime $creationDate): void
    {
        $this->creationDate = $creationDate;
    }
}