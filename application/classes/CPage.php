<?php
/*
 *   phpMyAuctioneer
 *   Copyright (C) 2006,2013  by Christian Lins <christian@lins.me>
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU Affero General Public License as
 *   published by the Free Software Foundation, either version 3 of the
 *   License, or (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU Affero General Public License for more details.
 *
 *   You should have received a copy of the GNU Affero General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once("CCategoryList.php");
require_once("CMenu.php");
require_once("CPageDynamic.php");
require_once("CPageSell.php");
require_once("CPageStatic.php");
require_once("CPageAuction.php");
require_once("CPageList.php");

/**
 * A page and its content.
 */
class CPage
{
    var $System;
    var $CategoryList;
    var $ContentProvider;
    var $Menu;

    function CPage($system)
    {
        $this->CategoryList = new CCategoryList();
        $this->Menu = new CMenu($system);
        $this->System = $system;

        if($this->System->Content == "category")
            $this->ContentProvider = new CPageList($system);
        else if($this->System->Content == "auction")
            $this->ContentProvider = new CPageAuction($system);
        else if($this->System->Content == "sell")
            $this->ContentProvider = new CPageSell($system);
        else if(strstr($this->System->Content, "_exec") != false)
            $this->ContentProvider = new CPageDynamic($system);
        else
            $this->ContentProvider = new CPageStatic($system);
    }

    function GetContent()
    {
        if(!empty($this->System->Session))
            echo "<text>Sie sind eingeloggt als " . $this->System->Username . "!</text>";

        echo "<table>";
        echo "<row>";

        echo "<column>";

        // Category view on the left
        $this->CategoryList->GetCategories(0);

        echo "</column>";
        echo "<column>";
        	
        $this->ContentProvider->GetContent();
        echo "</column>";
        echo "</row>";
        echo "</table>";
    }

    function Dispose()
    {
        $this->CategoryList->Dispose();
        $this->ContentProvider->Dispose();
    }
}
?>