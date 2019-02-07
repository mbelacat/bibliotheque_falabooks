<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Book;

class BookFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {

      for ($i = 0; $i < 20; $i++) {
          $book = new Book();
          $book->setTitle('$book '.$i);
          $book->setAuthor('Michel'.$i.' Houellebeck'.$i);
          $book->setEditor('Gallimard'.mt_rand(0, 9));
          $book->setAvailable(mt_rand(0, 1));
          $book->setSummary("Lorem ipsum dolor sit amet,
            consectetur adipiscing elit, sed do eiusmod tempor
            incididunt ut labore et dolore magna aliqua.
            Ut enim ad minim veniam, quis nostrud exercitation
            ullamco laboris nisi ut aliquip ex ea commodo consequat.
            Duis aute irure dolor in reprehenderit in voluptate
            velit esse cillum dolore eu fugiat nulla pariatur.
            Excepteur sint occaecat cupidatat non proident,
            sunt in culpa qui officia deserunt mollit anim id est laborum.");
          $book->setReleaseDate(new \DateTime('@'.strtotime('now')));
          $book->persist($book);
      }
      $manager->flush();

    }
}
