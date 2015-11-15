<?php

namespace PHPCR\Benchmark;

use PHPCR\NodeInterface;
use PhpBench\BenchmarkInterface;
use PHPCR\ImportUUIDBehaviorInterface;

abstract class BaseBench
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
        $this->getNodeTypeManager()->registerNodeTypesCnd(file_get_contents(__DIR__ . '/../dumps/nodetypes.cnd'), true);

        return $this->session;
    }

    protected function getNodeTypeManager()
    {
        return $this->getWorkspace()->getNodeTypeManager();
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

    protected function getQueryManager()
    {
        return $this->getWorkspace()->getQueryManager();
    }

    protected function getWorkspace()
    {
        return $this->getSession()->getWorkspace();
    }

    protected function createNodes(NodeInterface $parentNode, $number, $properties = array(), $offset = 0)
    {
        $number = $number + $offset;

        for ($i = $offset; $i < $number; $i++) {
            $node = $parentNode->addNode('node-' . $i);
            foreach ($properties as $property => $value) {
                $node->setProperty($property, $value);
            }
        }
    }

    protected function getRootNode()
    {
        return $this->rootNode;
    }

    protected function loadDump($filename, $remove = false)
    {
        $dumpPath = __DIR__ . '/../dumps/' . $filename;

        if (!file_exists($dumpPath)) {
            throw new \Exception('Could not find dump file: ' . $dumpPath);
        }

        $rootNode = $this->getSession()->getRootNode();

        if (false === $remove && $rootNode->hasNode($filename)) {
            return;
        }

        if (true === $rootNode->hasNode($filename)) {
            $rootNode->remove($filename);
        }

        $rootNode->addNode($filename, 'nt:unstructured');
        $this->getSession()->importXML('/' . $filename, $dumpPath, ImportUUIDBehaviorInterface::IMPORT_UUID_COLLISION_THROW);
        $this->getSession()->save();
    }
}
