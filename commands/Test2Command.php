<?php
namespace Commands;
use System\Core\Command;

class Test2Command extends Command {
    public function __construct()
    {
        parent::__construct();
    }
    protected $command = 'update:record2';
    protected $command_description = 'update:record2';
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