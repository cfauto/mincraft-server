<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\command;

use pocketmine\lang\Translatable;

/**
 * @internal
 */
final class AutoPermissionCheckingCommandProxy extends Command{

	public function __construct(private Command $delegate){
		parent::__construct($delegate->getName(), $delegate->getDescription(), $delegate->getUsage(), $delegate->getAliases());
		unset($this->timings); //hack for __get()
	}

	public function getName() : string{
		return $this->delegate->getName();
	}

	public function getPermission() : ?string{
		return $this->delegate->getPermission();
	}

	public function setPermission(?string $permission) : void{
		$this->delegate->setPermission($permission);
	}

	public function testPermission(CommandSender $target, ?string $permission = null) : bool{
		return $this->delegate->testPermission($target, $permission);
	}

	public function testPermissionSilent(CommandSender $target, ?string $permission = null) : bool{
		return $this->delegate->testPermissionSilent($target, $permission);
	}

	public function getLabel() : string{
		return $this->delegate->getLabel();
	}

	public function setLabel(string $name) : bool{
		return $this->delegate->setLabel($name);
	}

	public function register(CommandMap $commandMap) : bool{
		return $this->delegate->register($commandMap);
	}

	public function unregister(CommandMap $commandMap) : bool{
		return $this->delegate->unregister($commandMap);
	}

	public function isRegistered() : bool{
		return $this->delegate->isRegistered();
	}

	public function getAliases() : array{
		return $this->delegate->getAliases();
	}

	public function getPermissionMessage() : ?string{
		return $this->delegate->getPermissionMessage();
	}

	public function getDescription() : Translatable|string{
		return $this->delegate->getDescription();
	}

	public function getUsage() : Translatable|string{
		return $this->delegate->getUsage();
	}

	public function setAliases(array $aliases) : void{
		$this->delegate->setAliases($aliases);
	}

	public function setDescription(Translatable|string $description) : void{
		$this->delegate->setDescription($description);
	}

	public function setPermissionMessage(string $permissionMessage) : void{
		$this->delegate->setPermissionMessage($permissionMessage);
	}

	public function setUsage(Translatable|string $usage) : void{
		$this->delegate->setUsage($usage);
	}

	public function __toString() : string{
		return "AutoPermissionCheckingCommandProxy(" . $this->delegate->__toString() . ")";
	}

	public function __get(string $name) : mixed{
		if($name === "timings"){
			return $this->delegate->timings;
		}

		throw new \Error("Access to undefined property " . self::class . "::$" . $name);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(!$this->delegate->testPermission($sender)){
			return true;
		}

		return $this->delegate->execute($sender, $commandLabel, $args);
	}
}