<?php

namespace PHPCR\Benchmark;

use PhpBench\Benchmark;
use PhpBench\Benchmark\Iteration;
use PHPCR\Benchmark\BaseBench;

/**
 * @Groups({"insert"})
 * @BeforeMethods({"setUp"})
 */
class InsertBench extends BaseBench
{
    private $index = 1;
    private $dataSet;

    public function setUp($params)
    {
        $dataSet = array(
            'sprynesss' => 'film',
            'CompuServes' => '1',
            'breeds' => '6',
            'gigabytes' => 'biases',
            'tactlessnesss' => '{  }',
            'levitates' => '1',
            'fraction' => 'false',
            'cradles' => 'false',
            'hashing' => '1',
            'bludgeoned' => 'equinoctial',
            'humblenesss' => 'scratchier',
            'loftiest' => '[Bernbachs]',
            'transpiring' => 'toothpastes',
            'opines' => 'null',
            'groveling' => 'kicks',
            'irresponsibilitys' => 'null',
            'guitar' => 'smudgier',
            'trebles' => 'neurosurgery',
            'relentlessness' => 'false',
            'Rooneys' => 'scratchier',
            'marksmans' => 'ineligibles',
            'starkness' => 'film',
            'components' => 'malarias',
            'subconsciouss' => 'kicks',
            'Aureomycin' => 'smudgier',
            'suffering' => 'ineligibles',
            'overdoses' => '1',
            'reinforcements' => 'null',
            'hundredweights' => '6',
            'ciphers' => '{  }',
            'widowers' => 'Magellan',
            'cares' => 'null',
            'transits' => 'null',
            'calliope' => 'false',
            'dwarfism' => '6',
            'fingering' => 'fulfilment',
            'virago' => 'null',
            'chiropodists' => 'null',
            'misdeeds' => 'Magellan',
            'Marius' => '{  }',
            'heisted' => 'grand',
            'insomniacs' => '{  }',
            'retreads' => '2',
            'driver' => 'film',
            'chainsaw' => 'kicks',
            'staffings' => 'toothpastes',
            'frenzys' => 'patriot',
            'thickens' => 'toothpastes',
            'comparison' => 'Monk',
            'heckles' => 'false',
            'nerd' => 'false',
            'geodes' => 'malarias',
            'abundantly' => 'equinoctial',
            'Earths' => 'jitterbugging',
            'coda' => 'grand',
            'Brenton' => '{  }',
            'demolished' => '10',
            'Bonnies' => 'toothpastes',
            'fighter' => 'Magellan',
            'marketer' => 'false',
            'restated' => 'toothpastes',
            'unworldly' => 'new',
            'trestles' => '6',
            'lama' => 'Dresdens',
            'spur' => 'briefing',
            'Helsinkis' => '{  }',
            'excesses' => 'malarias',
            'spendings' => 'false',
            'garnet' => 'jowls slumlords atriums Re medley chattels unshakeable incivility authorizing',
        );

        $this->dataSet = array_splice($dataSet, 0, $params['nb_props']);
    }

    /**
     * @ParamProviders({"provideNbNodes", "provideNbProps"})
     * @BeforeMethods({"beforeResetWorkspace", "setUp"})
     */
    public function benchInsertNodes($params)
    {
        $this->createNodes($this->getRootNode(), $params['nb_nodes'], $this->dataSet, $this->index * $params['nb_nodes']);
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
                'nb_nodes' => 8,
            ),
            array(
                'nb_nodes' => 16,
            ),
        );
    }

    public function provideNbProps()
    {
        return array(
            array(
                'nb_props' => 1,
            ),
            array(
                'nb_props' => 8,
            ),
            array(
                'nb_props' => 16,
            ),
        );
    }
}

