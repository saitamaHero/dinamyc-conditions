<?php

namespace SaitamaHero\Conditions;

use SaitamaHero\Conditions\Traits\HasConditions;

class ConditionGroup implements ConditionInterface
{
    use HasConditions;

    protected $conditions = [];

    protected $logicalOperator = self::AND;

    public function getLogicalOperator() : ?string {
        $conditions = $this->conditions;

        return array_shift($conditions)->getLogicalOperator();
    }
}