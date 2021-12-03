<?php

namespace shop\Commands;

use shop\Main;
use shop\LibsForm\UI;
use pocketmine\command\{Command, CommandSender};
use pocketmine\{Player, Server};
use pocketmine\plugin\Plugin;

class Material extends Command
{
    public $plugin;

    private static $instance = null;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
        //$this->player = $player;
        parent::__construct("muavatlieu");
        $this->setDescription("Hệ Thống Vật Liệu");
    }

    public static function getInstance(){
        return self::$instance;
    }

    public function getPlugin(): Main{
        return $this->plugin;
    }

    /**public function getPlayer(){
        return $this->player;
    }*/

    public function execute(CommandSender $player, string $label, array $args){
        if(!($player instanceof Player)){
            Server::getServer()->getLogger()->warning($this->getPlugin()->tag . "USE IN-GAME!");
            return true;
        }
        new UI($player);
        return;
    }
}