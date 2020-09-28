<?php


namespace App\Service;


use App\AutoMapping;
use App\Entity\Anime;
use App\Manager\AnimeManager;
use App\Response\CreateAnimeResponse;
use App\Response\GetAnimeByIdResponse;
use App\Response\GetAnimeResponse;
use App\Response\GetAnimeByCategoryResponse;
use App\Response\UpdateAnimeResponse;
use App\Response\GetHighestRatedAnimeResponse;
use App\Response\GetHighestRatedAnimeByUserResponse;

class AnimeService
{
    private $animeManager;
    private $autoMapping;
    private $imageService;
    private $commentService;
    private $interactionService;
  

    public function __construct(AnimeManager $animeManager, AutoMapping $autoMapping, ImageService $imageService, CommentService $commentService,
                        InteractionService $interactionService)
    {
        $this->animeManager = $animeManager;
        $this->autoMapping = $autoMapping;
        $this->imageService = $imageService;
        $this->commentService = $commentService;
        $this->interactionService = $interactionService;
       
    }

    public function createAnime($request)
    {
        $animeResult = $this->animeManager->create($request);
        return $this->autoMapping->map(Anime::class, CreateAnimeResponse::class, $animeResult);
    }

    public function getAnimeById($request)
    {
        /** @var $response GetAnimeByIdResponse*/
        $response = [];

        $result = $this->animeManager->getAnimeById($request);

        $resultImg = $this->imageService->getImagesByAnimeID($request);
        $resultComments = $this->commentService->getCommentsByAnimeId($request);
        $love = $this->interactionService->loved($request);
        $like = $this->interactionService->like($request);

        foreach ($result as $row)
        {
            $response = $this->autoMapping->map('array', GetAnimeByIdResponse::class, $row);
        }

        $response->setImage($resultImg);
        $response->setComments($resultComments);
        $response->interactions['love'] = $love;
        $response->interactions['like'] = $like;

        return $response;
    }

    public function getAllAnime()
    {
        /** @var $response GetAnimeResponse*/
        $result = $this->animeManager->getAllAnime();
        $response = [];
        
        foreach ($result as $row)
        {
            $row['interaction']=[
            'love' => $this->interactionService->lovedAll($row['id']),
            'like' => $this->interactionService->likeAll($row['id'])
            ];
            $response[] = $this->autoMapping->map('array', GetAnimeResponse::class, $row);
          
        }
        return $response;
    }

    public function getAnimeByCategoryID($categoryID)
    {
        $result = $this->animeManager->getByCategoryID($categoryID);
        $response = [];
        
        foreach ($result as $row)
        {
            $row['interaction']=[
                'love' => $this->interactionService->lovedAll($row['id']),
                'like' => $this->interactionService->likeAll($row['id'])
                ];
            $response[] = $this->autoMapping->map('array', GetAnimeByCategoryResponse::class, $row);
        }
        return $response;
    }

    public function update($request)
    {
        $animeResult = $this->animeManager->update($request);
        $response = $this->autoMapping->map(Anime::class, UpdateAnimeResponse::class, $animeResult);
        $response->setName($request->getName());
        $response->setMainImage($request->getMainImage());
        return $response;
    }

    public function deleteAnime($request)
    {
        $animeResult = $this->animeManager->delete($request);
        if($animeResult == null)
        {
            return null;
        }
        return  $this->autoMapping->map(Anime::class, GetAnimeByIdResponse::class, $animeResult);
        
    }

    public function getHighestRatedAnime()
    {
        $response = [];
        $result = $this->animeManager->getHighestRatedAnime();

        foreach ($result as $row)
        {
            $response[] = $this->autoMapping->map('array', GetHighestRatedAnimeResponse::class, $row);
        }

        return $response;
    }

    public function getHighestRatedAnimeByUser($userID)
    {
        $response = [];
        $result = $this->animeManager->getHighestRatedAnimeByUser($userID);

        foreach ($result as $row)
        {
            $response[] = $this->autoMapping->map('array', GetHighestRatedAnimeByUserResponse::class, $row);
        }
      
        return $response;
    }
}