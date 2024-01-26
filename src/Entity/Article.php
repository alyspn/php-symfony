<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ArticleRepository;


#[ORM\Entity(repositoryClass: ArticleRepository::class)]

class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'text')]
    private $body;

    #[ORM\Column(type: 'datetime')]
    private $publicationDate;

    private ?File $imageFile = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $imageName;

    #[ORM\Column(type: 'string', length: 255)]
    private $status;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'articles')]
    private $author;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'articles')]
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->publicationDate = new \DateTime(); 
        $this->status = 'pending'; 
    }

   
    public function getId(): ?int
    {
        return $this->id;
    }
	
	public function getBody(): ?string
{
    return $this->body;
}

public function setBody(string $body): self
{
    $this->body = $body;
    return $this;
}

public function getPublicationDate(): ?\DateTimeInterface
{
    return $this->publicationDate;
}

public function setPublicationDate(\DateTimeInterface $publicationDate): self
{
    $this->publicationDate = $publicationDate;
    return $this;
}

public function getAuthor(): ?User
{
    return $this->author;
}

public function setAuthor(?User $author): self
{
    $this->author = $author;
    return $this;
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

    

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if ($imageFile) {
            $this->publicationDate = new \DateTime('now'); 
        }
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addArticle($this);
        }
        return $this;
    }

    public function removeTag(Tag $tag): self
{
    if ($this->tags->removeElement($tag)) {
        $tag->removeArticle($this); 
    }

    return $this;
}

	

    
}
