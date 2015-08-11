<?php

namespace PHPCR\Benchmark;

use PHPCR\NodeInterface;

class TraversalBench extends BaseBench
{
    public function setUp()
    {
        $this->loadDump('large_website.xml');
    }

    /**
     * @group traversal_full
     * @iterations 3
     */
    public function benchFullTraversal()
    {
        $this->traverse($this->getSession()->getRootNode());
    }

    /**
     * @group traversal_full
     * @iterations 3
     */
    public function benchFullTraversalReadProperties()
    {
        $this->traverse($this->getSession()->getRootNode(), true);
    }

    private function traverse(NodeInterface $node, $readProperties = false)
    {
        if ($readProperties) {
            foreach ($node->getProperties() as $property) {
                try {
                    $property->getValue();
                } catch (\PHPCR\RepositoryException $e) {
                }
            }
        }

        foreach ($node->getNodes() as $child) {
            $this->traverse($child, $readProperties);
        }
    }
}
