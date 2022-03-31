<?php

declare(strict_types=1);

namespace App\Document;

use DateTimeInterface;
use Doctrine\ODM\MongoDB\Types\Type;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document]
class User
{
    #[ODM\Id]
    protected $id;

    #[ODM\Field(type: Type::STRING)]
    public string $name;

    #[ODM\Field(type: Type::STRING)]
    public string $email;

    #[Gedmo\Timestampable(on: 'create')]
    #[ODM\Field(type: Type::DATE)]
    protected DateTimeInterface $created;

    #[Gedmo\Timestampable(on: 'update', field: ['name', 'email'])]
    #[ODM\Field(type: Type::DATE)]
    protected DateTimeInterface $updated;

    #[ODM\ReferenceMany(targetDocument: BlogPost::class, mappedBy: 'author')]
    private Collection $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCreated(): DateTimeInterface
    {
        return $this->created;
    }

    public function getUpdated(): DateTimeInterface
    {
        return $this->updated;
    }

    public function posts(): Collection
    {
        return $this->posts;
    }

    public function addPost(BlogPost $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(BlogPost $post): static
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            $post->removeAuthor();
        }

        return $this;
    }
}