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

class CPageDynamic
{
    var $Database;
    var $System;

    function CPageDynamic($system)
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
        switch($this->System->Content)
        {
            case "bid_exec":
                {
                    $this->ExecuteBid();
                    break;
                }
            case "login_exec":
                {
                    $this->ExecuteLogin();
                    break;
                }
            case "sell_exec":
                {
                    $this->ExecuteSell();
                    break;
                }
            case "signup_exec":
                {
                    $this->ExecuteSignup();
                    break;
                }
        }
    }

    function ExecuteBid()
    {
        $bid = strtr($this->System->PostVars['bid'], ",",".");
        $id  = $this->System->ID;

        $db = new CDatabase();

        $db->Query("SELECT start_bid FROM phpmyauctioneer_auction WHERE pid = '$id'");
        $startbid = $db->GetFirst();

        if($bid < $startbid)
        {
            echo "<text>Ihr Gebot muss über dem Startgebot liegen!</text>";
            echo "<link url=\"auction&amp;id=$id\">Zurück</link>";
        }
        else
        {
            $result = $db->Query("INSERT INTO afoot_bid (bid, articleid, username) VALUES
                    ('$bid', '$id', '" . $this->System->Username . "')");
            	
            if($result)
            {
                echo "<text>Danke für Ihr Gebot!</text>";
                echo "<link url=\"auction&amp;id=$id\">Zurück</link>";
            }
            else
            {
                echo mysql_error();
            }
        }
    }

    function ExecuteLogin()
    {
        $username = $this->System->PostVars['username'];
        $password = md5($this->System->PostVars['password']);

        $this->Database->Query("SELECT * FROM phpmyauctioneer_user WHERE username = '$username'");
        $result = $this->Database->GetResultArray();

        if($result['password'] == $password)
        {
            $session = md5(rand());
            $this->Database->Query("INSERT INTO phpmyauctioneer_session
                    VALUES ('" . time() . "', '". $session . "', '" . getenv("REMOTE_ADDR") . "' ,'" . $username . "')");

            echo "<text>Hallo, $username!</text>";
            echo "<text>Vielen Dank für Ihren Besuch!</text>";
            echo "<text><rawlink url=\"index.php?content=index&amp;session=$session&amp;user=$username\">Hier klicken um den Login-Vorgang zu vervollst�ndigen!</rawlink></text>";
        }
        else
        {
            echo "<text>Falsches Passwort oder Benutzername!</text>";
            echo "<text><link url=\"auth_requ\">Nochmal versuchen</link></text>";
        }
    }

    function ExecuteSell()
    {
        if($this->System->Authentification != 1)
        {
            echo "<text>Fehler bei der Authentifizierung!</text>";
            echo "<text>Sie müssen <link url=\"auth_requ\">eingeloggt</link> sein um eine Auktion erstellen zu können!</text>";
        }
        else
        {
            $errorOccured = false;

            echo "<text class=\"huge\">Auktion einstellen</text>";

            if(empty($this->System->PostVars['auction_title']))
            {
                echo "<text>Bitte geben Sie einen Titel für die Auktion an!</text>";
                $errorOccured = true;
            }

            if(empty($this->System->PostVars['auction_category']))
            {
                echo "<text>Bitte wählen Sie eine Kategorie für die Auktion!</text>";
                $errorOccured = true;
            }

            if(empty($this->System->PostVars['auction_description']))
            {
                echo "<text>Bitte geben Sie ein Beschreibung der Auktion an!</text>";
                $errorOccurred = true;
            }

            if(empty($this->System->PostVars['auction_startbid']))
            {
                echo "<text>Bitte geben Sie ein Startgebot für die Auktion an!</text>";
                $errorOccured = true;
            }

            if(empty($this->System->PostVars['auction_duration']))
            {
                echo "<text>Bitte wählen Sie eine Dauer für die Auktion!</text>";
                $errorOccurred = true;
            }

            if(!$errorOccured)  // Es ist kein kein Fehler bis jetzt aufgetreten...
            {
                $newID = rand();
                $return = $this->Database->Query("INSERT INTO phpmyauctioneer_auction
                        (name, category, description, start_bid, starts, ends, pid, status, seller) VALUES
                        ('".$this->System->PostVars['auction_title']."',
                        '".$this->System->PostVars['auction_category']."',
                        '".$this->System->PostVars['auction_description']."',
                        '". strtr($this->System->PostVars['auction_startbid'], ",", ".") ."',
                        ". time() .", " . intval(time() + $this->System->PostVars['auction_duration']) . ",
                        '" . $newID .  "',
                        'running',
                        '" . $this->System->Username . "');");

                if($return)
                {
                    echo "<text>Auktion eingestellt!</text>";
                    	
                    $msg = "Die Auktion " . $this->System->PostVars['auction_title'] . " wurde eingestellt (ID $newID).";
                    	
                    if(mail($this->System->Useremail, "Auktion eingestellt", $msg))
                    {
                        echo "<text>Ein E-Mail zur Bestätigung wurde an " . $this->System->Useremail . " gesendet</text>";
                    }
                }
                else
                    echo "<text>" . mysql_error() ."</text>";
            }
        }
    }

    function ExecuteSignup()
    {
        $errorOccured = false;

        echo "<text>Anmeldung als Benutzer</text>";

        if(empty($this->System->PostVars['firstname']))
        {
            echo "<text>Bitte geben Sie Ihren Vornamen an!</text>";
            $errorOccured = true;
        }

        if(empty($this->System->PostVars['lastname']))
        {
            echo "<text>Bitte geben Sie Ihren Nachnamen an!</text>";
            $errorOccured = true;
        }

        if(empty($this->System->PostVars['street']))
        {
            echo "<text>Bitte geben Sie Straße und Hausnummer an!</text>";
            $errorOccured = true;
        }

        if(empty($this->System->PostVars['zip']))
        {
            echo "<text>Bitte geben Sie Ihre Postleitzahl an!</text>";
            $errorOccured = true;
        }

        if(empty($this->System->PostVars['city']))
        {
            echo "<text>Bitte geben Sie Ihre Stadt an!</text>";
            $errorOccured = true;
        }

        if(empty($this->System->PostVars['username']))
        {
            echo "<text>Bitte geben Sie einen Benutzernamen an!</text>";
            $errorOccured = true;
        }

        if(empty($this->System->PostVars['email']))
        {
            echo "<text>Bitte geben Sie Ihre gültige E-Mail-Adresse an!</text>";
            $errorOccured = true;
        }

        if(!$errorOccured) // falls kein Fehler aufgetreten ist
        {
            $randomPassword = md5(Rand());

            if($this->Database->Query("INSERT INTO phpmyauctioneer_user
                    (firstname, lastname, address, zipcode, city, email, password, username)
                    VALUES
                    (
                    '" . $this->System->PostVars['firstname'] . "',
                    '" . $this->System->PostVars['lastname'] . "',
                    '" . $this->System->PostVars['street'] . "',
                    '" . $this->System->PostVars['zip'] . "',
                    '" . $this->System->PostVars['city'] . "',
                    '" . $this->System->PostVars['email'] . "',
                    '" . md5($randomPassword) . "',
                    '" . $this->System->PostVars['username'] . "'
            )") == true)
            {
                echo "<text>Es wurde eine Nachricht an Ihre E-Mail-Adresse geschickt!</text>";
                echo "<text>Sie enthält ein vorläufiges Passwort und weitere Informationen.</text>";

                $msg = "Vielen Dank für Ihre Anmeldung bei phpMyAuctioneer!\n\n";
                $msg .= "Ihr vorläufiges Passwort: $randomPassword";

                mail($this->System->PostVars['email'], "Anmeldung bei phpMyAuctioneer", $msg);
            }
            else
            {
                echo "<text>Bei der Anmeldung ist ein Fehler aufgetreten!</text>";
            }
        }
    }
}

?>