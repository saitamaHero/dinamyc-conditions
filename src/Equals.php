<?php

namespace SaitamaHero\Conditions;


class Equals extends BaseCondition
{
    protected $options = [
        'insensitive' => false,        
    ];

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
        $compareFn = 'strcmp';

        if ($this->getOption('insensitive', false)) {
            $compareFn = 'strcasecmp';
        }

        return call_user_func($compareFn, $a, $b) === 0;
    }

    protected function compareInts(int $a, int $b)
    {
        return ($a - $b) === 0;
    }

    protected function compareFloats(float $a, float $b)
    {
        return abs($a - $b) < PHP_FLOAT_EPSILON;
    }

    public function explain() : string 
    {
        return sprintf("'%s' %s '%s'", 
            $this->arguments[0],
            "=",
            $this->arguments[1]
        );
    }
}