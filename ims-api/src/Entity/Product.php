<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: '`products`')]
final class Product
{
    public function __construct(Organization $organization, string $name) {
        $this->organization = $organization;
        $this->name = $name;
        $this->tags = new ArrayCollection();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue("IDENTITY")]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $name;

    #[ORM\ManyToOne(targetEntity: ProductProvider::class, cascade: ['persist'])]
    private ProductProvider $provider;

    /** @var Collection<Tag> */
    #[ORM\ManyToMany(targetEntity: Tag::class, cascade: ['persist'])]
    private Collection $tags;

    #[ORM\ManyToOne(targetEntity: 'Organization', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private Organization $organization;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrganization(): Organization
    {
        return $this->organization;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param Collection<Tag> $tags
     * @return void
     */
    public function setTags(Collection $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @return Collection<Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function getProvider(): ProductProvider
    {
        return $this->provider;
    }

    public function setProvider(ProductProvider $provider): void
    {
        $this->provider = $provider;
    }
}
