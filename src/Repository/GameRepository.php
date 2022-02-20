<?php

require __DIR__ . "/../Entity/Game.php";
require __DIR__ . "/../Entity/Rating.php";

class GameRepository {
   protected $pdo;
   public function __construct() {
       $this->pdo = new PDO(
           'mysql:host=localhost;dbname=gamebook_test','root',null);
   }
   public function findById($id) {
       $statement = $this->pdo->prepare('SELECT * FROM game WHERE id = ?');
       $statement->execute([$id]);
       $game = $statement->fetchObject('Game');
       return $game;
   }
   public function saveGameRating($gameId, $userId, $score) {
       $statement = $this->pdo->prepare(
          'REPLACE INTO rating (game_id, user_id, score)
          VALUES(?, ?, ?)');
       return $statement->execute([$gameId, $userId, $score]);
   }
   public function findByUserId($id) {
       $games = [];
       $statement = $this->pdo->prepare('SELECT * FROM game');
       $statement->execute([$id]);
       $games_array = $statement->fetchAll();
       //var_dump($games_array);
       foreach($games_array as $game_array){
           $game = new Game($game_array['id']);
           $game->setTitle($game_array['title']);
           $game->setImagePath($game_array['image_path']);
           $rating = new Rating();
           $rating->setScore(4.5);
           $game->setRatings([$rating]); 
           $games[] = $game;         
       }
       return $games;
   }
}
