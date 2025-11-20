<?php

namespace GuiBarRPG\Tasks;

use pocketmine\scheduler\Task;
use GuiBarRPG\Main;

class UpdateBarTask extends Task {

    private Main $plugin;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    public function onRun(): void {
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
            $status = $this->plugin->getPlayerStatus($player);
            if($status !== null){
                $status->update();
            }
        }
    }
}