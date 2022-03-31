<?php

declare(strict_types=1);

namespace App\Document;

use DateTimeInterface;
use Doctrine\ODM\MongoDB\Types\Type;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document]
class BlogPost
{
    #[ODM\Id]
    private string $id;

    #[ODM\Field(type: Type::STRING)]
    public string $title;

    #[ODM\Field(type: Type::STRING)]
    public string $body;

    #[Gedmo\Timestampable(on: 'create')]
    #[ODM\Field(type: Type::DATE)]
    protected DateTimeInterface $created;

    #[Gedmo\Timestampable(on: 'update')]
    #[ODM\Field(type: Type::DATE)]
    protected DateTimeInterface $updated;

    #[ODM\ReferenceOne(targetDocument: User::class, inversedBy: 'posts')]
    protected ?User $author;

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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author): static
    {
        $this->author = $author;
        return $this;
    }

    public function removeAuthor(): static
    {
        $this->author = null;

        return $this;
    }


}