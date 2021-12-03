<?php

namespace shop;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\{Player, Server};
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerJoinEvent;
use shop\Commands\Material;

class Main extends PluginBase implements Listener
{
    public $data;

    public $tag = "§f[§aMaterial§f]";

    public function onEnable(): void{
        $this->getServer()->getCommandMap()->register("muavatlieu", new Material($this));
        $this->getServer()->getLogger()->info($this->tag . "§a Started system Material");
        //$this->data = yaml_parse(file_get_contents($this->gettDataFolder(), "Data.yml"));
        if(!is_dir($this->getDataFolder())){
            @mkdir($this->getDataFolder());
            @mkdir($this->getDataFolder(), "Players/");
            @mkdir($this->getDataFolder(), "Materials/");
        }
    }

    public function onJoin(PlayerJoinEvent $ev){
        $player = $ev->getPlayer();
        $name = $player->getName();

        if(!file_exists($this->getDataFolder() . $name.".yml", Config::YAML)){
            $this->data = new config($this->getDataFolder() . $name.".yml", Config::YAML);
            $this->data->set([
                "Item" => false,
                "Amount" => 0
            ]);
            $this->data->save();
            $this->getServer()->getLogger()->notice("Created player.yml!");
        }

        if(!file_exists($this->getDataFolder() . "Materials/".$name.".yml", Config::YAML)){
            $this->mt = new Config($this->getDataFolder() . "Materials/".$name.".yml", Config::YAML);
            $this->getServer()->getLogger()->notice("Created Materials.yml!");
            $this->createMaterial($player);
        }
    }

    public function createMaterial(Player $player){
        $this->mt->set("Materials", 0);
        $this->mt->save();
    }

    public function setMaterial(Player $player, int $material){
        $psmt = $this->mt->get("Materials");
        $this->mt->set("Materials", $psmt + $material);
        $this->mt->save();
    }

    /**public function chanceMaterial($player){
        $this->mt("Materials", $material);
        $this->mt->save();
    }*/

    public function checkMaterial(Player $player){
        if(!file_exists($this->getDataFolder() . "Materials/".$player->getName(). ".yml", Config::YAML)){
            return true;
        }
        return false;
    }

    public function getMaterial(Player $player){
        if($this->checkMaterial($player)){
            $psmt = $this->mt->get("Materials");
            return $psmt;
        }
        return false;
    }

}