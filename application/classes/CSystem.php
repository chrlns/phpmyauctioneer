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
require_once("Constants.php");

// Diese Klasse stellt allgemeine Funktionen f�r die Funktionalit�t des Systems bereit.
class CSystem
{
    var $Content;
    var $Database;
    var $ID;
    var $Useremail;
    var $Username;
    var $Session;
    var $Authentification;
    var $PostVars;
    var $GetVars;

    function CSystem($getVars, $postVars)
    {
        // Database
        $this->Database = new CDatabase();

        // Parsen des Content Parameters
        $this->Content = $getVars['content'];

        if(empty($this->Content))
            $this->Content = $postVars['content'];

        if(empty($this->Content))
            $this->Content = "index";

        // Parsen des ID Parameters
        $this->ID = $getVars['id'];

        if(empty($this->ID))
            $this->ID = $postVars['id'];

        // User name
        $this->Username = $getVars['user'];

        if(empty($this->Username))
            $this->Username = $postVars['user'];

        // Session
        $this->Session = $getVars['session'];

        if(empty($this->Session))
            $this->Session = $postVars['session'];

        if(!empty($this->Session))
            $this->CheckSession();
        else
            $this->Authentification = 0;

        $this->PostVars = $postVars;
        $this->GetVars = $getVars;
        	
        // Userdaten laden
        if($this->Authentification >= 1)
        {
            if(!$this->Database->Query("SELECT * FROM phpmyauctioneer_user WHERE username = '" . $this->Username . "';"))
                echo "<text>Fehler bei der Datenbankabfrage!</text>";

            $result = $this->Database->GetResultArray();

            $this->Useremail = $result['email'];
        }
        	
        // Auktion checken
        $this->CheckAuction();
    }

    function Dispose()
    {
        $this->Database->Dispose();
    }

    function CheckSession()
    {
        $result = $this->Database->Query("DELETE FROM phpmyauctioneer_session WHERE created < '" . intval(time() - 15*60) . "'");

        if(!$result)
            echo "<text>CSystem::CheckSession: " . mysql_error() . "</text>";
        	
        $this->Database->Query("SELECT * FROM phpmyauctioneer_session WHERE id ='" . $this->Session . "'");
        $result = $this->Database->GetResultArray();

        if($result['username'] == $this->Username && $result['ip'] == getenv("REMOTE_ADDR"))
        {
            $this->Authentification = 1;

            $this->Database->Query("UPDATE phpmyauctioneer_session SET created = '" . time() . "' WHERE id = '" . $this->Session . "'");

            return true;
        }
        else
        {
            $this->Authentification = 0;
            $this->Session = 0;
            return false;
        }
    }

    // This method checks an auction
    function CheckAuction()
    {
        $pa = new CPageAuction($this->System);

        // L�schen der Auktionen, die schon seit 14 Tagen beendet sind
        $result = $this->Database->Query("DELETE FROM phpmyauctioneer_auction WHERE ends > '" . intval(time() + 60*60*24*14) . "' AND status <> 'running';");
        if(!$result)
        {
            echo "<text>CSystem::CheckAuction: " . mysql_error() . "</text>";
        }
        	
        // Beenden von Auktionen
        $this->Database->Query("SELECT * FROM phpmyauctioneer_auction WHERE ends < '" . time() . "' AND status = 'running';");
        while($q = $this->Database->GetResultArray())
        {
            $db = new CDatabase();
            
            // Status der Auktion auf "Beendet" setzen
            $db->Query("UPDATE phpmyauctioneer_auction SET status = 'ended' WHERE pid = '" . $q['pid'] . "'");

            $currentPrice = $pa->GetCurrentPrice($q['pid']);

            // Wer hat's ersteigert?
            $buyer_username = $pa->GetHighBidder($q['pid']);

            // Wer hat's verkauft?
            $seller_username = $q['seller'];
            $result = $db->Query("SELECT * FROM phpmyauctioneer_user WHERE username = '$seller_username'");
            $result = $db->GetResultArray();
            $seller_email = $result['email'];
            $seller_name = $result['firstname'] . " " . $result['lastname'];
            $seller_address = $result['address'];
            $seller_city = $result['zipcode'] . " " . $result['city'];

            if(!empty($buyer_username))
            {
                $result = $db->Query("SELECT * FROM phpmyauctioneer_user WHERE username = '$buyer_username'");
                $result = $db->GetResultArray();
                $buyer_email = $result['email'];
                $buyer_name = $result['firstname'] . " " . $result['lastname'];
                $buyer_address = $result['address'];
                $buyer_city = $result['zipcode'] . " " . $result['city'];

                // E-Mail an K�ufer und Verk�ufer
                $msg = "Glückwunsch, Sie haben den Artikel " . $q['name'] . " für EUR " . $currentPrice . " (zzgl. Versand) ";
                $msg .= " erworben!\n\nAdresse des Verkäufers:\n";
                $msg .= "$seller_name\n";
                $msg .= "$seller_address\n";
                $msg .= "$seller_city\n";
                $msg .= "E-Mail: $seller_email\n";

                mail($buyer_email, "Artikel erworben", $msg);

                $msg = "Glückwunsch, Sie haben den Artikel " . $q['name'] . " für EUR " . $currentPrice . " (zzgl. Versand) ";
                $msg .= " verkauft!\n\nAdresse des Käufers:\n";
                $msg .= "$buyer_name\n";
                $msg .= "$buyer_address\n";
                $msg .= "$buyer_city\n";
                $msg .= "E-Mail: $buyer_email\n";

                mail($seller_email, "Artikel verkauft", $msg);
            }
            else
            {
                $msg = "Leider hat niemand Ihren Artikel " . $q['name'] . " erworben!\n\n";
                $msg .= "Die Auktion wurde erfolglos beendet!";
                	
                mail($seller_email, "Artikel nicht verkauft", $msg);
            }

        }
    }

}
?>