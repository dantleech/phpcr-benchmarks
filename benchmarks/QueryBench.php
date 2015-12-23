<?php

namespace PHPCR\Benchmark\Query;

use PhpBench\Benchmark\Iteration;
use PHPCR\Benchmark\BaseBench;

/**
 * @Groups({"query"})
 * @BeforeClassMethods({"beforeClass"})
 */
class QueryBench extends BaseBench
{
    private $query;

    public static function beforeClass()
    {
        (new self)->loadDump('large_website.xml');
    }

    public function setUp($params)
    {
        $properties = array('valves', 'thistles', 'toppings', 'troikas', 'underrate', 'worksheets');
        $properties = array_splice($properties, 0, $params['nb_props']);

        if ($params['size'] === 'large') {
            $clause = '';
        } else {
            $clause = ' WHERE valves IS NOT NULL';
        }

        $this->query = $this->getQueryManager()->createQuery(sprintf(
            'SELECT %s FROM [nt:unstructured] %s',
            implode(', ', $properties),
            $clause
        ), 'JCR-SQL2');
    }

    /**
     * @Groups({"query_single_prop"}, extend=true)
     * @ParamProviders({"provideNbProps", "provideSize"})
     * @BeforeMethods({"setUp"})
     */
    public function benchQuery()
    {
        $this->query->execute();
    }

    /**
     * @Groups({"query_single_prop"}, extend=true)
     */
    public function benchQueryWithNodes($params)
    {
        $query = $this->getQueryManager()->createQuery($params['query'], 'JCR-SQL2');
        $results = $query->execute();

        foreach ($results as $result) {
            $result->getNode();
        }
    }

    /**
     * @Groups({"query_single_prop"}, extend=true)
     */
    public function benchQueryIterate($params)
    {
        $query = $this->getQueryManager()->createQuery($params['query'], 'JCR-SQL2');
        $results = $query->execute();

        foreach ($results as $result) {
            $result->getNode();
        }
    }

    /**
     * @Groups({"query_variable_prop"}, extend=true)
     */
    public function benchQueryIterateVariableProperties($params)
    {
        $props = $params['props'];
        $query = $this->getQueryManager()->createQuery(sprintf(
            'SELECT %s FROM [nt:unstructured] WHERE valves IS NOT NULL'
        , implode(', ', $props)), 'JCR-SQL2');

        $results = $query->execute();

        foreach ($results as $row) {
            foreach ($props as $prop) {
                $row->getValue($prop);
            }
        }
    }

    public function provideNbProps()
    {
        return array(
            array(
                'nb_props' => 1,
            ),
            array(
                'nb_props' => 4,
            ),
        );
    }

    public function provideSize()
    {
        return array(
            array(
                'size' => 'large',
            ),
            array(
                'size' => 'small',
            ),
        );
    }
}
