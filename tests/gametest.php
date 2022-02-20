<?php
use PHPUnit\Framework\TestCase;

require __DIR__ . "/../src/Entity/Game.php";
require __DIR__ . "/../src/Entity/Rating.php";
require __DIR__ . "/../src/Entity/user.php";

class GameTest extends TestCase {

   public function testImage_WithNull_ReturnsPlaceholder()  {
       $game = new Game();
       $game->setImagePath(null);
       $this->assertEquals('images/placeholder.png', $game->getImagePath());
   }

   public function testImage_WithPath_ReturnsPath() {
        $game = new Game();
        $game->setImagePath('images/game1.png');
        $this->assertEquals('images/game1.png', $game->getImagePath());
   }

   public function testAverageScore_WithoutRatings_ReturnsNull() {
        $game = new Game();
        $game->SetRatings([]);
        $this->assertNull($game->getAverageScore());

   }

   public function testAverageScore_With6And8_Returns7() {
        $rating1 = $this->createMock(Rating::class);
        $rating1->method('getScore')->willReturn(6);

        $rating2 = $this->createMock(Rating::class);
        $rating2->method('getScore')->willReturn(8);

        $game = $this->getMockBuilder(Game::class)
            ->setMethods(array('getRatings'))
            ->getMock();
        $game->method('getRatings')->willReturn([$rating1, $rating2]);
        $this->assertEquals(7, $game->getAverageScore());

   }

   public function testAverageScore_WithNullAnd5_Returns5()
   {
       $rating1 = $this->createMock(Rating::class);
       $rating1->method('getScore')->willReturn(null);

       $rating2 = $this->createMock(Rating::class);
       $rating2->method('getScore')->willReturn(5);

       $game = $this->getMockBuilder(Game::class)
           ->setMethods(array('getRatings'))
           ->getMock();
       $game->method('getRatings')->willReturn([$rating1, $rating2]);

       $this->assertEquals(5, $game->getAverageScore());
   }

   public function testIsRecommended_WithCompatibility2AndScore10_ReturnsFalse()
   {
       $rating1 = $this->createMock(Rating::class);
       $rating1->method('getScore')->willReturn(10);

       $user = $this->createMock(User::class);
       $user->method('getGenreCompatibility')->willReturn(2);

       $game = $this->getMockBuilder(Game::class)
        ->setMethods(array('getRatings'))
        ->getMock();
       $game->method('getRatings')->willReturn([$rating1]);

       $this->assertFalse($game->isRecommended($user));
    }

   public function testIsRecommended_WithCompatibility10AndScore10_ReturnsTrue()
   {
    $rating1 = $this->createMock(Rating::class);
    $rating1->method('getScore')->willReturn(10);

    $user = $this->createMock(User::class);
    $user->method('getGenreCompatibility')->willReturn(10);

    $game = $this->getMockBuilder(Game::class)
     ->setMethods(array('getRatings'))
     ->getMock();
    $game->method('getRatings')->willReturn([$rating1]);

    $this->assertTrue($game->isRecommended($user));
   }
}
