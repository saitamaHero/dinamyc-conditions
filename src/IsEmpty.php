<?php

namespace SaitamaHero\Conditions;

class IsEmpty extends BaseCondition
{
    public function evaluate($value) : bool
    {   
        // if (function_exists("preg_match")) {
        //     return (bool)preg_match("/$pattern/", $value);
        // }
        
        return $this->not ? !empty($value) : empty($value);
    }

    public function explain() : string 
    {
        return sprintf("'%s' %s%s", 
            $this->arguments[0],
            $this->not ? "NOT " : "",
            "IS EMPTY",
            $this->arguments[1]
        );
    }
}
