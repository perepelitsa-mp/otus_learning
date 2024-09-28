<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

CBitrixComponent::includeComponentClass('mycompany:my.custom.list');

$component = new MyCustomListComponent($this);
$component->executeComponent();
