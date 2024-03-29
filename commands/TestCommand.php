<?php
namespace Commands;
use System\Core\Command;

class TestCommand extends Command {
    public function __construct()
    {
        parent::__construct();
    }
    protected $command = 'update:record';
    protected $command_description = 'update:record';
    protected $arguments = [
        'username'
    ];
    protected $options = [];

    public function handle()
    {
        $groups = [1,2,3,4,5];
        $progressBar = $this->createProgressBar(count($groups));
        echo $this->getArgument('username');
        $progressBar->start();
        foreach ($groups as $group)
        {
            sleep(2);
            $progressBar->advance();
        }
        $progressBar->finish();
        echo 'vo';
    }


}