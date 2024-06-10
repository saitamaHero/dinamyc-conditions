<?php

namespace SaitamaHero\Conditions;

class MatchPattern extends BaseCondition
{
    public function evaluate($value, $pattern) : bool
    {   
        if (function_exists("preg_match")) {
            return (bool)preg_match("/$pattern/", $value);
        }
        
        return false;
    }

    public function explain() : string 
    {
        return sprintf("'%s' %s /%s/", 
            $this->arguments[0],
            "MATCH PATTERN",
            $this->arguments[1]
        );
    }
}
