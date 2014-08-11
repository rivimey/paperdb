<?
/**
 * fixup_text.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Ensure that abstract texts are properly coded.
 *
 * $Id: fixup_text.php,v 1.3 2005/09/27 21:41:07 rivimey Exp $
 */

require_once("html_output_fns.php");
require_once("paper_fns.php");
require_once("admin_fns.php");

$noparas = array ( "<p>" => "",
                   "</p>" => "",
                   "\r\n\r\n" => "\n",
                   "\r\n" => "",
                   "\r" => "",
                   "\\\\" => "\\",
                   "\\'" => "'",
                   "\\\"" => "\"",
                   "</p><p>" => "\n",
                   );

$toparas = array ( "\n" => "</p><p>" );

//--------------------------------------------------------------------------------------
//   do_update
//
//  Determine if incoming text is correctly coded; that is, if it is coded in html
// entities and paragraph boundaries are indicated using <p> markers. However, the
// latter behavior can be disabled by passing 'false' in $dopara;
//
//--------------------------------------------------------------------------------------

function fixup_text($papid, $text, $name, $dopara)
{
    global $toparas, $noparas;
    
    $worklist = array();
    
    if ($text != "") {
            
        $text1 = strtr($text, $noparas);
        if ((stristr($text1, "<p>") != FALSE) || (stristr($text1, "<p>") != FALSE) ) {
            echo "WARNING: $name text for paper $papid has a problem.<br>\n";
        }
        # ensure that html entities are correctly coded.
            
        $text2 = html_entity_decode($text1, ENT_COMPAT);
        $text3 = htmlentities($text2, ENT_COMPAT);

        if ($text1 != $text3) {
            echo "<b>- $name entities were incorrectly coded.</b><br>\n";
        }
            
        if ($dopara) {
            $text4 = strtr($text3, $toparas);
            $text4 = "<p>".$text4."</p>";
        }
        else
            $text4 = $text3;
        
        if ($text == $text4) {
            echo "- $name was ok.<br>\n";
        }
        else {
            echo "<b>- $name requires update.</b><br>\n";
            $worklist[$papid] = $text4;
        }
    }
    else {
        echo "- $name was blank.<br>\n";
    }
    
    return $worklist;
}

//--------------------------------------------------------------------------------------
//   do_update
//
//  Update a table field using work, where work is a mapping of table key number to
// the new value of the field.
//
//--------------------------------------------------------------------------------------

function do_update($work, $tablename, $tablekey, $fieldname)
{
    if (is_array($work)) {        
        foreach ($work as $num => $content) {
            $query = "update $tablename set $fieldname = ".sqlvalue($content).
                " where $tablekey = ".sqlvalue($num,"N")."\n";
            
            # echo $query."<br>\n";
      
            if (generic_update($query)) {
                echo "- $fieldname updated ok.<br>\n";
            }
            else {
                echo "<b>- $fieldname updated FAIL.</b><br>\n";
            }
        }
    }
}

//--------------------------------------------------------------------------------------
//   fixup_papers
//
//  Update the title and abstract for all papers in the database.
//  
//--------------------------------------------------------------------------------------

function fixup_papers()
{
    $paper_array = get_papers("i");  // order by id

    if ($paper_array) {
        foreach ($paper_array as $thispaper)
        {
            $papid = $thispaper["paperid"];
            echo "<i>Paper $papid Title: ".$thispaper["title"]."</i><br>\n";
        
            $abstwork = fixup_text($papid, $thispaper["abstract"], "Abstract", true);
            $ttlwork = fixup_text($papid, $thispaper["title"], "Title", false);
        
            do_update($abstwork, "papers", "paperid", "abstract");
            do_update($ttlwork, "papers", "paperid", "title");

            echo "<br>\n";
        }
    }
}

//--------------------------------------------------------------------------------------
//   fixup_authors
//
//  Update the names for all people in the database.
//  
//--------------------------------------------------------------------------------------

function fixup_authors()
{
    $people_array = get_people();

    if ($people_array) {
        foreach ($people_array as $thisperson)
        {
            $personid = $thisperson["paperid"];
            echo "<i>Person $personid : ".$thisperson["lastname"].", ".$thisperson["firstname"]."</i><br>\n";
        
            $lasttwork = fixup_text($personid, $thisperson["lastname"], "Surname", false);
            $firstwork = fixup_text($personid, $thisperson["firstname"], "Forename", false);
        
            do_update($lastwork, "people", "personid", "lastname");
            do_update($firstwork, "people", "personid", "firstname");

            echo "<br>\n";
        }
    }
}

//--------------------------------------------------------------------------------------

session_start();
$f = isset($_GET['f']) ? $_GET['f'] : $_POST['f'];
  
if ($f > 0 && $f < 4 ) {
    $num = isset($_GET['num']) ? $_GET['num'] : $_POST['num'];

    do_html_header("Text Updating and Checking");

    if ($f == 1) {
        echo "<p>Options:</p><ul><li>";
        do_html_url("fixup_text.php?f=2", "Fix Title and Abstract for all Papers");
        echo "<li>";
        do_html_url("fixup_text.php?f=3", "Fix Author Names for all Papers");
        echo "</ul>";
    }
    elseif ($f == 2) {
        echo "<h2>Checking Paper Title and Abstract</h2>";
        
        fixup_papers();
    }
    elseif ($f == 3) {
        echo "<h2>Checking Author Names</h2>";
        
        fixup_authors();
    }
}
else {
    do_html_header("Text Updating and Checking", "nofollow");
    echo "fixup: Unimplemented function\n";
}

// if logged in as admin, show add, delete, edit cat links
if(session_is_registered("admin_user")) {
?>
 <p>Other Links:</p>
    <ul>
      <li><a href="list_proceeds.php?f=1">List Proceedings</a></li>
      <li><a href="list_papers.php">List Papers</a></li>
      <li><a href="list_authors.php">List Authors</a></li>
      <li><a href="create_refs.php">Fill in unset ref texts</a></li>
      <li><a href="index.php">Go to main site</a></li>
    </ul>
<?
}

do_html_footer();
?>
