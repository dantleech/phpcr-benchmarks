<?php

namespace PHPCR\Benchmark\Query;

use PhpBench\Benchmark\Iteration;
use PHPCR\Benchmark\BaseBench;

class QueryBench extends BaseBench
{
    /**
     * @description Run a select query
     * @paramProvider provideQueryVariableProperties
     * @paramProvider provideNbNodes
     * @beforeMethod beforeCreateNodes
     * @iterations 1
     */
    public function benchQuery(Iteration $iteration)
    {
        $query = $this->getQueryManager()->createQuery($iteration->getParameter('query'), 'JCR-SQL2');
        $query->execute();
    }

    /**
     * @description Run a select query and iterate
     * @paramProvider provideQueryVariableProperties
     * @paramProvider provideNbNodes
     * @beforeMethod beforeCreateNodes
     * @iterations 1
     */
    public function benchQueryAndIterate(Iteration $iteration)
    {
        $query = $this->getQueryManager()->createQuery($iteration->getParameter('query'), 'JCR-SQL2');
        $result = $query->execute();

        foreach ($result->getRows() as $row) {
            $row->getPath();
        }
    }

    /**
     * @description Run a select query and get node for each result
     * @paramProvider provideQueryVariableProperties
     * @paramProvider provideNbNodes
     * @beforeMethod beforeCreateNodes
     * @iterations 2
     */
    public function benchQueryAndIterateWithNode(Iteration $iteration)
    {
        $query = $this->getQueryManager()->createQuery($iteration->getParameter('query'), 'JCR-SQL2');
        $result = $query->execute();

        foreach ($result->getRows() as $row) {
            $row->getNode()->getPath();
        }
    }

    /**
     * @description Search by property
     * @paramProvider provideNbNodesWithSections
     * @beforeMethod beforeCreateNodes
     * @beforeMethod beforeAppendNode
     * @iterations 2
     */
    public function benchSearchByProperty()
    {
        $query = $this->getQueryManager()->createQuery(
            'SELECT * FROM [nt:unstructured] WHERE my_new_property = "foobar"'
            , 'JCR-SQL2');
        $results = $query->execute();

        foreach ($results as $result) {
            $result->getNode();
        }
    }

    /**
     * @description Search by property in a subpath
     * @paramProvider provideNbNodesWithSections
     * @beforeMethod beforeCreateNodes
     * @beforeMethod beforeAppendNode
     * @iterations 2
     */
    public function benchSearchByPropertyInSubpath()
    {
        $query = $this->getQueryManager()->createQuery(
            'SELECT * FROM [nt:unstructured] WHERE my_new_property = "foobar" AND ISDESCENDANTNODE("/bench/section-0")'
            , 'JCR-SQL2');
        $results = $query->execute();

        foreach ($results as $result) {
            $result->getNode();
        }
    }

    /**
     * @description Full text search within property
     * @paramProvider provideNbNodesWithSections
     * @beforeMethod beforeCreateNodes
     * @beforeMethod beforeAppendNode
     * @iterations 2
     */
    public function benchSearchPropertyFullText()
    {
        $query = $this->getQueryManager()->createQuery(
            'SELECT * FROM [nt:unstructured] WHERE CONTAINS(my_new_property, "foobar")'
            , 'JCR-SQL2');
        $results = $query->execute();

        foreach ($results as $result) {
            $result->getNode();
        }
    }

    public function beforeCreateNodes(Iteration $iteration)
    {
        // do not recreate nodes in the same iteration set
        if ($iteration->getIndex() > 0) {
            $this->resetSession();
            return;
        }

        $this->resetWorkspace();

        // warmup the connection
        $this->getQueryManager()->createQuery('SELECT * FROM [nt:unstructured]', 'JCR-SQL2')->execute();

        $this->createNodes($this->getRootNode(), $iteration->getParameter('nb_nodes'), array(
            'string' => 'Hello',
            'number' => 10,
            'hello' => 'goodbye',
            'goodbye' => 'hello',
        ), $iteration->getParameter('sections'), 0);

        $this->getSession()->save();
    }

    public function beforeAppendNode(Iteration $iteration)
    {
        // do not recreate nodes in the same iteration set
        if ($iteration->getIndex() > 0) {
            return;
        }

        $this->createNodes($this->getRootNode(), 1, array(
            'my_new_property' => 'foobar',
        ), 1, 10000);

        $this->getSession()->save();
    }

    public function provideQueryVariableProperties()
    {
        return array(
            array(
                'query' => 'SELECT * FROM [nt:unstructured]',
            ),
            array(
                'query' => 'SELECT string FROM [nt:unstructured]',
            ),
            array(
                'query' => 'SELECT string, number FROM [nt:unstructured]',
            ),
            array(
                'query' => 'SELECT string, number, hello, goodbye FROM [nt:unstructured]',
            ),
        );
    }

    public function provideNbNodes()
    {
        return array(
            array(
                'nb_nodes' => 1,
                'sections' => 1,
            ),
            array(
                'nb_nodes' => 10,
                'sections' => 1,
            ),
            array(
                'nb_nodes' => 100,
                'sections' => 1,
            ),
        );
    }

    public function provideNbNodesWithSections()
    {
        return array(
            array(
                'nb_nodes' => 100,
                'sections' => 2,
            ),
            array(
                'nb_nodes' => 1000,
                'sections' => 2,
            ),
            array(
                'nb_nodes' => 100,
                'sections' => 4,
            ),
            array(
                'nb_nodes' => 1000,
                'sections' => 4,
            ),
        );
    }
}
