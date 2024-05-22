<?php

use Botble\Menu\Facades\Menu;

app()->booted(fn () => Menu::useMenuItemIconImage());
