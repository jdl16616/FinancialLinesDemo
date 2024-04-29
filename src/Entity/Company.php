<?php

namespace App\Entity;

// Entity traits
use App\Entity\Trait\Column\Id as EntityTraitId;

// Vendor
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\Company\Fetch")]
#[ORM\Table(name: "Company")]
class Company
{
    // Columns
    use EntityTraitId;

    #[ORM\Column(type: "string", length: 100)]
    private $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}