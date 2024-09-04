<?php

namespace PHPNomad\Mutator\Traits;

use PHPNomad\Mutator\Interfaces\MutationAdapter;

trait CanMutateFromAdapter{

    protected MutationAdapter $mutationAdapter;

    public function mutate(...$args)
    {
        $mutation = $this->mutationAdapter->convertFromSource(...$args);
        $mutation->mutate();

        return $this->mutationAdapter->convertToResult($mutation);
    }
}