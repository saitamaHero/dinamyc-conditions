<?php

namespace SaitamaHero\Conditions\Evaluators;

use SaitamaHero\Conditions\Traits\HasConditions;

class ConditionEvaluator implements Evaluator
{
    use HasConditions;

    protected $conditions = [];

    public function getLogicalOperator(): ?string
    {
        return null;
    }

    public function reset()
    {
        $this->conditions = [];
    }
}
