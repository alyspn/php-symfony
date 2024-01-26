<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface; 
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=App\Repository\UserRepository::class)
 */
#[ORM\Entity(repositoryClass: 'App\Repository\UserRepository')]
#[ApiResource]
#[Get(normalizationContext: ['groups' => ['user:read']])]
#[Post(denormalizationContext: ['groups' => ['user:write']])]
#[Put(denormalizationContext: ['groups' => ['user:update']])]
#[Delete]
class User implements UserInterface, PasswordAuthenticatedUserInterface 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['user:read'])] 
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:read', 'user:write'])] 
    private $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:read', 'user:write'])] 
    private $lastName;


    #[ORM\Column(type: 'string', length: 2)]
    #[Groups(['user:create', 'user:update'])]
    private $countryCode;


    #[ORM\Column(type: 'string')]
    private $email;

    /**
     * @var string The hashed password
     */
    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: 'App\Entity\Article')]
    private $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
	
	public function getFirstName(): ?string
		{
			return $this->firstName;
		}

		public function setFirstName(string $firstName): self
		{
			$this->firstName = $firstName;
			return $this;
		}

		public function getLastName(): ?string
		{
			return $this->lastName;
		}

		public function setLastName(string $lastName): self
		{
			$this->lastName = $lastName;
			return $this;
		}


    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getSalt()
    {
        
        return null;
    }

    public function eraseCredentials(): void
    {
        
    }

    
    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setAuthor($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            
            if ($article->getAuthor() === $this) {
                $article->setAuthor(null);
            }
        }

        return $this;
    }

    


    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): self
    {
        $this->countryCode = $countryCode;
        return $this;
    }

}
