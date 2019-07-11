<?php
use function Deployer\{askConfirmation, askChoice, get, set, writeln, input};

set('branch', function () {
    // --branch=<branch> でオプション指定されてるときはそれで
    if (input()->getOption('branch')) {
        return input()->getOption('branch');
    }

    $repository = get('repository');
    exec("git ls-remote -h $repository", $outputLines);
    $branches = [];
    foreach ($outputLines as $line) {
        if (preg_match('#refs/heads/(.*)$#', $line, $matches)) {
            $branches[] = $matches[1];
        }
    }

    $branch = askChoice("Choose a branch to deploy:", $branches);
    writeln("> Branch chosen: {$branch}");
    if (!askConfirmation("Are you sure to work with '{$branch}' ?")) {
        exit();
    }

    return $branch;
});
