<?php
/**
 * entities.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * $Id: entities.php,v 1.3 2004/11/18 19:17:15 rivimey Exp $
 */

$entitiesToBibTeX = array(

  "\"" => "\\textquotedbl",
  "#" => "\\#",
  "$" => "\\$",
  "%" => "\\%",
  "&" => "\\&",
  "<" => "\\textless",
  ">" => "\\textgreater",
  "{" => "\{",
  "}" => "\}",
  "#" => "\#",
#	"\\"	=>	"\\textbackslash",
  "^" => "\\^{}",
  "~" => "\\~{}",
  "_" => "\\_",
  "|" => "\\textbar",

  "&nbsp;" => " ",
  "&iexcl;" => "!'",
  "&cent;" => "\\textcent",
  "&pound;" => "\\pounds",
  "&curren;" => "\\textcurrency",
  "&yen;" => "\\textyen",
  "&brvbar;" => "\\textbrokenbar",
  "&sect;" => "\\S",
  "&copy;" => "\\textcopyright",
  "&ordf;" => "\\textordfeminine",
  "&laquo;" => "\\guillemotleft",
  "&not;" => "\\textlnot",
  "&shy;" => "-",
  "&reg;" => "\\textregistered",
  "&macr;" => "\\textasciimacron",
  "&deg;" => "\\textdegree",
  "&plusmn;" => "\\textpm",
  "&sup2;" => "\\texttwosuperior",
  "&sup3;" => "\\textthreesuperior",
  "&acute;" => "\\textasciiacute",
  "&micro;" => "\\textmu",
  "&para;" => "\\P",
  "&midot;" => "\\textperiodcentered",
  "&cedil;" => "\\c{}",
  "&sup1;" => "\\textonesuperior",
  "&ordm;" => "\\textordmasculine",
  "&raquo;" => "\\guillemotright",
  "&frac14;" => "\\textonequarter",
  "&frac12;" => "\\textonehalf",
  "&frac34;" => "\\textthreequarters",
  "&iquest;" => "?'",

  "&atilde;" => "\\~{a}",
  "&etilde;" => "\\~{e}",
  "&itilde;" => "\\~{i}",
  "&otilde;" => "\\~{o}",
  "&ntilde;" => "\\~{n}",
  "&Atilde;" => "\\~{A}",
  "&Etilde;" => "\\~{E}",
  "&Itilde;" => "\\~{I}",
  "&Otilde;" => "\\~{O}",
  "&Ntilde;" => "\\~{N}",

  "&acirc;" => "\\^{a}",
  "&ecirc;" => "\\^{e}",
  "&icirc;" => "\\^{i}",
  "&ocirc;" => "\\^{o}",
  "&ucirc;" => "\\^{u}",
  "&Acirc;" => "\\^{A}",
  "&Ecirc;" => "\\^{E}",
  "&Icirc;" => "\\^{I}",
  "&Ocirc;" => "\\^{O}",
  "&Ucirc;" => "\\^{U}",

  "&aacute;" => "\\`{a}",
  "&eacute;" => "\\`{e}",
  "&iacute;" => "\\`{i}",
  "&oacute;" => "\\`{o}",
  "&uacute;" => "\\`{u}",
  "&yacute;" => "\\`{y}",
  "&Aacute;" => "\\`{A}",
  "&Eacute;" => "\\`{E}",
  "&Iacute;" => "\\`{I}",
  "&Oacute;" => "\\`{O}",
  "&Uacute;" => "\\`{U}",
  "&Yacute;" => "\\`{Y}",

  "&agrave;" => "\\'{a}",
  "&egrave;" => "\\'{e}",
  "&igrave;" => "\\'{i}",
  "&ograve;" => "\\'{o}",
  "&ugrave;" => "\\'{u}",
  "&Agrave;" => "\\'{A}",
  "&Egrave;" => "\\'{E}",
  "&Igrave;" => "\\'{I}",
  "&Ograve;" => "\\'{O}",
  "&Ugrave;" => "\\'{U}",

  "&auml;" => "\\\"{a}",
  "&euml;" => "\\\"{e}",
  "&iuml;" => "\\\"{i}",
  "&ouml;" => "\\\"{o}",
  "&uuml;" => "\\\"{u}",
  "&yuml;" => "\\\"{y}",
  "&Auml;" => "\\\"{A}",
  "&Euml;" => "\\\"{E}",
  "&Iuml;" => "\\\"{I}",
  "&Ouml;" => "\\\"{O}",
  "&Uuml;" => "\\\"{U}",
  "&Yuml;" => "\\\"{Y}",

  "&Oslash;" => "\\O",
  "&oslash;" => "\\o",

  // "&Scaron;"	=>	"",	-- no symbol.
  // "&scaron;"	=>	"",	-- no symbol.

  "&Ccedil;" => "\\c{C}",
  "&ccedil;" => "\\c{c}",

  "&THORN;" => "\\TH",
  "&thorn;" => "\\th",
  "&ETH;" => "\\DH",
  "&eth;" => "\\dh",

  "&aring;" => "\\aa",
  "&Aring;" => "\\AA",

  "&aelig;" => "\\ae",
  "&AElig;" => "\\AE",

  "&oelig;" => "\\oe",
  "&OElig;" => "\\OE",

  "&szlig;" => "\\ae",
);

$entitiesToRefer = array(

  "'" => "\\[rs]",
  "\"" => "\\[dq]",
  "#" => "\\[sh]",
  "-" => "\\-",

  "&nbsp;" => "\ ",
  "&iexcl;" => "\\[r!]",
  "&cent;" => "\\[ct]",
  "&pound;" => "\\[Po]",
  "&curren;" => "\\[Cs}",
  "&yen;" => "\\[Ye]",
  "&brvbar;" => "\\[bb]",
  "&sect;" => "\\[sc]",
  "&copy;" => "\\[co]",
  "&ordf;" => "\\[Of]",
  "&laquo;" => "\\[Fo]",
  "&not;" => "\\[no]",
  "&shy;" => "\%",
  "&reg;" => "\\[rg]",
  "&macr;" => "\\[a-]",
  "&deg;" => "\\[de]",
  "&plusmn;" => "\\[+-]",
  "&sup2;" => "\\[S2]",
  "&sup3;" => "\\[S3]",
  "&acute;" => "\\[aa]",
  "&micro;" => "\\[mc]",
  "&para;" => "\\[ps]",
  "&midot;" => "\\[pc]",
  "&cedil;" => "\\c{}",
  "&sup1;" => "\\[S1]",
  "&ordm;" => "\\[Om]",
  "&raquo;" => "\\[Fc]",
  "&frac14;" => "\\[14]",
  "&frac12;" => "\\[12]",
  "&frac34;" => "\\[34]",
  "&iquest;" => "\\[r?]",

  "&atilde;" => "\\[~a]",
  "&etilde;" => "\\[~e]", // not supported?
  "&itilde;" => "\\[~i]", // not supported?
  "&otilde;" => "\\[~o]",
  "&ntilde;" => "\\[~n]",
  "&Atilde;" => "\\[~A]",
  "&Etilde;" => "\\[~E]", // not supported?
  "&Itilde;" => "\\[~I]", // not supported?
  "&Otilde;" => "\\[~O]",
  "&Ntilde;" => "\\[~N]",

  "&acirc;" => "\\[^a]",
  "&ecirc;" => "\\[^e]",
  "&icirc;" => "\\[^i]",
  "&ocirc;" => "\\[^o]",
  "&ucirc;" => "\\[^u]",
  "&Acirc;" => "\\[^A]",
  "&Ecirc;" => "\\[^E]",
  "&Icirc;" => "\\[^I]",
  "&Ocirc;" => "\\[^O]",
  "&Ucirc;" => "\\[^U]",

  "&aacute;" => "\\[`a]",
  "&eacute;" => "\\[`e]",
  "&iacute;" => "\\[`i]",
  "&oacute;" => "\\[`o]",
  "&uacute;" => "\\[`u]",
  "&yacute;" => "\\[`y]",
  "&Aacute;" => "\\[`A]",
  "&Eacute;" => "\\[`E]",
  "&Iacute;" => "\\[`I]",
  "&Oacute;" => "\\[`O]",
  "&Uacute;" => "\\[`U]",
  "&Yacute;" => "\\[`Y]",

  "&agrave;" => "\\['a]",
  "&egrave;" => "\\['e]",
  "&igrave;" => "\\['i]",
  "&ograve;" => "\\['o]",
  "&ugrave;" => "\\['u]",
  "&Agrave;" => "\\['A]",
  "&Egrave;" => "\\['E]",
  "&Igrave;" => "\\['I]",
  "&Ograve;" => "\\['O]",
  "&Ugrave;" => "\\['U]",

  "&auml;" => "\\[\"a]",
  "&euml;" => "\\[\"e]",
  "&iuml;" => "\\[\"i]",
  "&ouml;" => "\\[\"o]",
  "&uuml;" => "\\[\"u]",
  "&yuml;" => "\\[\"y]",
  "&Auml;" => "\\[\"A]",
  "&Euml;" => "\\[\"E]",
  "&Iuml;" => "\\[\"I]",
  "&Ouml;" => "\\[\"O]",
  "&Uuml;" => "\\[\"U]",
  "&Yuml;" => "\\[\"Y]",

  "&Scaron;" => "\\[vS]",
  "&scaron;" => "\\[vs]",

  "&Oslash;" => "\\[/O]",
  "&oslash;" => "\\[/o]",

  "&Ccedil;" => "\\[,C]",
  "&ccedil;" => "\\[,c]",

  "&THORN;" => "\\[TP]",
  "&thorn;" => "\\[Tp]",
  "&ETH;" => "\\[-D]",
  "&eth;" => "\\[Sd]",

  "&aring;" => "\\[oa]",
  "&Aring;" => "\\[oA]",
  "&aelig;" => "\\[ae]",
  "&AElig;" => "\\[AE]",

  "&oelig;" => "\\[oe]",
  "&OElig;" => "\\[OE]",

  "&szlig;" => "\\ss",

  // "&euro;"	=>	"\\[eu]"	-- no symbol.
  "&circ;" => "\\[a^]"
);

//------------------------------------------------------------------------------------------------------------------------------
//  XML2BibTexHTML
//
//  The database stores many characters as XML entities, some of which are
// understood by HTML, some not. Convert those required to the HTML form.
//
//------------------------------------------------------------------------------------------------------------------------------

function XML2BibTexHTML($str, $dobr) {
  global $entitiesToBibTeX;

  # Convert XML from DB to a form that looks right
  # when displayed in an HTML browser. Some HTML entities
  # get translated by the browser, so it's just 
  # a patching exercise, not a total conversion...

  $str = str_replace("</p><p>", "<br>", $str);
  $str = str_replace("\"", "''", $str);
  $str = str_replace("<p>", "", $str);
  $str = str_replace("</p>", "", $str);

  $str = strtr($str, $entitiesToBibTeX);

  if ($dobr) {
    $str = preg_replace("/([A-Z]+)/", "{\\1}", $str);
  }

  return $str;
}

//-----------------------------------------------------------------------------
//  XML2ReferHTML
//
//  The database stores many characters as XML entities
// Convert those required to the Refer form.
//
//-----------------------------------------------------------------------------


function XML2ReferHTML($str) {
  global $entitiesToRefer;

  # Convert XML from DB to a form that looks right
  # when displayed in an HTML browser. Some HTML entities
  # get translated by the browser, so it's just 
  # a patching exercise, not a total conversion...

  $str = str_replace("</p><p>", " ", $str);
  $str = str_replace("<p>", "", $str);
  $str = str_replace("</p>", "", $str);

  $str = strtr($str, $entitiesToRefer);

  # don't let the browser mess up the refer stuff
  $str = htmlspecialchars($str);

  return $str;
}

//-----------------------------------------------------------------------------
//  XML2BibTexText
//
//
//-----------------------------------------------------------------------------

function XML2BibTexText($str, $dobr) {
  global $entitiesToBibTeX;

  $str = str_replace("</p><p>", "\n", $str);
  $str = str_replace("<p>", "", $str);
  $str = str_replace("</p>", "", $str);

  $str = strtr($str, $entitiesToBibTeX);

  if ($dobr) {
    $str = preg_replace("/([A-Z]+)/", "{\\1}", $str);
  }
  # don't let the browser mess up the bibtex stuff
  $str = htmlspecialchars($str);

  return $str;
}

//-----------------------------------------------------------------------------
//  XML2ReferText
// 
//  The database stores many characters as XML entities
// Convert those required to the Refer form. Most are latin1
// characters, but it's easier to convert to the named character
// instead (see "man groff_char").
//-----------------------------------------------------------------------------

function XML2ReferText($str) {
  global $entitiesToRefer;

  $str = str_replace("</p><p>", "\n", $str);
  $str = str_replace("<p>", "", $str);
  $str = str_replace("</p>", "", $str);

  $str = strtr($str, $entitiesToRefer);

  return $str;
}
