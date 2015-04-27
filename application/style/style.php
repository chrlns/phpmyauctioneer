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

header("Content-Type: text/xsl");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";

$User    = $HTTP_GET_VARS['user'];
$Session = $HTTP_GET_VARS['session'];
?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" encoding="utf-8" doctype-public="-//W3C//DTD XHTML 1.0     Transitional//EN" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"/>
<xsl:template match="/">
  <html>
  <head>
  <title>Auktionshaus</title>
  <link rel="stylesheet" type="text/css" href="style/style.css" />
  </head>
  <body>
  <xsl:apply-templates />
  </body>
  </html>
</xsl:template>

<xsl:template match="logo">
  <img style="position: absolut; top: 25px; left: 15px;">
  <xsl:attribute name="src"><xsl:value-of select="@url" /></xsl:attribute>
  </img>
</xsl:template>

<xsl:template match="menu">
  <table style="position: absolute; top: 25px; left: 340px;">
  <tr>
  <xsl:apply-templates />
  </tr>
  </table>
</xsl:template>

<xsl:template match="content">
  <table style="border: 0px; padding-top: 5px;" cellspacing="0">
  <tr><td style="background-color: #0000FF; heigth: 20px;"></td>
      <td style="background-color: #0000FF; width: 20px; height: 20px; "></td>
  </tr>
  <tr><td>
  <xsl:apply-templates />
  </td>
  <td style="background-color: #0000FF; width: 20px;"></td></tr>
  </table>
</xsl:template>

<xsl:template match="categories">
  <xsl:apply-templates />
</xsl:template>

<xsl:template match="category">
  <xsl:apply-templates />
</xsl:template>

<xsl:template match="menuentry">
  <td>
  <xsl:attribute name="class"><xsl:value-of select="@class" /></xsl:attribute>
  <a>
  <xsl:attribute name="href">index.php?content=<xsl:value-of select="@url" />&amp;session=<?php echo $Session; ?>&amp;user=<?php echo $User; ?></xsl:attribute>
  <xsl:value-of select="@text" />
  </a>
  </td>
</xsl:template>

<xsl:template match="link">
  <a>
  <xsl:attribute name="href">index.php?content=<xsl:value-of select="@url" />&amp;session=<?php echo $Session; ?>&amp;user=<?php echo "$User"; ?></xsl:attribute>
  <xsl:apply-templates />
  </a>
</xsl:template>

<xsl:template match="rawlink">
  <a>
  <xsl:attribute name="href"><xsl:value-of select="@url" /></xsl:attribute>
  <xsl:apply-templates />
  </a>
</xsl:template>

<xsl:template match="text">
  <p>
	<xsl:attribute name="class">
	  <xsl:value-of select="@class" />
	</xsl:attribute>
  <xsl:value-of select="text" />
  <xsl:apply-templates />
  </p>
</xsl:template>

<xsl:template match="column">
  <td>
  <xsl:attribute name="class"><xsl:value-of select="@class" /></xsl:attribute>
  <xsl:apply-templates />
  </td>
</xsl:template>

<xsl:template match="row">
  <tr>
  <xsl:apply-templates />
  </tr>
</xsl:template>

<xsl:template match="table">
  <table>
  <xsl:apply-templates />
  </table>
</xsl:template>

<xsl:template match="form">
  <form method="POST" action="index.php">
  <input type="hidden" name="session" value="<?php echo $Session; ?>" />
  <input type="hidden" name="user" value="<?php echo $User; ?>" />
  <input type="hidden" name="content">
    <xsl:attribute name="value"><xsl:value-of select="@url" /></xsl:attribute>
  </input>
  <table>
  <xsl:apply-templates />
  </table>
  </form>
</xsl:template>

<xsl:template match="input">
  <tr><td>
  <xsl:value-of select="@text" />
  </td><td>
  <input>
    <xsl:attribute name="type"><xsl:value-of select="@type" /></xsl:attribute>
    <xsl:attribute name="name"><xsl:value-of select="@name" /></xsl:attribute>
    <xsl:attribute name="value"><xsl:value-of select="@value" /></xsl:attribute>
  </input>
  </td></tr>
</xsl:template>

<xsl:template match="submit">
  <tr><td></td><td>
  <input type="submit">
  <xsl:attribute name="value">
  <xsl:value-of select="@text" />
  </xsl:attribute>
  </input>
  </td></tr>
</xsl:template>

<xsl:template match="option">
  <option>
  <xsl:attribute name="value">
    <xsl:value-of select="@value" />
  </xsl:attribute>
  <xsl:apply-templates />
  </option>
</xsl:template>

<xsl:template match="select">
  <tr><td>
  <xsl:value-of select="@text" />
  </td><td>
  <select>
  <xsl:attribute name="name">
    <xsl:value-of select="@name" />
  </xsl:attribute>
  <xsl:apply-templates />
  </select>
  </td></tr>
</xsl:template>

<xsl:template match="textarea">
  <tr><td>
  <xsl:value-of select="@text" />
  </td><td>
  <textarea>
  <xsl:attribute name="cols">
    <xsl:value-of select="@width" />
  </xsl:attribute>
  <xsl:attribute name="rows">
    <xsl:value-of select="@height" />
  </xsl:attribute>
	<xsl:attribute name="name">
	  <xsl:value-of select="@name" />
	</xsl:attribute>
  <xsl:apply-templates />
  </textarea>
  </td></tr>
</xsl:template>

</xsl:stylesheet>
