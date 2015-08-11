<?php

namespace PHPCR\Benchmark;

use PhpBench\Benchmark;
use PhpBench\Benchmark\Iteration;
use PHPCR\Benchmark\BaseBench;

/**
 * @group insert
 * @iterations 4
 * @revs 4
 */
class InsertBench extends BaseBench
{
    private $index = 1;

    /**
     * @paramProvider provideNbNodes
     * @beforeMethod beforeResetWorkspace
     */
    public function benchInsertNodes($params)
    {
        $this->createNodes($this->getRootNode(), $params['nb_nodes'], array(
            'string' => 'Hello',
            'number' => 10,
            'hello' => 'goodbye',
            'goodbye' => 'hello',
        ), $this->index * $params['nb_nodes']);
        $this->index++;

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

