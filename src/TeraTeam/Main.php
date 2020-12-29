<?php

namespace TeraTeam;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{
    public function onEnable()
    {
        $this->getLogger()->info("JoinTp on by TeraTeam");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        @mkdir($this->getDataFolder());
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        if(!file_exists($this->getDataFolder()."data.yml")){
            $config = new Config($this->getDataFolder()."data.yml", Config::YAML);
        }
    }

    public function onDisable()
    {
        $this->getLogger()->info("JoinTp off by TeraTeam");
    }

    public function onCommand(CommandSender $player, Command $command, string $label, array $args): bool
    {
        if ($player instanceof Player){
            switch ($command->getName()){
                case "setjoin":
                    $config = new Config($this->getDataFolder()."data.yml", Config::YAML);
                    $config->set('join', [$player->getX(), $player->getY(), $player->getZ(), $player->getLevel()->getName()]);
                    $config->save();
                    $player->sendMessage("La position de quand un joueur va rejoindre le serveur avec la permission§c join.tp§f a bien été définie !");
                    break;
            }
        }
        return true;
    }

    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();

        if (!$player->hasPlayedBefore()){
            $config = new Config($this->getDataFolder()."data.yml", Config::YAML);
            if ($player->hasPermission("join.tp")){
                if($config->get("minage") !== null){
                    $x = $config->get("join")[0];
                    $y = $config->get("join")[1];
                    $z = $config->get("join")[2];
                    $monde = $config->get("join")[3];
                    $player->teleport(new Position($x, $y, $z, $this->getServer()->getLevelByName($monde)));
                }
            }
        }else{
            $config = new Config($this->getDataFolder()."data.yml", Config::YAML);
            if ($player->hasPermission("join.tp")){
                if($config->get("minage") !== null){
                    $x = $config->get("join")[0];
                    $y = $config->get("join")[1];
                    $z = $config->get("join")[2];
                    $monde = $config->get("join")[3];
                    $player->teleport(new Position($x, $y, $z, $this->getServer()->getLevelByName($monde)));
                }
            }
        }
    }
}