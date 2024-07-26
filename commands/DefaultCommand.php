<?php
namespace Commands;
use Hola\Core\Command;
class DefaultCommand extends Command {
    public function __construct()
    {
        parent::__construct();
    }
    protected $command = "default";
    protected $command_description = "A command default description";
    protected $arguments = [];
    protected $options = [];

    public function handle()
    {
        // code here
    }
}