<?php

namespace shop\LibsForm;

use shop\Main;
use pocketmine\{Server, Player};
use jojoe7777\FormAPI;
use pocketmine\item\Item;
use pocketmine\inventory\Inventory;

class UI
{
    public static $instance = null;

    public function __construct(Player $player){
        $this->openForm($player);
    }

    public static function getInstance(){
        return self::$instance;
    }

    public function getMain(): ?Main{
        $main = Server::getInstance()->getPluginManager()->getPlugin("Materials-PE");
        if($main instanceof Main){
            return $main;
        }
        return null;
    }

    public function openForm($player)
    {
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $f = $api->createSimpleForm(Function (Player $player, $data){
            $r = $data;
            if($r == null){
                $this->openForm($player);
            }
            switch($r){
                case 0:
                    break;
                case 1:
                    $this->shopItem($player);
                    break;
                case 2:
                    $this->updateFeature($player);
                    break;
            }
        });

        $f->setTitle("§l§f[§r§cMATERIALS'S §aSHOP§l§f]");
        $f->setContent("§c•§a Chào mừng bạn đến với cửa hàng vật liệu!\n Chuyên cung cấp các hàng sỉ & lẻ toàn quốc!");
        $f->addButton("Đéo có gì mua nên Bye xD", 0, "https://www.iconfinder.com/icons/964372/bye_farewell_goodbye_message_see_soon_you_icon");
        $f->addButton("§f[§aShop§c Item§f]", 1, "https://www.iconfinder.com/icons/4365246/buy_cart_retail_shop_icon");
        $f->addButton("§aWhat's new ", 2, "https://www.iconfinder.com/icons/170204/badge_new_icon");
        $f->sendToPlayer($player);
    }

    public function shopItem($player){
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $f = $api->createSimpleForm(Function (Player $player, $data){
            $r = $data;
            if($r == null){
                return $this->openForm($player);
            }
            switch($r)
            {
                case 0:
                    $this->openForm($player);
                    break;
                case 1:
                    $this->sandItem($player);
                    break;
            }
        });

        $f->setTitle("§l§f[§r§cMATERIALS'S §aSHOP§l§f]");
        $f->addButton("§l•§r §aBACK", 0);
        $f->addButton("Sand");
        $f->sendToPlayer($player);
    }

    public function sandItem($player){
        $a = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $f = $a->createCustomForm(Function (Player $player, $data){
                       
            if(!(is_numeric($data[1]))){
                $player->sendMessage("§l§f[§r§cMATERIALS'S §aSHOP§l§f] Only numbers!");
                return true;
            }
            $this->eco = Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI");
            $m = $this->eco->myMoney($player->getName());
            $cost = 300; # Default
            $inv = $player->getInventory();
            $nameItem = Item::get($data[1],0,$data[2])->getCustomName();
            if($m >= $cost){
                if($data[2] >= 30){
                    $this->eco->reduceMoney($player->getName(), ($cost*$data[2])/2);
                    $player->sendMessage("§l§aBạn đang được giảm giá khi mua với số lượng §b".$data[2]."§f(§c> 30§f), §aGốc:§b ".$cost*r[2]."§a, Giảm còn:§b ". ($cost*$data[2])/2);
                    $player->sendMessage("§l§f[§r§cMATERIALS'S §aSHOP§l§f] §aBạn đã mua §6".$nameItem." §aVới số lượng §b".$data[2]."§a Cái! - Sale 20%!");
                    $player->sendPopup("§a§l[BALANCE]§c Số Dư còn: §b". $m);
                    $inv->addItem(Item::get($data[1],0,$data[2]));
                }else{
                    $this->eco->reduceMoney($player->getName(), $cost*$data[2]);
                    $player->sendPopup("§a§l[BALANCE]§c Số Dư còn: §b". $m);
                    $player->sendMessage("§l§f[§r§cMATERIALS'S §aSHOP§l§f] §aBạn đã mua §6".$nameItem." §aVới số lượng §b".$data[2]."§a Cái! - Total:§b " . ($cost*$data[2])/2);
                    $inv->addItem(Item::get($data[1],0,$data[2]));
                }
                $this->getMain()->setMaterial($player, mt_rand(1,10));
                $this->getMain()->data->set([
                    "Item" => $nameItem,
                    "Amount" => $data[2],
                ]);
                $this->getMain()->data->save();
            }else{
                $player->sendMessage("Bạn Không đủ tiền!");
            }
        });
        //$nameItem = Item::get($data[1],0,$data[2])->getCustomName();
        $f->setTitle("§l§f[§r§cMATERIALS'S §aSHOP§l§f]");
        $f->addLabel("All $5000/Item");
        $f->addInput("Write ID's Item: (Search on Meta Item, VD: 12)");
        $f->addSlider("Amount", 1, 100);
        $f->addLabel("==============\n§l§c• §aSale 20% nếu bạn mua trên 30!");
        $f->sendToPlayer($player);
    }
}