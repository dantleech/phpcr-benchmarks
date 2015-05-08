<?php

namespace PHPCR\Benchmark\Query;

use PhpBench\BenchIteration;
use PHPCR\Benchmark\BaseBench;

class InsertBench extends BaseBench
{
    /**
     * @description Insert nodes
     * @paramProvider provideNbNodes
     * @beforeMethod beforeResetWorkspace
     * @iterations 2
     */
    public function benchInsertNodes(BenchIteration $iteration)
    {
        $this->createNodes($this->getRootNode(), $iteration->getParameter('nb_nodes'), array(
            'string' => 'Hello',
            'number' => 10,
            'hello' => 'goodbye',
            'goodbye' => 'hello',
        ), $iteration->getParameter('sections'), 0);

        $this->getSession()->save();
    }

    public function beforeResetWorkspace()
    {
        $this->resetWorkspace();
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
            array(
                'nb_nodes' => 1000,
                'sections' => 1,
            ),
        );
    }
}

