<?php
namespace EssentialsPE\Commands\Economy;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Sell extends BaseCommand{
    /**
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "sell", "Sell the specified item", "/sell <item|hand> [amount]", false);
        $this->setPermission("essentials.sell");
    }

    /**
     * @param CommandSender $sender
     * @param string $alias
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage($this->getConsoleUsage());
            return false;
        }
        if($sender->getGamemode() === 1 || $sender->getGamemode() === 3){
            $sender->sendMessage(TextFormat::RED . "[Error] You're in " . $this->getPlugin()->getServer()->getGamemodeString($sender->getGamemode()) . " mode");
            return false;
        }
        if(strtolower($args[0]) === "hand"){
            $item = $sender->getInventory()->getItemInHand();
            if($item->getId() === 0){
                $sender->sendMessage(TextFormat::RED . "[Error] You don't have anything in your hand");
                return false;
            }
        }else{
            if(!is_int($args[0])){
                $item = Item::fromString($args[0]);
            }else{
                $item = Item::get($args[0]);
            }
            if($item->getId() === 0){
                $sender->sendMessage(TextFormat::RED . "[Error] Unknown item");
                return false;
            }
        }
        if(!$sender->getInventory()->contains($item)){
            $sender->sendMessage(TextFormat::RED . "[Error] You don't have that item in your inventory");
            return false;
        }
        if(isset($args[1]) && !is_int((int) $args[1])){
            $sender->sendMessage(TextFormat::RED . "[Error] Please specify a valid amount to sell");
            return false;
        }

        $amount = $this->getPlugin()->sellPlayerItem($sender, $item, (isset($args[1]) ? $args[1] : null));
        if(!$amount){
            $sender->sendMessage(TextFormat::RED . "[Error] Worth not available for this item");
            return false;
        }elseif($amount === -1){
            $sender->sendMessage(TextFormat::RED . "[Error] You don't have that amount of items");
            return false;
        }

        if(is_array($amount)){
            $sender->sendMessage(TextFormat::RED . "Sold " . $amount[0] . " items! You got" . $this->getPlugin()->getCurrencySymbol() . ($amount[1] * $amount[0]));
        }else{
            $sender->sendMessage(TextFormat::GREEN . "Item sold! You got " . $this->getPlugin()->getCurrencySymbol() . $amount);
        }
        return true;
    }
}