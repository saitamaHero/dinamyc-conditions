<?php

namespace SaitamaHero\Conditions;

class GreaterThan extends BaseCondition
{
    public function evaluate($a, $b) : bool
    {                
        if (is_numeric($a) || is_numeric($b)) {
            if (strpos($a, ".") || strpos($b, ".")) {
                return $this->compareFloats((float)$a, (float)$b);
            }else {
                return $this->compareInts((int)$a, (int)$b);
            }
        }else {
            return $this->compareStrings($a, $b);
        }

        return false;
    }

    protected function compareStrings(string $a, string $b)
    {        
        return strcmp($a, $b) > 0;
    }

    protected function compareInts(int $a, int $b)
    {
        return $a > $b;
    }

    protected function compareFloats(float $a, float $b)
    {
        return $a > $b;
    }

    public function explain() : string 
    {
        return sprintf("'%s' %s '%s'", 
            $this->arguments[0],
            ">",
            $this->arguments[1]
        );
    }
}