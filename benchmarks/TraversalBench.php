<?php

namespace PHPCR\Benchmark;

use PHPCR\NodeInterface;

/**
 * @Groups({"traversal"})
 * @BeforeClassMethods({"beforeClass"})
 */
class TraversalBench extends BaseBench
{
    public static function beforeClass()
    {
        (new self)->loadDump('small_website.xml');
    }

    public function benchFullTraversal()
    {
        $this->traverse($this->getSession()->getRootNode()->getNode('small_website.xml'));
    }

    public function benchFullTraversalReadProperties()
    {
        $this->traverse($this->getSession()->getRootNode()->getNode('small_website.xml'), true);
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
