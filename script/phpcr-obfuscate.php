<?php

// Script to obfuscate PHPCR dumps.
//
// Translates all words (including node names and proprety names) into random words
// from the system dictionary.
//
// Dates and UUIDs retain their original values.
// 
// NOTE: Will not work on Windows. Depends on a dict package.
//
// Usage:
//
//     php phpcr-obfuscate.php some_session_dump.xml > outfile.xml

$dictPath = '/usr/share/dict/american-english';
$verbose = false;

function blowup($message)
{
    echo $message . PHP_EOL;
    exit(1);
}

function obfuscate($string)
{
    global $knownNames;

    $prefix = '';
    if (substr($string, 0, 3) == 'jcr') {
        return $string;
    }

    $words = explode(' ', $string);
    $string = array();
    foreach ($words as $word) {
        if (isset($knownNames[$word])) {
            return $knownNames[$word];
        }

        $knownNames[$word] = randomName();
        $string[] = $knownNames[$word];
    }

    return $prefix . implode(' ', $string);
}

function randomName()
{
    global $dict, $dictSize;

    $name = $dict[rand(0, $dictSize)];
    $name = str_replace("'", '', $name);

    return $name;
}

global $dict, $dictSize, $knownNames;

if (!isset($argv[1])) {
    blowup('You must provide the path to a PHPCR session export');
}

$file = $argv[1];

if (!file_exists($file)) {
    blowup(sprintf('File "%s" does not exist', $file));
}

if (!file_exists($dictPath)) {
    blowup(sprintf('Could not find dictionary file "%s"', $dict));
}

if ($verbose) { echo "Importing dictionary" . PHP_EOL; }
$dict = explode(PHP_EOL, file_get_contents($dictPath));
$dictSize = count($dict) - 1;
$knownNames = array();

$dom = new \DOMDocument('1.0');
$dom->loadXml(file_get_contents($file));
$dom->formatOutput = true;
$xpath = new \DOMXpath($dom);

if ($verbose) { echo "Replacing property names" . PHP_EOL; }
foreach ($xpath->query('//sv:property') as $propertyEl) {
    $name = obfuscate($propertyEl->getAttribute('sv:name'));
    $propertyEl->setAttribute('sv:name', $name);
}

if ($verbose) { echo "Replacing node names" . PHP_EOL; }
foreach ($xpath->query('//sv:node') as $nodeEl) {
    $name = obfuscate($nodeEl->getAttribute('sv:name'));
    $nodeEl->setAttribute('sv:name', $name);
}

if ($verbose) { echo "Replacing string values" . PHP_EOL; }
foreach ($xpath->query('//sv:property[@sv:type="String" and not(starts-with(@sv:name, "jcr:"))]/sv:value') as $valueEl) {
    $name = obfuscate($valueEl->nodeValue);
    $valueEl->nodeValue = $name;
}

echo $dom->saveXml();
