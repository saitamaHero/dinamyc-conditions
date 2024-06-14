<?php

namespace SaitamaHero\Conditions\Traits;

use SaitamaHero\Conditions\ConditionInterface;

trait HasConditions {
    
    public function add(ConditionInterface $condition)
    {
        $this->conditions[] = $condition;
    }
    
    // public function addMultiple(array $conditions) {
    //     foreach ($conditions as $key => $condition) {
    //         $this->add($condition);
    //     }     
    // }

    public function execute() : bool
    {
        $conditions = $this->conditions;


        if (empty($conditions)) {
            throw new \Exception("Group Conditions can not execute without conditions");
        }

        $conditionResult = true;
    
        //TODO: check group conditions
        foreach ($conditions as $key => $condition) {

            // echo "Executing ".$condition->getLogicalOperator()." --".$condition->explain().PHP_EOL."<br>";

            if ($key == 0) {
                $conditionResult = $condition->execute();
                continue;
            }
            // if ($conditionResult && $condition->getLogicalOperator() === self::OR) {
            //     break;
            // }
            
            $currentResult = $condition->execute();

            // var_dump(['cr1' => $conditionResult, 'cr2' => $currentResult]);

            $conditionResult = $condition->getLogicalOperator() === "OR" ? 
                $conditionResult || $currentResult :
                $conditionResult && $currentResult;
        }

        return $conditionResult;
    }

    public function explain() : string {
        
        if (empty($this->conditions)) {
            return "";
        }

        $buffer = "";

        foreach ($this->conditions as $key => $condition) {

            if (empty($buffer)) {
                $buffer .= $condition->explain();            
            }else {
                $buffer .= sprintf("%s %s %s",
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