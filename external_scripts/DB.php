<?php

 define( "LOADIMAGES", TRUE);

 class DB extends MySQLi
 {

  private $nbQuery;
  private $encodage;

  private $db_host;
  private $db_user;
  private $db_password;
  private $db_dbName;
  private $db_port;
  private $db_socket;

  private $uniqueIdentifier;
  private $connected;


  public function __construct( $db_host     = MYSQL_HOST,
                               $db_port     = MYSQL_PORT,
                               $db_user     = MYSQL_USER,
                               $db_password = MYSQL_PASSWORD,
                               $db_dbName   = MYSQL_DBNAME,
                               $db_socket   = MYSQL_SOCKET )
  {
   $this->nbQuery = 0;

   $this->db_host = $db_host;
   $this->db_user = $db_user;
   $this->db_password = $db_password;
   $this->db_dbName = $db_dbName;
   $this->db_port = $db_port;
   $this->db_socket = $db_socket;

   $this->connected = FALSE;
   $this->uniqueIdentifier = rand();


   switch( MYSQL_ENCODAGE )
   {
    case "_utf8":
     $this->encodage = MYSQL_ENCODAGE;
     break;
    case "_latin1":
     $this->encodage = MYSQL_ENCODAGE;
     break;
    default:
     $this->encodage = "";
   }
  }


  public function __destruct()
  {
   //$this->disconnect();
  }


  public function connect($host = NULL, $user = NULL, $password = NULL, $database = NULL, $port = NULL, $socket = NULL)
  {
   parent::init();

   $this->real_connect( $this->db_host,
                        $this->db_user,
                        $this->db_password,
                        $this->db_dbName,
                        $this->db_port,
                        $this->db_socket );

   if( $this->connect_errno )
   {
    die('Erreur de connexion : ' . $this->connect_errno . '<br/>' );
    $bReturn = FALSE;
   }
   else
   {
    echo 'Connexion ... OK';
    $this->connected = TRUE;
    $bReturn = TRUE;
   }

   return $bReturn;
  }


  public function disconnect()
  {
   $bReturn = FALSE;

   if( $this->connected === TRUE && $this->thread_id )
   {
    $this->connected = FALSE;

    if( @$this->close() )
    {
     $bReturn = TRUE;
    }
    else
    {
     ErrorCode::set( 0x00010201 );
     $bReturn = FALSE;
    }
   }
   else
   {
    $bReturn = TRUE;
   }

   return $bReturn;
  }


  public function reconnect()
  {
   $this->disconnect();
   $this->connect();
  }


  public function isConnected()
  {
   if( $this->connected )
   {
    return TRUE;
   }
   else
   {
    return FALSE;
   }
  }


  public function getNextAutoIncrement( $table )
  {
   if( $this->thread_id )
   {
    $query = "SHOW TABLE STATUS FROM `". MYSQL_DBNAME. "` LIKE '". escapeshellcmd( $table ). "';";
    $result = $this->query( $query );

    if( !( $result === FALSE ) )
    {
     $ligne = $result->fetch_object();
     //$result->free;

     return $ligne->Auto_increment;
    }
    else
    {
     ErrorCode::set( 0x00010302 );
     return FALSE;
    }
   }
   else
   {
    ErrorCode::set( 0x00010301 );
    return FALSE;
   }
  }


  public function requete( $query ){ return $this->query( $query ); }
  public function query( $query )
  {
   global $DEBUG_NB_QUERY;

   if( $this->thread_id )
   {
    $this->nbQuery++;
    $DEBUG_NB_QUERY++;
    $result = parent::query( $query );

    if( $result === FALSE )
    {
      // print_r( $query );
    }

    return $result;
   }
   else
   {
    return FALSE;
   }
  }


  public function protectDataToDb( $text, $forceQuote=FALSE, $convertCharacterEncoding=TRUE, $strip_tags=TRUE )
  {
   if( $this->thread_id )
   {
    if( $text === NULL )
    {
     $text = "NULL";
    }
    else
    {
     if( get_magic_quotes_gpc() )
     {
      $text = stripslashes( $text );
     }

     // Protection si ce n'est pas un entier
     if( !is_numeric( $text ) || $forceQuote === TRUE )
     {
      if( $convertCharacterEncoding === TRUE )
       $text = $this->convertCharacterEncoding( $text, "UTF-8" );
      $text = $this->encodage. "'". $this->real_escape_string( ( $strip_tags === TRUE ? strip_tags( $text ) : $text ) ). "'";
     }
    }

    return $text;
   }
   else
   {
    ErrorCode::set( 0x00010501 );
    return FALSE;
   }
  }


  public function getNbQuery()
  {
   if( $this->thread_id )
    return $this->nbQuery;
   else
    return 0;
  }


  private function convertCharacterEncoding( $text, $to_encoding="UTF-8", $from_encoding=NULL )
  {
   if( !empty( $text ) )
   {
    switch( $from_encoding )
    {
     case "ASCII": $from_encoding = "ASCII"; break;
     case "UTF-8": $from_encoding = "UTF-8"; break;
     default: $from_encoding = NULL;
    }

    if( $from_encoding == NULL )
     $encodage = mb_detect_encoding( $text, mb_detect_order() );
    else
     $encodage = $from_encoding;

    if( $encodage != "" )
     $text = mb_convert_encoding( $text, $to_encoding, $encodage );
    else if( $to_encoding == "UTF-8" )
     $text = utf8_encode( $text );
   }
   else if( $to_encoding == "UTF-8" )
   {
    $text = utf8_encode( $text );
   }

   return $text;
  }

  public static function htmlaccents( $texte, $invert=FALSE, $utf8=TRUE )
  {
   $array_src = array(
                      "«",
                      "»",
                      "²",
                      "¡",
                      "¿",
                      "š",
                      "Ž",
                      "",
                      "",
                      "ž",
                      "·",
                      "",
                      "©",
                      "®",
                      "€",
                      "",
                      "¢",
                      "£",
                      "¥",
                      "",
                      "á",
                      "Á",
                      "â",
                      "Â",
                      "à",
                      "À",
                      "å",
                      "Å",
                      "ã",
                      "Ã",
                      "ä",
                      "Ä",
                      "æ",
                      "Æ",
                      "ç",
                      "Ç",
                      "é",
                      "É",
                      "ê",
                      "Ê",
                      "è",
                      "È",
                      "ë",
                      "Ë",
                      "í",
                      "Í",
                      "î",
                      "Î",
                      "ì",
                      "Ì",
                      "ï",
                      "Ï",
                      "ñ",
                      "Ñ",
                      "ó",
                      "Ó",
                      "ô",
                      "Ô",
                      "ò",
                      "Ò",
                      "ø",
                      "Ø",
                      "õ",
                      "Õ",
                      "ö",
                      "Ö",
                      "",
                      "",
                      "",
                      "",
                      "ß",
                      "ð",
                      "Ð",
                      "þ",
                      "Þ",
                      "ú",
                      "Ú",
                      "û",
                      "Û",
                      "ù",
                      "Ù",
                      "ü",
                      "Ü",
                      "ý",
                      "Ý",
                      "ÿ",
                      ""
                     );
   $array_dst = array(
                      "&laquo;",
                      "&raquo;",
                      "&sup2;",
                      "&iexcl;",
                      "&iquest;",
                      "&uml;",
                      "&acute;",
                      "&circ;",
                      "&tilde;",
                      "&cedil;",
                      "&middot;",
                      "&bull;",
                      "&copy;",
                      "&reg;",
                      "&curren;",
                      "&euro;",
                      "&cent;",
                      "&pound;",
                      "&yen;",
                      "&fnof;",
                      "&aacute;",
                      "&Aacute;",
                      "&acirc;",
                      "&Acirc;",
                      "&agrave;",
                      "&Agrave;",
                      "&aring;",
                      "&Aring;",
                      "&atilde;",
                      "&Atilde;",
                      "&auml;",
                      "&Auml;",
                      "&aelig;",
                      "&AElig;",
                      "&ccedil;",
                      "&Ccedil;",
                      "&eacute;",
                      "&Eacute;",
                      "&ecirc;",
                      "&Ecirc;",
                      "&egrave;",
                      "&Egrave;",
                      "&euml;",
                      "&Euml;",
                      "&iacute;",
                      "&Iacute;",
                      "&icirc;",
                      "&Icirc;",
                      "&igrave;",
                      "&Igrave;",
                      "&iuml;",
                      "&Iuml;",
                      "&ntilde;",
                      "&Ntilde;",
                      "&oacute;",
                      "&Oacute;",
                      "&ocirc;",
                      "&Ocirc;",
                      "&ograve;",
                      "&Ograve;",
                      "&oslash;",
                      "&Oslash;",
                      "&otilde;",
                      "&Otilde;",
                      "&ouml;",
                      "&Ouml;",
                      "&oelig;",
                      "&OElig;",
                      "&scaron;",
                      "&Scaron;",
                      "&szlig;",
                      "&eth;",
                      "&ETH;",
                      "&thorn;",
                      "&THORN;",
                      "&uacute;",
                      "&Uacute;",
                      "&ucirc;",
                      "&Ucirc;",
                      "&ugrave;",
                      "&Ugrave;",
                      "&uuml;",
                      "&Uuml;",
                      "&yacute;",
                      "&Yacute;",
                      "&yuml;",
                      "&Yuml;"
                     );

   if( $invert === TRUE )
   {
    $texte = str_replace( $array_dst, $array_src, $texte );
   }
   else
   {
    $texte = str_replace( $array_src, $array_dst, $texte );
   }

   if( $utf8 === TRUE )
   {
    $texte = utf8_encode( $texte );
   }

   return $texte;
  }


  public static function removeAccents( $texte )
  {
   $texte = str_replace(
                        array( "á", "Á", "â", "Â", "à", "À", "å", "Å", "ã", "Ã", "ä", "Ä", "æ", "Æ", "ç", "Ç", "é", "É", "ê", "Ê", "è", "È", "ë", "Ë", "í", "Í", "î", "Î", "ì", "Ì", "ï", "Ï", "ñ", "Ñ", "ó", "Ó", "ô", "Ô", "ò", "Ò", "ø", "Ø", "õ", "Õ", "ö", "Ö", "", "", "", "", "ú", "Ú", "û", "Û", "ù", "Ù", "ü", "Ü", "ý", "Ý", "ÿ", "" ),
                        array( "a", "A", "a", "A", "a", "A", "a", "A", "a", "A", "a", "A", "ae", "AE", "c", "C", "e", "E", "e", "E", "e", "E", "e", "E", "i", "I", "i", "I", "i", "I", "i", "I", "n", "N", "o", "O", "o", "O", "o", "O", "o", "O", "o", "O", "o", "O", "oe", "OE", "s", "S", "u", "U", "u", "U", "u", "U", "u", "U", "y", "Y", "y", "Y" ),
                        $texte
                       );
   return $texte;
  }

  public static function textToURI( $texte )
  {
   $texte = trim( $texte );
   $texte = DB::htmlaccents( $texte, TRUE, FALSE );
   $texte = DB::removeAccents( $texte );
   $texte = str_replace( array( "'", " ", "/" ), "-", $texte );
   $texte = str_replace( array( "*", "\\", "&", "^", "~", "$", "€", "£", "š", "%", "§", ",", "?", ";", ":", "!", "<", ">", "#", "{", "\"", "(", "[", "|", "`", ")", "]", "°", "=", "}", ".", "²" ), "", $texte );
   $texte = strtolower( $texte );

   while( strpos( $texte, "--" ) !== FALSE )
    $texte = str_replace( "--", "-", $texte );

   return $texte;
  }

  public static function ucsentences( $sentence_split )
  {
   if( $sentence_split !== NULL )
   {
    $sentence_split = preg_replace( array( '/[!]+/', '/[?]+/', '/[.]+/' ),
                                    array( '!', '?', '.' ),
                                    $sentence_split );

    $textbad = preg_split( "/(\!|\.|\?|\n)/", $sentence_split, -1, PREG_SPLIT_DELIM_CAPTURE );
    $newtext = array();
    $count = sizeof( $textbad );

    foreach( $textbad as $key=>$string )
    {
     if( !empty( $string ) )
     {
      $text = trim( $string, ' ' );
      $size = strlen( $text );

      if( $size > 1 )
      {
       $newtext[] = ucfirst( mb_strtolower( $text ) );
      }
      elseif( $size == 1 )
      {
       $newtext[] = ( $text == "\n" ) ? $text : $text. ' ';
      }
     }
    }

    $newtext = implode( $newtext );
    $newtext = preg_replace( array( '/[!]+/', '/[?]+/' ),
                             array( ' !', ' ?' ),
                             $newtext );
    $newtext = trim( $newtext );
   }
   else
   {
    $newtext = NULL;
   }

   return $newtext;
  }

  public static function slugify($text) {
    $text = DB::removeAccents($text);
    // replace all non letters or digits by -
    $text = preg_replace('/\W+/', '-', $text);

    // trim and lowercase
    $text = strtolower(trim($text, '-'));

    return $text;
  }


 }

?>



<?php

 define( "DEBUG", FALSE );
 $DEBUG_NB_QUERY = 0;

 // MySQL
 define( "MYSQL_HOST", "localhost" ); //mysql5-12.start //localhost
 define( "MYSQL_PORT", "3306" );
 define( "MYSQL_SOCKET", "/var/run/mysqld/mysqld.sock" );
 define( "MYSQL_USER", "root" ); //cpfaizenaybase //root
 define( "MYSQL_PASSWORD", "root" ); //i1c09a //"admin" // RuptadevVa
 define( "MYSQL_DBNAME_WEBSITE", "cpfaizenaybase" );
 define( "MYSQL_DBNAME", MYSQL_DBNAME_WEBSITE );
 define( "MYSQL_ENCODAGE", "_utf8" );
 define( "MYSQL_PHPSESS_TABLE", "php_sessions" );
 ?>