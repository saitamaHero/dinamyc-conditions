<?php

namespace SaitamaHero\Conditions;

interface ConditionInterface {
    const AND = 'and';
    const OR = 'or';

    public function execute(): bool;

    public function getLogicalOperator() : ?string;

    public function explain() : string;
}