<?php

namespace PHPNomad\Datastore\Factories;

class IdentityToWhere
{
    protected array $identity;

    public function __construct(array $identity)
    {
        $this->identity = $identity;
    }

    /**
     * Returns the where statement for a given identity.
     *
     * @return array
     */
    public function toWhere(): array
    {
        $where = [];
        foreach($this->identity as $column => $id){
            $where[] = ['column' => $column, 'operator' => '=', 'value' => $id];
        }

        return $where;
    }
}