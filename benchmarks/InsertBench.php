<?php

namespace PHPCR\Benchmark;

use PhpBench\Benchmark;
use PhpBench\Benchmark\Iteration;
use PHPCR\Benchmark\BaseBench;

/**
 * @group insert
 * @processIsolation iteration
 */
class InsertBench extends BaseBench
{
    /**
     * @description Insert nodes
     * @paramProvider provideNbNodes
     * @beforeMethod beforeResetWorkspace
     * @iterations 2
     */
    public function benchInsertNodes(Iteration $iteration)
    {
        $this->createNodes($this->getRootNode(), $iteration->getParameter('nb_nodes'), array(
            'string' => 'Hello',
            'number' => 10,
            'hello' => 'goodbye',
            'goodbye' => 'hello',
        ), 0);

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
            ),
            array(
                'nb_nodes' => 10,
            ),
            array(
                'nb_nodes' => 100,
            ),
            array(
                'nb_nodes' => 1000,
            ),
        );
    }
}

