<?php

namespace PHPCR\Benchmark;

use PhpBench\BenchCase;
use PHPCR\NodeInterface;

abstract class BaseBench implements BenchCase
{
    const ROOT_NAME = 'bench';
    const ROOT_PATH = '/bench';

    private $session;
    private $loader;
    private $rootNode;

    protected function getSession()
    {
        if ($this->session) {
            return $this->session;
        }

        $this->session = $this->getLoader()->getSession();

        return $this->session;
    }

    protected function resetSession()
    {
        $this->getSession()->logout();
        $this->session = $this->getLoader()->getSession();
    }

    protected function resetWorkspace()
    {
        $rootNode = $this->getSession()->getRootNode();

        if ($rootNode->hasNode(self::ROOT_NAME)) {
            $this->getSession()->removeItem(self::ROOT_PATH);
        }

        $this->rootNode = $rootNode->addNode(self::ROOT_NAME);
        $this->getSession()->save();
        $this->getSession()->refresh(false);
    }

    protected function getLoader()
    {
        if ($this->loader) {
            return $this->loader;
        }

        $this->loader = \ImplementationLoader::getInstance();

        return $this->loader;
    }

    protected function loadFixtures($identifier)
    {
        $this->getLoader()->getFixtureLoader()->import($identifier);
    }

    protected function getQueryManager()
    {
        return $this->getWorkspace()->getQueryManager();
    }

    protected function getWorkspace()
    {
        return $this->getSession()->getWorkspace();
    }

    protected function createNodes(NodeInterface $parentNode, $number, $properties = array(), $sections = 1, $offset = 0)
    {
        $number = $number + $offset;
        for ($section = 0; $section < $sections; $section++) {
            if (!$parentNode->hasNode('section-' . $section)) {
                $sectionNode = $parentNode->addNode('section-' . $section);
            } else {
                $sectionNode = $parentNode->getNode('section-' . $section);
            }
            for ($i = $offset; $i < $number; $i++) {
                $node = $sectionNode->addNode('node-' . $i);
                foreach ($properties as $property => $value) {
                    $node->setProperty($property, $value);
                }
            }
        }
    }

    protected function getRootNode()
    {
        return $this->rootNode;
    }
}
