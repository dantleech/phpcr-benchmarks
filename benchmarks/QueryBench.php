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
    public static function beforeClass()
    {
        (new self)->loadDump('large_website.xml');
    }

    /**
     * @Groups({"query_single_prop"}, extend=true)
     * @ParamProviders({"provideQueries"})
     */
    public function benchQuery($params)
    {
        $query = $this->getQueryManager()->createQuery($params['query'], 'JCR-SQL2');
        $query->execute();
    }

    /**
     * @Groups({"query_single_prop"}, extend=true)
     * @ParamProviders({"provideQueries"})
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
     * @ParamProviders({"provideQueries"})
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
     * @ParamProviders({"provideProperties"})
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

    public function provideQueries()
    {
        return array(
            array(
                'query' => 'SELECT valves FROM [nt:unstructured]',
            ),
        );
    }

    public function provideProperties()
    {
        return array(
            array(
                'props' => array('valves'),
            ),
            array(
                'props' => array('valves', 'thistles'),
            ),
            array(
                'props' => array('valves', 'thistles', 'toppings', 'troikas'),
            ),
            array(
                'props' => array('valves', 'thistles', 'toppings', 'troikas', 'underrate', 'worksheets'),
            ),
        );
    }
}
