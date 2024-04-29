<?php

namespace App\Entity\Trait\ColumnAssociated;

use App\Entity\Statement as EntityStatement;
use Doctrine\ORM\Mapping as ORM;

trait Statement
{
    #[ORM\ManyToOne(targetEntity: EntityStatement::class)]
    #[ORM\JoinColumn(name: "statement_id", referencedColumnName: "id")]
    private $statement;

    /**
     * @return EntityStatement
     */
    public function getStatement():EntityStatement
    {
        return $this->statement;
    }

    /**
     * @param EntityStatement $statement
     */
    public function setStatement($statement):void
    {
        $this->statement = $statement;
    }


}