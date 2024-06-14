<?php

require './vendor/autoload.php';


use SaitamaHero\Conditions\{
    ConditionGroup,
    Equals, 
    ConditionInterface as Condition,
    MatchPattern,
    GreaterThan,
    GreaterOrEqualsThan,
    LessThan,
    LessOrEqualThan,
    IsEmpty,
};

use SaitamaHero\Conditions\Evaluators\ConditionEvaluator;
use SaitamaHero\Conditions\Evaluators\Evaluator;




$groupConditions = new ConditionGroup();
$groupConditions->add(new Equals([1,1], Condition::OR));
$groupConditions->add(new Equals(["b","a"], 'and', [
    'insensitive' => true
]));

// var_dump($groupConditions->explain(), $groupConditions->execute());
// die("hoa");

// $cond = new Equals([1,1], Condition::OR);
// return;

$groupConditions2 = new ConditionGroup();
// $groupConditions2->add(new Equals([1,1], Condition::OR));
$groupConditions2->add(new GreaterThan(["d","b"]));

// var_dump($groupConditions2->explain(), $groupConditions2->execute());
// die(
//     'ls'
// );

$groupConditions3 = new ConditionGroup();
$groupConditions3->add($groupConditions);
$groupConditions3->add(new MatchPattern(['Dionicio', 'o$'], Condition::AND));
$groupConditions3->add(new GreaterOrEqualsThan([1,1], Condition::AND));
// var_dump($groupConditions3->explain(), $groupConditions3->execute());
// die(
//     'ls'
// );

$evaluator = new ConditionEvaluator();
$evaluator->add($groupConditions3);
// $evaluator->add($groupConditions2);
// $evaluator->add(new Equals(["A","a"], Condition::AND, [
//     'insensitive' =>  true
// ]));

$evaluator->add(new IsEmpty([null], Condition::AND, [], false));
// $evaluator->add(new GreaterThan(["1.32","10.31"], Condition::OR));


// echo '<pre>';
// echo($evaluator->explain());
// echo '<pre>';
// var_dump($evaluator->execute());

class ConditionBuilder {
    protected $definedConditions = [
        'equals' => Equals::class,    
        'lessThan' => LessThan::class,
        'lessOrEquals' => LessOrEqualThan::class,
        'greaterThan' => GreaterThan::class,    
        'greaterOrEqualsThan' => GreaterOrEqualsThan::class,
        'match' => MatchPattern::class
    ];

    protected $ruleKeyMap = [
        'condition' => null,
        'params' => null,
        ''

    ];
    
    protected $conditions = [];


    protected $data = [];

    protected $evaluator = null;

    // protected $conditionArray = [];

    public function withData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function withEvaluator(Evaluator $evaluator) {
        $this->evaluator = $evaluator;
        return $this;
    }

    public function withConditionsArray(array $conditions) 
    {

        //TODO Do this apart
        foreach($conditions as $condition) {

            //TODO handle conditions group

            //TODO handle a better way
            $rawArguments = $condition['arguments'];

            $property = isset($rawArguments[0]) ? $this->getFromData($rawArguments[0]) : null;

            $arguments = [$property] + array_slice($rawArguments, 0);

            $boolean = isset($condition['boolean']) ? $condition['boolean'] : Condition::AND;

            $this->add($condition['condition'], $arguments, $boolean);
        }

    }

    protected function getFromData(string $key) {
        if (is_null($this->data)) {
            return $key;
        }

        if (is_array($this->data) || ($this->data instanceof ArrayAccess)){
            return isset($this->data[$key]) ? $this->data[$key] : $key;
        }

        return property_exists($this->data, $key) ? $this->data->{$key} : $key;
    }

    public function add(string $condition, array $params, string $logicalOperator) {
        if (!key_exists($condition, $this->definedConditions)) {
            throw new Exception("Error Processing Request", 1);
        }

        //TODO unshift the first element. then try to find on data or use null to compare

        $condition =  new $this->definedConditions[$condition]($params, $logicalOperator);

        $this->conditions[] = $condition;
        $this->evaluator->add($condition);

        return $this;
    }

    public function getEvaluator() {
        return $this->evaluator;
    }

    public function getConditions() {
        return $this->conditions;
    }
}

// $evaluator->addMultiple($this->conditions);


$rules = [
    [
        'condition' => 'equals',
        'arguments' => [
            'val1',
            'Hola'
        ],
        'options' => [
            'insensitive' => true,
        ]
    ],
    [
        'condition' => 'equals',
        'arguments' => [
            'first_name',
            'Dionicio'
        ],
        'options' => [
            'insensitive' => true,
        ],
        'boolean' => "OR"
    ],
    [
        'condition' => 'match',
        'arguments' => [
            'first_name',
            'oe$'
        ],
        'options' => [
            'insensitive' => true,
        ],
        'boolean' => "OR"
    ],
];

$data = [
    'first_name' => 'Joe Doe',
    'val1'  => "PEPE"
];


$conditionBuilder = new ConditionBuilder();

$ev = new ConditionEvaluator();
$conditionBuilder
->withEvaluator($ev)
->withData($data)
->withConditionsArray($rules);



echo '<pre>';
echo($ev->explain());
var_dump($ev->execute());
echo '</pre>';
