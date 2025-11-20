<?php

namespace GuiBarRPG;

use pocketmine\player\Player;

class PlayerStatus {

    private Player $player;
    private int $health = 20;
    private int $hunger = 20;
    private int $thirst = 20;
    private int $money = 100;

    public function __construct(Player $player) {
        $this->player = $player;
    }

    public function update(): void {
        // Kurangi hunger & thirst otomatis
        $this->decreaseHunger(1);
        $this->decreaseThirst(1);
        $this->showBar();
    }

    public function showBar(): void {
        $bar = "❤ $this->health  🍖 $this->hunger  💧 $this->thirst  💰 $this->money";
        $this->player->sendTip($bar);
    }

    // Hunger & Thirst
    public function decreaseHunger(int $amount = 1): void { $this->hunger = max(0, $this->hunger - $amount); }
    public function decreaseThirst(int $amount = 1): void { $this->thirst = max(0, $this->thirst - $amount); }
    public function eat(int $food = 5): void { $this->hunger = min(20, $this->hunger + $food); }
    public function drink(int $water = 5): void { $this->thirst = min(20, $this->thirst + $water); }

    // Money
    public function addMoney(int $amount): void { $this->money += $amount; }
    public function removeMoney(int $amount): void { $this->money = max(0, $this->money - $amount); }

    // Getters & setters
    public function getPlayer(): Player { return $this->player; }
}