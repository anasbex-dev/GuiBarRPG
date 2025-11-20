<?php

namespace GuiBarRPG;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use GuiBarRPG\Tasks\UpdateBarTask;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class Main extends PluginBase implements Listener {

    /** @var PlayerStatus[] */
    private array $playersStatus = [];

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        // Schedule update bar tiap 1 detik
        $this->getScheduler()->scheduleRepeatingTask(new UpdateBarTask($this), 20);

        $this->getLogger()->info("GuiBarRPG Enabled (API 5)!");
    }

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $this->playersStatus[$player->getName()] = new PlayerStatus($player);
    }

    public function onQuit(PlayerQuitEvent $event): void {
        $player = $event->getPlayer();
        unset($this->playersStatus[$player->getName()]);
    }

    public function getPlayerStatus(Player $player): ?PlayerStatus {
        return $this->playersStatus[$player->getName()] ?? null;
    }
    
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
    if($command->getName() === "guibar" && $sender instanceof Player){
        $this->openGui($sender);
        return true;
    }
    return false;
}

    // GUI Interaktif
    public function openGui(Player $player): void {
        $status = $this->getPlayerStatus($player);
        if($status === null) return;

        $form = new SimpleForm(function(Player $player, ?int $data) use ($status) {
            if($data === null) return;
            switch($data){
                case 0:
                    $status->eat(5);
                    $player->sendMessage("🍖 Kamu makan 5 point hunger!");
                    break;
                case 1:
                    $status->drink(5);
                    $player->sendMessage("💧 Kamu minum 5 point thirst!");
                    break;
                case 2:
                    $status->addMoney(50);
                    $player->sendMessage("💰 Kamu mendapatkan 50 uang!");
                    break;
            }
        });

        $form->setTitle("GuiBarRPG");
        $form->setContent("Pilih aksi:");
        $form->addButton("Makan 🍖");
        $form->addButton("Minum 💧");
        $form->addButton("Dapatkan Uang 💰");

        $player->sendForm($form);
    }
}