<?php

namespace App\DataFixtures;
use App\Entity\Library;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\DateTime;


class AppFixtures extends Fixture
{
    // ...
    private $encoder;
    private $libraries;
    private $categories;
    private $roles = [0 => ['ROLE_USER'], 1 => ['ROLE_ADMIN']];

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // create 3 libraries! Bam!
        for ($i = 0; $i < 3; $i++) {
            $library = new Library();
            $library->setName('FalaBooks'.$i);
            $manager->persist($library);
            $this->libraries[] = $library; //tableau des librairies
        }

        //$manager->flush();

        // create 20 users! Bam!
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setLastName('Mbemba'.$i);
            $user->setFirstName('Mbela'.$i);
            $user->setEmail('mbela'.$i.'@gmail.com');
            $user->setLogin('login'.$i);
            $password = $this->encoder->encodePassword($user, 'test'.$i); //cryptage du password
            $user->setPassword($password);
            //$user->setRoles(['ROLE_USER']);
            $user->setRoles($this->roles[rand(0, count($this->roles)-1)]); //choix aleatoire du role access
            $user->setLibrary($this->libraries[rand(0, count($this->libraries)-1)]); //choix aleatoire de la librairie
            $manager->persist($user);
        }

        //$manager->flush();

        // create 5 categories! Bam!
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->setName('Categorie'.$i);
            $manager->persist($category);
            $this->categories[] = $category; //tableau des categories
        }
        //$manager->flush();

        // create 10 books! Bam!
        for ($i = 0; $i < 100; $i++) {
            $book = new Book();
            $book->setTitle('Titre'.$i);
            $book->setAuthor('Auteur'.$i);
            $book->setEditor('Editeur'.$i);
            $book->setSummary('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque congue efficitur mollis. Integer in aliquet urna, ut iaculis ipsum. Suspendisse potenti. Sed id leo sagittis, consequat mi eget, interdum augue. Maecenas vitae tellus finibus lorem blandit elementum. Mauris sit amet posuere lacus. Morbi eleifend odio sed ante porta, eget tempor nisl facilisis. Nulla mauris orci, luctus eu aliquet nec, efficitur elementum dolor. Ut sapien purus, pellentesque tincidunt condimentum ac, rutrum non odio. Phasellus dictum lacinia magna non gravida. Duis gravida viverra arcu sit amet hendrerit. Integer faucibus ante nec ipsum scelerisque pellentesque. Sed velit neque, fringilla in malesuada eget, sodales id augue.');
            $book->setNbPage(mt_rand(25,300));
            $book->setAvailable(1);
            $book->setReleaseDate(new \DateTime());
            $book->setCategory($this->categories[rand(0, count($this->categories)-1)]); //choix aleatoire de la categorie
            $book->setLibrary($this->libraries[rand(0, count($this->libraries)-1)]); //choix aleatoire de la librairie
            $manager->persist($book);
        }

        $manager->flush();
    }

}
