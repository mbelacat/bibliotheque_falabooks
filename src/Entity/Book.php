<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 */
class Book
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $editor;

    /**
     * @ORM\Column(type="text")
     */
    private $summary;

    /**
     * @ORM\Column(type="datetime")
     */
    private $releaseDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbPage;

    /**
     * @ORM\Column(type="boolean")
     */
    private $available;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="books")
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="books")
     */
    private $borrower;

    /**

     * @ORM\ManyToOne(targetEntity="App\Entity\Library", inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     */
    private $library;
    
     /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $borrowingDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $returnDate;





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getEditor(): ?string
    {
        return $this->editor;
    }

    public function setEditor(?string $editor): self
    {
        $this->editor = $editor;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getNbPage(): ?int
    {
        return $this->nbPage;
    }

    public function setNbPage(int $nbPage): self
    {
        $this->nbPage = $nbPage;

        return $this;
    }

    public function getAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): self
    {
        $this->available = $available;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getBorrower(): ?User
    {
        return $this->borrower;
    }

    public function setBorrower(?User $borrower): self
    {
        $this->borrower = $borrower;
        if($borrower) {
          $this->setAvailable(0);
        }
        else {
          $this->setAvailable(1);
        }
        return $this;
    }


    public function getLibrary(): ?Library
    {
        return $this->library;
    }

    public function setLibrary(?Library $library): self
    {
        $this->library = $library;
    }

    public function getBorrowingDate(): ?\DateTimeInterface
    {
        return $this->borrowingDate;
    }

    public function setBorrowingDate(?\DateTimeInterface $borrowingDate): self
    {
        $this->borrowingDate = $borrowingDate;

        return $this;
    }

    public function getReturnDate(): ?\DateTimeInterface
    {
        return $this->returnDate;
    }

    public function setReturnDate(?\DateTimeInterface $returnDate): self
    {
        $this->returnDate = $returnDate;


        return $this;
    }


}
