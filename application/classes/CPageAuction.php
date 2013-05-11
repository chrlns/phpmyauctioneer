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
require_once("CPageList.php");

// Diese Klasse stellt Funktion zum Darstellen der Auktionsseite bereit.
class CPageAuction
{
    var $Database;
    var $PageList;
    var $System;

    function CPageAuction($system)
    {
        $this->Database = new CDatabase();
        $this->PageList = new CPageList($system);
        $this->System = $system;
    }

    function Dispose()
    {
        $this->Database->Dispose();
        $this->PageList->Dispose();
    }

    function GetContent()
    {
        $myDB = new CDatabase();

        $return = $this->Database->Query("SELECT * FROM phpmyauctioneer_auction WHERE pid = '". $this->System->ID ."'");
        $q = $this->Database->GetResultArray();
        if(!$return || empty($q))
        {
            echo "<text>Diese Auktion existiert nicht!</text>";
        }
        else
        {
            $myDB->Query("SELECT Count(*) FROM phpmyauctioneer_bid WHERE articleid = '". $q['pid'] ."'");
            $numBids = $myDB->GetFirst();
            	
            $currentPrice = $this->GetCurrentPrice($q['pid']);
            $highBidder = $this->GetHighBidder($q['pid']);
            if(!empty($highBidder))
                $highBidder = "(Höchstbietender ist $highBidder)";

            // Beschreibung und Infos zur Auktion
            echo "<text class=\"huge\">" . $q['name'] . " (Artikelnummer " . $q['pid'] . ")</text>";
            echo "<text>Preis: EUR $currentPrice ($numBids Gebote) $highBidder</text>";
            echo "<text>Verbleibende Zeit: " . $this->PageList->FormatDate($q['ends'] - time()) . "</text>";
            echo "<text>Beschreibung:</text>";
            echo "<text class=\"greybackground\">" . $q['description'] . "</text>";
            	
            if($this->System->Authentification >= 1)
            {
                if($q['ends'] > time())
                {
                    // Bietfeld
                    echo "<text class=\"huge\">Mitbieten:</text>";
                    echo "<form url=\"bid_exec\">";
                    echo "<input type=\"hidden\" name=\"id\" value=\"" . $q['pid'] . "\" />";
                    echo "<input type=\"text\" name=\"bid\" text=\"Ihr Gebot:\" />";
                    echo "<submit text=\"Bieten\" />";
                    echo "</form>";
                }
                else
                    echo "<text>Die Auktion ist beendet, daher können keine Gebote mehr abgegeben werden!</text>";
            }
            else
            {
                echo "<text><link url=\"auth_requ\">Bitte einloggen um mit zu bieten!</link></text>";
            }
        }
    }

    function GetCurrentPrice($id)
    {
        $db = new CDatabase();
        $db->Query("SELECT bid FROM phpmyauctioneer_bid WHERE articleid = '$id' ORDER BY bid DESC LIMIT 2");

        $highesBid = $db->GetResultArray();
        $secondBid = $db->GetResultArray();

        $db->Query("SELECT * FROM afoot_auction WHERE pid = '$id';");
        $auctionData = $db->GetResultArray();

        if(empty($secondBid) || empty($highesBid))
            $currentPrice = $auctionData['start_bid'];
        else
        {
            $currentPrice = $secondBid['bid'] + 0.01;
        }

        return strtr($currentPrice, ".", ",");
    }

    function GetHighBidder($id)
    {
        $db = new CDatabase();
        $db->Query("SELECT username FROM phpmyauctioneer_bid WHERE articleid = '$id' ORDER BY bid DESC LIMIT 1");

        return $db->GetFirst();
    }
}

?>