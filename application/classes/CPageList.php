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

require_once("CDatabase.php");
require_once("CPageAuction.php");

class CPageList
{
    var $Database;
    var $System;

    function CPageList($system)
    {
        $this->Database = new CDatabase();
        $this->System = $system;
    }

    function Dispose()
    {
        $this->Database->Dispose();
    }

    function GetContent()
    {
        $myDB = new CDatabase();

        $id = $this->System->ID;
        $result = $this->Database->Query("SELECT * FROM phpmyauctioneer_auction WHERE category = '$id' ORDER BY ends DESC");

        if($result)
        {
            echo "<table>";
            	
            // Kopf der Tabelle
            echo "<row>";
            echo "<column>";
            echo "Artikel";
            echo "</column>";
            echo "<column>";
            echo "Gebote";
            echo "</column>";
            echo "<column>";
            echo "Preis";
            echo "</column>";
            echo "<column>";
            echo "Verbleibende Zeit";
            echo "</column>";
            echo "</row>";

            while($q = $this->Database->GetResultArray())
            {
                $myDB->Query("SELECT Count(*) FROM phpmyauctioneer_bid WHERE articleid = '" . $q['pid'] . "'");
                $numBids = $myDB->GetFirst();

                $pa = new CPageAuction($this->System);
                $currentPrice = $pa->GetCurrentPrice($q['pid']);

                echo "<row>";
                echo "<column>";
                echo "<link url=\"auction&amp;id=" . $q['pid'] . "\">" . $q['name'] . "</link>";
                echo "</column>";
                echo "<column>";
                echo "$numBids";
                echo "</column>";
                echo "<column>";
                echo "EUR $currentPrice";
                echo "</column>";
                echo "<column>";
                echo $this->FormatDate($q['ends'] - time());
                echo "</column>";
                echo "</row>";
            }

            echo "</table>";
        }
        else
        {
            echo "<text>" . mysql_error() . "</text>";
        }
    }

    function FormatDate($timestamp)
    {
        if($timestamp < 0)
            return "Beendet";

        $days = intval($timestamp / (24 * 3600));
        $hours = intval(($timestamp - $days * (24 * 3600)) / 3600) ;
        $min = intval(($timestamp - $days * (24 * 60 * 60) - $hours * 3600) / 60);
        $sec = intval($timestamp - $days * (24 * 60 * 60) - $hours * 3600 - $min * 60);

        if($days > 0)
            return $days . " Tage, " . $hours . " Stunden";

        if($hours > 0)
            return $hours . " Stunden, " . $min . " Minuten";

        return $min . " Minuten, " . $sec . " Sekunden";
    }
}

?>