<?php

namespace PHPCR\Benchmark;

use PHPCR\NodeInterface;

/**
 * @Groups({"traversal_full"})
 * @Iterations(6)
 */
class TraversalBench extends BaseBench
{
    public function setUp()
    {
        $this->loadDump('large_website.xml');
    }

    public function benchFullTraversal()
    {
        $this->traverse($this->getSession()->getRootNode());
    }

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
