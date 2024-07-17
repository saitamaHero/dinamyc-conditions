<?php

namespace SaitamaHero\Conditions\Traits;

use SaitamaHero\Conditions\ConditionInterface;

trait HasConditions
{

    public function add(ConditionInterface $condition)
    {
        $this->conditions[] = $condition;
    }

    // public function addMultiple(array $conditions) {
    //     foreach ($conditions as $key => $condition) {
    //         $this->add($condition);
    //     }
    // }

    protected function getConditions(): array
    {
        return $this->conditions;
    }

    public function execute(): bool
    {
        $conditions = $this->getConditions();


        if (empty($conditions)) {
            throw new \Exception("Group Conditions can not execute without conditions");
        }


        $conditionResult = array_shift($conditions)->execute();

        $lastLogicOperator = ConditionInterface::AND;

        $boolToString = function (bool $val) {
            return $val ? "TRUE" : "FALSE";
        };

        //TODO: check group conditions
        foreach ($conditions as $key => $condition) {
            $currentResult = $condition->execute();

            if ($conditionResult && strcasecmp($lastLogicOperator, ConditionInterface::OR)  === 0) {
                break;
            }

            $r = $condition->getLogicalOperator() === "OR" ?
                $conditionResult || $currentResult :
                $conditionResult && $currentResult;

            $lastLogicOperator = $condition->getLogicalOperator();

            // echo sprintf(
            //     "%s %s %s => %s<br>",
            //     $boolToString($conditionResult),
            //     $condition->getLogicalOperator(),
            //     $boolToString($currentResult),
            //     $boolToString($r)
            // );

            // var_dump(['cr1' => $conditionResult, 'cr2' => $currentResult, 'r' => $r]);

            $conditionResult = $r;
        }

        // var_dump($this->conditions);

        return $conditionResult;
    }

    public function explain(): string
    {

        if (empty($this->conditions)) {
            return "";
        }

        $buffer = "";

        foreach ($this->conditions as $key => $condition) {

            if (empty($buffer)) {
                $buffer .= $condition->explain();
            } else {
                $buffer .= sprintf(
                    "%s %s %s",
                    "\n",
                    \strtoupper($condition->getLogicalOperator()),
                    $condition->explain()
                );
            }
        }

        return count($this->conditions) > 1  ?
            sprintf("( %s ) ", $buffer)
            : $buffer;
    }
}
