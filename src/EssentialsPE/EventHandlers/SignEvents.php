<?php

namespace EssentialsPE\EventHandlers;

use EssentialsPE\BaseFiles\BaseEventHandler;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;
use pocketmine\tile\Sign;
use pocketmine\utils\TextFormat;

class SignEvents extends BaseEventHandler{
    /**
     * @param PlayerInteractEvent $event
     */
    public function onSignTap(PlayerInteractEvent $event){
        $tile = $event->getBlock()->getLevel()->getTile(new Vector3($event->getBlock()->getFloorX(), $event->getBlock()->getFloorY(), $event->getBlock()->getFloorZ()));
        if($tile instanceof Sign){
            // Free sign
            if(TextFormat::clean($tile->getText()[0], true) === "[Free]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("essentials.sign.use.free")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
               }else{
		    $event->getPlayer()->sendMessage(TextFormat::RED . "Free signs are prohibited on this server");
		    /*
                    if($event->getPlayer()->getGamemode() === 1 || $event->getPlayer()->getGamemode() === 3){
                        $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] You're in " . $event->getPlayer()->getServer()->getGamemodeString($event->getPlayer()->getGamemode()) . " mode");
                        return;
                    }

                    $item_name = $tile->getText()[1];
                    $damage = $tile->getText()[2];

                    $item = $this->getPlugin()->getItem($item_name . ":" . $damage);

                    $event->getPlayer()->getInventory()->addItem($item);
                    $event->getPlayer()->sendMessage(TextFormat::YELLOW . "Giving " . TextFormat::RED . $item->getCount() . TextFormat::YELLOW . " of " . TextFormat::RED . ($item->getName() === "Unknown" ? $item_name : $item->getName()));
                    */
                }
            }

            // Gamemode sign
            // TODO Implement costs
            elseif(TextFormat::clean($tile->getText()[0], true) === "[Gamemode]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("essentials.sign.use.gamemode")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
               }else{
		    $event->getPlayer()->sendMessage(TextFormat::RED . "Gamempde signs are prohibited on this server");
		    /*
                    $v = strtolower($tile->getText()[1]);
                    if($v === "survival"){
                        $event->getPlayer()->setGamemode(0);
                    }elseif($v === "creative"){
                        $event->getPlayer()->setGamemode(1);
                    }elseif($v === "adventure"){
                        $event->getPlayer()->setGamemode(2);
                    }elseif($v === "spectator"){
                        $event->getPlayer()->setGamemode(3);
                    }
                    */
                }
            }

            // Heal sign
            // TODO Implement costs
            elseif(TextFormat::clean($tile->getText()[0], true) === "[Heal]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("essentials.sign.use.heal")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
                }elseif($event->getPlayer()->getGamemode() === 1 || $event->getPlayer()->getGamemode() === 3){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] You're in " . $event->getPlayer()->getServer()->getGamemodeString($event->getPlayer()->getGamemode()) . " mode");
                    return;
               }else{
		    $event->getPlayer()->sendMessage(TextFormat::RED . "Heal signs are prohibited on this server");
		    /*
                    $event->getPlayer()->heal($event->getPlayer()->getMaxHealth(), new EntityRegainHealthEvent($event->getPlayer(), $event->getPlayer()->getMaxHealth(), EntityRegainHealthEvent::CAUSE_CUSTOM));
                    $event->getPlayer()->sendMessage(TextFormat::GREEN . "You have been healed!");
                    */
                }
            }
            
            // Kit sign
            // TODO: Implement costs
            elseif(TextFormat::clean($tile->getText()[0], true) === "[Kit]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("essentials.sign.use.kit")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
                }elseif($event->getPlayer()->getGamemode() === 1 || $event->getPlayer()->getGamemode() === 3){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] You're in " . $event->getPlayer()->getServer()->getGamemodeString($event->getPlayer()->getGamemode()) . " mode");
                    return;
                }else{
                    if(!($kit = $this->getPlugin()->getKit($tile->getText()[1]))){
                        $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] Kit doesn't exists");
                        return;
                    }elseif(!$event->getPlayer()->hasPermission("essentials.kits." . $kit->getName())){
                        $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] You don't have permissions to get this kit");
                        return;
                    }else{
			$event->getPlayer()->sendMessage(TextFormat::RED . "Kit signs are prohibited on this server");
			/*
                        $kit->giveToPlayer($event->getPlayer());
                        $event->getPlayer()->sendMessage(TextFormat::GREEN . "Getting kit " . TextFormat::AQUA . $kit->getName() . "...");
                        */
                    }
                }
            }

            // Repair sign
            // TODO Implement costs
            elseif(TextFormat::clean($tile->getText()[0], true) === "[Repair]"){
                $event->setCancelled(true);
                $event->getPlayer()->sendMessage(TextFormat::RED . "Repair signs are prohibited on this server");
                /*
                if(!$event->getPlayer()->hasPermission("essentials.sign.use.repair")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
                }elseif($event->getPlayer()->getGamemode() === 1 || $event->getPlayer()->getGamemode() === 3){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] You're in " . $event->getPlayer()->getServer()->getGamemodeString($event->getPlayer()->getGamemode()) . " mode");
                    return;
               }else{
                    if(($v = $tile->getText()[1]) === "Hand"){
                        if($this->getPlugin()->isReparable($item = $event->getPlayer()->getInventory()->getItemInHand())){
                            $item->setDamage(0);
                            $event->getPlayer()->sendMessage(TextFormat::GREEN . "Item successfully repaired!");
                        }
                    }elseif($v === "All"){
                        foreach ($event->getPlayer()->getInventory()->getContents() as $item){
                            if($this->getPlugin()->isReparable($item)){
                                $item->setDamage(0);
                            }
                        }
                        foreach ($event->getPlayer()->getInventory()->getArmorContents() as $item){
                            if($this->getPlugin()->isReparable($item)){
                                $item->setDamage(0);
                            }
                        }
                        $event->getPlayer()->sendMessage(TextFormat::GREEN . "All the tools on your inventory were repaired!" . TextFormat::AQUA . "\n(including the equipped Armor)");
                    }
                }
                */
            }

            // Time sign
            // TODO Implement costs
            elseif(TextFormat::clean($tile->getText()[0], true) === "[Time]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("essentials.sign.use.time")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
               }else{
                    if(($v = $tile->getText()[1]) === "Day"){
                        $event->getPlayer()->getLevel()->setTime(0);
                        $event->getPlayer()->sendMessage(TextFormat::GREEN . "Time set to \"Day\"");
                    }elseif($v === "Night"){
                        $event->getPlayer()->getLevel()->setTime(12500);
                        $event->getPlayer()->sendMessage(TextFormat::GREEN . "Time set to \"Night\"");
                    }
                }
            }

            // Teleport sign
            // TODO Implement costs
            elseif(TextFormat::clean($tile->getText()[0], true) === "[Teleport]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("essentials.sign.use.teleport")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
               }else{
                    $event->getPlayer()->teleport(new Vector3($x = $tile->getText()[1], $y = $tile->getText()[2], $z = $tile->getText()[3]));
                    $event->getPlayer()->sendMessage(TextFormat::GREEN . "Teleporting to " . TextFormat::AQUA . $x . TextFormat::GREEN . ", " . TextFormat::AQUA . $y . TextFormat::GREEN . ", " . TextFormat::AQUA . $z);
                }
            }

            // Warp sign
            // TODO Implement costs
            elseif(TextFormat::clean($tile->getText()[0], true) === "[Warp]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("essentials.sign.use.warp")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
               }else{
                    $warp = $this->getPlugin()->getWarp($tile->getText()[1]);
                    if(!$warp){
						$wattz_plugin = $this->getPlugin()->getServer()->getPluginManager()->getPlugin("Wattz");
						if( is_null($wattz_plugin) ) {
							$event->getPlayer()->sendMessage(TextFormat::RED . "[Error] Warp doesn't exist");
							return false;
						}
						$wattz_plugin->redirect($event->getPlayer(), $tile->getText()[1]);
                        return;
                    }
                    if(!$event->getPlayer()->hasPermission("essentials.warps.*") && !$event->getPlayer()->hasPermission("essentials.warps." . $tile->getText()[1])){
                        $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] You can't teleport to that warp");
                        return;
                    }
                    $event->getPlayer()->teleport($warp);
                    $event->getPlayer()->sendMessage(TextFormat::GREEN . "Warping to " . $tile->getText()[1] . " hold onto your seats...");
                }
            }

            /**
             * Economy signs
             */

            // Balance sign
            /**elseif(TextFormat::clean($tile->getText()[0) === "[Balance]"){
             * $event->setCancelled(true);
             * if(!$event->getPlayer()->hasPermission("essentials.sign.use.balance")){
             * $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
             * }else{
             * $event->getPlayer()->sendMessage(TextFormat::AQUA . "Your current balance is " . TextFormat::YELLOW . $this->getPlugin()->getCurrencySymbol() . $this->getPlugin()->getPlayerBalance($event->getPlayer()));
             * }
             * }*/

            /**
             * TODO Implement:
             * - Buy sign
             * - Sell sign
             */
        }
    }

    /**
     * @param BlockBreakEvent $event
     *
     * @priority HIGH
     */
    public function onBlockBreak(BlockBreakEvent $event){
        $tile = $event->getBlock()->getLevel()->getTile(new Vector3($event->getBlock()->getFloorX(), $event->getBlock()->getFloorY(), $event->getBlock()->getFloorZ()));
        if($tile instanceof Sign){
            $key = ["Free", "Gamemode", "Heal", "Kit", "Repair", "Time", "Teleport", "Warp"];
            foreach($key as $k){
                if(TextFormat::clean($tile->getText()[0], true) === "[" . $k . "]" && !$event->getPlayer()->hasPermission("essentials.sign.break." . strtolower($k))){
                    $event->setCancelled(true);
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to break this sign");
                    break;
                }
            }
        }
    }

    /**
     * @param SignChangeEvent $event
     */
    public function onSignChange(SignChangeEvent $event){
        // Special Signs
        // Free sign
        if(strtolower(TextFormat::clean($event->getLine(0), true)) === "[free]" && $event->getPlayer()->hasPermission("essentials.sign.create.free")){
            if(trim($event->getLine(1)) !== "" || $event->getLine(1) !== null){
                $item_name = $event->getLine(1);

                if(trim($event->getLine(2)) !== "" || $event->getLine(2) !== null){
                    $damage = $event->getLine(2);
                }else{
                    $damage = 0;
                }

                $item = $this->getPlugin()->getItem($item_name . ":" . $damage);

                if($item->getID() === 0 || $item->getName() === "Air"){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] Invalid item name/ID");
                    $event->setCancelled(true);
                }else{
                    $event->getPlayer()->sendMessage(TextFormat::GREEN . "Free sign successfully created!");
                    $event->setLine(0, TextFormat::AQUA . "[Free]");
                    $event->setLine(1, ($item->getName() === "Unknown" ? $item->getID() : $item->getName()));
                    $event->setLine(2, $damage);
                }
            }else{
                $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] You should provide an item name/ID");
                $event->setCancelled(true);
            }
        }

        // Gamemode sign
        elseif(strtolower(TextFormat::clean($event->getLine(0), true)) === "[gamemode]" && $event->getPlayer()->hasPermission("essentials.sign.create.gamemode")){
            switch(strtolower($event->getLine(1))){
                case "survival":
                case "0":
                    $event->setLine(1, "Survival");
                    break;
                case "creative":
                case "1":
                    $event->setLine(1, "Creative");
                    break;
                case "adventure":
                case "2":
                    $event->setLine(1, "Adventure");
                    break;
                case "spectator":
                case "view":
                case "3":
                    $event->setLine(1, "Spectator");
                    break;
                default:
                    $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] Unknown Gamemode, you should use \"Survival\", \"Creative\", \"Adventure\" or \"Spectator\"");
                    $event->setCancelled(true);
                    return;
                    break;
            }
            $event->getPlayer()->sendMessage(TextFormat::GREEN . "Gamemode sign successfully created!");
            $event->setLine(0, TextFormat::AQUA . "[Gamemode]");
        }

        // Heal sign
        elseif(strtolower(TextFormat::clean($event->getLine(0), true)) === "[heal]" && $event->getPlayer()->hasPermission("essentials.sign.create.heal")){
            $event->getPlayer()->sendMessage(TextFormat::GREEN . "Heal sign successfully created!");
            $event->setLine(0, TextFormat::AQUA . "[Heal]");
        }

        // Kit sign
        elseif(strtolower(TextFormat::clean($event->getLine(0), true)) === "[kit]" && $event->getPlayer()->hasPermission("essentials.sign.create.kit")){
            if(!$this->getPlugin()->kitExists($event->getLine(1))){
                $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] Kit doesn't exist");
                return;
            }
            $event->getPlayer()->sendMessage(TextFormat::GREEN . "Kit sign successfully created!");
            $event->setLine(0, TextFormat::AQUA . "[Kit]");
        }

        // Repair sign
        elseif(strtolower(TextFormat::clean($event->getLine(0), true)) === "[repair]" && $event->getPlayer()->hasPermission("essentials.sign.create.repair")){
            switch(strtolower($event->getLine(1))){
                case "hand":
                    $event->setLine(1, "Hand");
                    break;
                case "all":
                    $event->setLine(1, "All");
                    break;
                default:
                    $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] Invalid argument, you should use \"Hand\" or \"All\"");
                    $event->setCancelled(true);
                    return;
                    break;
            }
            $event->getPlayer()->sendMessage(TextFormat::GREEN . "Repair sign successfully created!");
            $event->setLine(0, TextFormat::AQUA . "[Repair]");
        }

        // Time sign
        elseif(strtolower(TextFormat::clean($event->getLine(0), true)) === "[time]" && $event->getPlayer()->hasPermission("essentials.sign.create.time")){
            switch(strtolower($event->getLine(1))){
                case "day":
                    $event->setLine(1, "Day");
                    break;
                case "night";
                    $event->setLine(1, "Night");
                    break;
                default:
                    $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] Invalid time, you should use \"Day\" or \"Night\"");
                    $event->setCancelled(true);
                    return;
                    break;
            }
            $event->getPlayer()->sendMessage(TextFormat::GREEN . "Time sign successfully created!");
            $event->setLine(0, TextFormat::AQUA . "[Time]");
        }

        // Teleport sign
        elseif(strtolower(TextFormat::clean($event->getLine(0), true)) === "[teleport]" && $event->getPlayer()->hasPermission("essentials.sign.create.teleport")){
            if(!is_numeric($event->getLine(1))){
                $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] Invalid X position, Teleport sign will not work");
                $event->setCancelled(true);
            }elseif(!is_numeric($event->getLine(2))){
                $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] Invalid Y position, Teleport sign will not work");
                $event->setCancelled(true);
            }elseif(!is_numeric($event->getLine(3))){
                $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] Invalid Z position, Teleport sign will not work");
                $event->setCancelled(true);
            }else{
                $event->getPlayer()->sendMessage(TextFormat::GREEN . "Teleport sign successfully created!");
                $event->setLine(0, TextFormat::AQUA . "[Teleport]");
                $event->setLine(1, $event->getLine(1));
                $event->setLine(2, $event->getLine(2));
                $event->setLine(3, $event->getLine(3));
            }
        }

        // Warp sign
        elseif(strtolower(TextFormat::clean($event->getLine(0), true)) === "[warp]" && $event->getPlayer()->hasPermission("essentials.sign.create.warp")){
            $warp = $event->getLine(1);
            if(!$this->getPlugin()->warpExists($warp)){
                $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] Warp doesn't exists");
                $event->setCancelled(true);
            }else{
                $event->getPlayer()->sendMessage(TextFormat::GREEN . "Warp sign successfully created!");
                $event->setLine(0, TextFormat::AQUA . "[Warp]");
            }
        }

        // Colored Sign
        elseif($event->getPlayer()->hasPermission("essentials.sign.color")){
            for($i = 0 ; $i < 4 ; $i++){
                $event->setLine($i, $this->getPlugin()->colorMessage($event->getLine($i)));
            }
        }
    }
}