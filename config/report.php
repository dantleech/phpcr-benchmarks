<?php

$runCols = array('iters', 'params', 'mean_time', 'mean_memory_diff', 'mean_rps', 'variance_time', 'deviation_min');
$subjectCols = array('iters', 'description', 'mean_time', 'mean_memory_diff', 'mean_rps', 'variance_time', 'deviation_min');

$config->addReport(array(
    'title' => 'Inserting Nodes',
    'description' => 'Insert nodes into the repository as children of a single parent',
    'name' => 'console_table',
    'groups' => array('insert'),
    'aggregate' => 'run',
    'cols' => $runCols,
    'sort' => array('time'),
));

$config->addReport(array(
    'title' => 'Query, select one property',
    'description' => <<<EOT
Execute a SELECT query and compare the difference between execution, iteration and iterating and retireving nodes:

SELECT someprop FROM [nt:unstructured]
EOT
    ,
    'name' => 'console_table',
    'groups' => array('query_single_prop'),
    'aggregate' => 'subject',
    'cols' => $subjectCols,
    'sort' => array('time'),
));

$config->addReport(array(
    'title' => 'Query, variable properties',
    'description' => <<<EOT
Execute a SELECT query with an increasing number of properties

SELECT [prop1, prop2, prop3, ...] FROM [nt:unstructured]
EOT
    ,
    'name' => 'console_table',
    'groups' => array('query_variable_props'),
    'aggregate' => 'run',
    'subject_meta' => false,
    'cols' => $runCols,
));

$config->addReport(array(
    'title' => 'Full tree traversal',
    'description' => <<<EOT
Traverse the entire tree for a medium sized website (~2300 nodes)
EOT
    ,
    'name' => 'console_table',
    'groups' => array('traversal_full'),
    'cols' => array('pid', 'iter', 'time', 'rps', 'memory_diff', 'deviation_mean'),
));
