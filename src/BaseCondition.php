<?php

namespace SaitamaHero\Conditions;

abstract class BaseCondition implements ConditionInterface
{
    protected $arguments = [];

    /**
     * @var ?string
     */
    protected $logicalOperator = null;

    /**
     * @var array<string,mixed>
     */
    protected $options = [];

    /**
     * @var bool
     */
    protected $not = false;

    public function __construct(array $args, $logicalOperator = self::AND, array $options = [], bool $not = false) {
        $this->arguments = $args;
        $this->logicalOperator = $logicalOperator;        
        $this->not = $not;
        
        $this->setOptions($options);
    }

    public function getLogicalOperator() : ?string {
        return $this->logicalOperator;
    }

    public function hasEnoughParams() 
    {
        return count($this->arguments) > 0;
    }

    public function setOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }

    public function getOptions()
    {
        return $this->$options;
    }

    public function getOption(string $option, $default = null)
    {
        return isset($this->options[$option]) ? $this->options[$option] : $default;
    }

    public function execute() : bool
    {
        if (func_num_args() > 0) {
            $this->arguments = func_get_args();
        }

        if (!$this->hasEnoughParams()) {
            throw new Exception("This condition has inalid parameter count.", 1);
            
        }

        // var_dump($this->arguments);
        // return false;

        if (method_exists($this, 'evaluate')) {
            return $this->evaluate(...$this->arguments);
        }


        return false;
    }

    public function explain() : string {
        throw new Exception("Not Implemented");
    }

}
