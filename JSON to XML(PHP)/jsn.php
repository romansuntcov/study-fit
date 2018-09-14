<?php

#JSN:xsuntc00

/*	promenna ktera obsahuje konecny vypis
	ktery pak zapiseme do souboru	*/
$main = '';

//	pomocna promenna pro praci s chybami
$GLOBALS['error'] = 0;

//parametry
$shortopts = "";
$shortopts .= "r::";
$shortopts .= "h::";
$shortopts .= "n";
$shortopts .= "s";
$shortopts .= "i";
$shortopts .= "l";
$shortopts .= "c";
$shortopts .= "a";
$shortopts .= "t";

$longopts = array(
	"help",
	"input:",
	"output:",
	"array-name:",
	"item-name:",
	"array-size",
	"index-items",
	"start:",
);

$options = getopt($shortopts, $longopts);

//	prace s parametry
foreach($options as $key => $value){
	switch($key){

		//	prepinac --help vypise napovedu ke skriptu 
		case 'help': func_help(); 
			if(array_key_exists('input', $options) or array_key_exists('output', $options)
				or array_key_exists('h', $options) or array_key_exists('n', $options)
				or array_key_exists('r', $options) or array_key_exists('array-name', $options)
				or array_key_exists('item-name', $options) or array_key_exists('s', $options)
				or array_key_exists('i', $options) or array_key_exists('l', $options)
				or array_key_exists('c', $options) or array_key_exists('a', $options)
				or array_key_exists('array-size', $options) or array_key_exists('t', $options)
				or array_key_exists('index-items', $options) or array_key_exists('start', $options)){
					func_error("Error: --help nelze kombinovat s jinymi parametry\n", 1);
				}
			else 
				exit(0);
		break;

		//	pokus o nacteni dat ze vstupu --input
		case 'input':
			//	vsechno se podarilo
			if(file_exists($value))
				$data_json = file_get_contents($value);
			//	doslo k chybe
			else
				func_error("Soubor nepodarilo otevrit\n", 2);
			break;

		/*	Zapise do $file_output cestu/jmeno vystupniho soubor.
			Otvirani toho souboru, provede pouze tehdy, pokud program nenarazi na zadnou chybu.
			To udelano pro to, aby v pripade nejake chyby program nemenil obsah puvodniho souboru
		*/
		case 'output':
			$file_output = $value; break;

		//	overi parametr --start
		case 'start':
			//	chyba, pokud parametr je zaporne cislo
			if($options['start'] < 0)
				func_error("Pocatecni index nemuze byt zapornym cislem.\n", 1);
			//	pokud --start bylo zapsano ne v kombinaci s -t nebo --index-items
			if(!array_key_exists('index-items',$options))
				func_error("nenalezeno --index-items v kombinaci s --start\n", 1);
			break;

		/*	overeni vzniku invalidnich znaku v parametrech root-element, array-element a item-element 
			program posila parametry funkce is_here_problem_char, ktera jejich overi
			v pripade, ze takovy znaky najde vypise chybu a ukonci program
		*/
		case 'r':
			if (is_here_problem_char($value))
				func_error("Chyba 50: zadany root-element vede na invalidni XML jmeno\n",50); 
			$root_element = $options['r'];
			break;

		case 'array-name':
			if(is_here_problem_char($value))
				func_error("Chyba 50: zadany array-element vede na invalidni XML jmeno\n",50); 
			break;

		case 'item-name':
			if(is_here_problem_char($value))
				func_error("Chyba 50: zadany item-element vede na invalidni XML jmeno\n", 50); 
			break;

		//	aby overit, ze nejsou mezi parametry nejake nepovolene
		case 'h': break;
		case 'n': break;
		case 's': break;
		case 'i': break;
		case 'l': break;
		case 'c': break;
		case 'a': break;
		case 'array-size': break;
		case 't': break;
		case 'index-items': break;

		default: func_error("neznamy parametr\n", 1);
		}
}

//	pokud nezadan parametr input, cteme ze standartniho vstupu
if(!array_key_exists('input', $options)){
	file
	if($data_json = file_get_contents('php://stdin'))

}

//	pokud nezadan parametr output, vypisujeme na standartni vystup
if(!array_key_exists('output', $options)){
	$file_output = 'php://stdout';
}

//	konvertace json do php
$data = json_decode($data_json, true);

//	zapis do $main hlavicky
if(!array_key_exists('n', $options)){
	$main .= ('<?xml version="1.0" encoding="UTF-8?">'. "\n");
}

//	zapis do $main obalu root <root-elemet>
if(array_key_exists('r', $options)){
	$main .= ("<{$root_element}>\n");
}

//	zapis do $main vysledku praci naseho parseru
$main .= JSON2XML($data, $options, $depth = 0);

//	zapis do $main obalu root </root-element>
if(array_key_exists('r', $options)){
	$main .= ("</{$root_element}>\n");
}

/*	overime globalni promennou error,
	pokud program nenarazi na zadnou chybu bude nastavena na 0.
	otevrime output soubor,
	prepiseme ten na aktualni obsah $main
*/
if($GLOBALS['error'] == 0){
	//	podarilo se otevrit soubor output
	if($data_output = fopen($file_output, 'w'))
		fwrite($data_output, $main);
	//	pri otevreni souboru doslo k chybe
	else
		func_error("Chyba 3: chyba pri otevreni zadaneho souboru\n",3);
}


//	zavirani output souboru
if(array_key_exists('output', $options)){
	//	nepodarilo zavrit output soubor
	if(fclose($data_output) == false)
		unc_error("Chyba 3: chyba pri zavreni zadaneho souboru\n",3);
}

exit(0);


/*						vsechny funkce programu					*/


//funkce help
function func_help(){
	echo "--help vypise napovedu skriptu\n";
	echo "--input=filename zadany vstupni JSON soubor v kodovani UTF-8\n";
	echo "--output=filename textovy vystupni XML soubor v kodovani UTF-8\n";
	echo "--h=subst ve jmene elementu (jmeno-hodnota) transformuje kazdy nepovoleny znak ve jmeneXML znacky retezcem subst\n";
	echo "Implicitne subst nastaven na znak (-) pomlcka";
	echo "Vznikne-li po nahrazeni invalidni jmeno XML elementu, skript se ukonci s chybou a navratovym kodem 51\n";
	echo "-n negeneruje XML hlavicku na vystup skriptu\n";
	echo "-r=root-element jmeno paroveho korenoveho elementu obalujici vysledek\n";
	echo "Pokud nebude zadan, tak se vysledek neobaluje korenovym elementem\n";
	echo "Zadani root-elemet vedouci na nevalidni XML znacku ukonci skript s chybou a navratovym kodem 50\n";
	echo "--array-name=array-element umozni prejmenovat element obalujici pole na array-element\n";
	echo "--item-name=item-element funguje analogicky pro item\n";
	echo "-s hodnoty typu string budou transformovany na textovy elementy misto atributu\n";
	echo "-i hodnoty typu number budou transformovany na textovy elementy misto atributu\n";
	echo "-l hodnoty literalu budou transformovany na elementy <true/>,<false/> a <null/>\n";
	echo "-c aktivuje preklad problematickych znaku\n";
	echo "-a,--array-size u pole bude doplnen atribut size s uvedenim poctu prvku v tomto poli\n";
	echo "-t,--index-items ke kazdemu prvku pole bude pridan atribut index s urcenim indexu prvku\n";
	echo "--start=n inicializac inkrementalniho citace pro indexaci prvku pole (nutno kombinovat s --index-items)\n";

}

/* 	pomocna funkce pro prepinac -c
	trans je pole problematickych znaku(<,&,>,',"), 
	ktere se transformuji na odpovidajici zapisy v XML
*/
function problem_char($char){
	$trans = array("<" => "&lt;", "&" => "&amp;", ">" => "&gt;", "\"" => "&quot;", "'" => "&apos;");
	return $char = strtr($char, $trans);
}

/*	pomocna funkce pro vypis chyb
	vstupem je chybova zprava a kod chyby
	vystupem je navratovy kod chyby
	navic prepina globalni promennou error 
*/
function func_error($message, $error_code){
	//	v pripade chyby nastavime na 1
	$GLOBALS['error'] = 1;
	//	vypise chybovou zpravu na standartni STDERR vystup
	fwrite(STDERR, $message);
	//	navratovy kod
	die($error_code);
}

/*	funkce overi vstupni retezec na nevalidni XML znaky
	pokud jsou ve vstupu vrati na vystup true, 
	pokud nejsou vrati false
*/
function is_here_problem_char($subject){
	if(preg_match('/(>|<|\\"|\\/|%|{|}|&|\\\\|\\^|\\#|\\[|\\]|@|~|\s|=|\\*|\\?)/', $subject) 
		or preg_match('/^(xml|-|\\.)/i', $subject) or preg_match('/^[0-9]/', $subject))
			return true;
	else
			return false;
}


/*	funkce pro nahrazeni nepovolenych znaku ve jmene XML znacky retezcem $replacement	
	vystupem je modifikovany retezec
*/ 
function change_string($subject, $options){
	if(array_key_exists('h', $options))
		//	$replacement = subst, pokud zapnut prepinac -h
		$replacement = $options['h'];
	else
		//	$replacement = '-', pokud prepinac nezapnut
		$replacement = "-";
	
	/*	overi vstupni retezec pomoci funkce is_here_problem_char
	pokud zjisti nepovoleny znaky nahradi jejich $replacement 	*/
	if(is_here_problem_char($subject)){

			$subject = preg_replace('/(>|<|\\"|\\/|%|{|}|&|\\\\|\\^|\\#|\\[|\\]|@|~|\s|=|\\*|\\?)/', $replacement, $subject);
			$subject = preg_replace('/^(XML|xml|-|\\.)/', $replacement, $subject);
			$subject = preg_replace('/^[0-9]/', $replacement, $subject);
		}

	//	znovu overi retezec, a pokud zase narazi na nepovolene znaky, dojde k chybe
	if(is_here_problem_char($subject)){
		func_error("Chyba 51: invalidni jmeno XML elementu\n", 51);
	}

	return $subject;
}

/*	funkce pro konverzi JSON formatu do XML
	vstuperm jsou: 
	$data - konvertovane json data do formatu php
	$options - parametry se kterymi byl spusten program
	$depth - "hloubka" na zacatku nastavena na 0, pouzivame pro spravnou tabulaci
*/
function JSON2XML($data, $options, $depth){
	//pomocna promenna 1 pro spravnou tabulaci
	$var_1 = '';
	//	pomocna promenna 2, pouzivame pro vypis dat
	$var_2 = '';
	//	 pomocna promenna pro vypis indexu, pouzivame v polich
	$index = '';

	/*	nastaveni promenne var_1;
		zalezi na stavu prepinacu;
		pokud prepinac neni -r tak neni zadne mezery
	*/
	if(array_key_exists('r', $options))
		$var_1 .= "\t";
	else 
		$var_1 = "";

	//	pokud nemame zadany parametr --start nastavime ho na 1
	if(!array_key_exists('start', $options))
		$options['start'] = 1;

	//	pokud nemame zadany parametr --item-name nastavime ho na "item"
	if(array_key_exists('item-name', $options))
		$i_name = $options['item-name'];
	else
		$i_name = "item";

	//	pokud nemame naastaveny parametr --array-name nastavime ho na "array"
	if(array_key_exists('array-name', $options))
		$a_name = $options['array-name'];
	else
		$a_name = "array";

	//	pomocny for pro spravnou tabulace
	for($i = 0; $i < $depth; $i++)
		$var_1 .= "\t";

	//	vypis obalu <array>
	if(is_array($data)){
		//vypis poctu prvku pole
		if(array_key_exists('0', $data)){
			if(array_key_exists('a', $options) or array_key_exists('array-size', $options))
				$var_2 .= "{$var_1}<{$a_name} size=\"".count($data)."\">\n";
			else
				$var_2 .= "{$var_1}<{$a_name}>\n";
		}
	}

	/*	hlavni cast funkce
		udelana pomoci foreach
		key je jmeno, value je hodnota
	*/

	foreach($data as $key => $value){

		//	pokud pracujeme s polem
		if(is_array($value)){

			//	pokud pracujeme s polem ktere ma vic nez 1 item
			if(is_numeric($key)){
				//	zajistime index u elementu pole
				if(array_key_exists('t', $options) or array_key_exists('index-items', $options)){
					$index = " index=\"". $options['start']."\"";
					$options['start'] += 1;
				}
				//	vypiseme u jednotlivych elementu jejich indexy
				$var_2 .= "{$var_1}\t<{$i_name}{$index}>\n";

				//	rekurzivni volani funkce
				$var_2 .= JSON2XML($value, $options, $depth + 2);
				$var_2 .= "{$var_1}\t</{$i_name}>\n";
			}
			//	pokud pracujeme s polem ktere ma 1 item
			else{
				$var_2 .= "{$var_1}<{$key}>\n";
				//	rekurzivni volani funkce
				$var_2 .= JSON2XML($value, $options, $depth + 1);
				$var_2 .= "{$var_1}</{$key}>\n";
			}
		}

		else{
			/*	pokud pokracujeme pracovat s polem ale nezajisteno u toho tzv. jmeno
				spocitame index stejne jak i u obycejnych polich
				pak $key = $i_name pro spravny vypis dat
			*/
			if(is_numeric($key)){
				$key = $i_name;
				if(array_key_exists('t', $options) or array_key_exists('index-items', $options)){
					$index = " index=\"". $options['start']."\"";
					$options['start'] += 1;
				}
			}
			else
				/*	pokud pracujeme se string klicmi
					odesilani na overeni a modifikace do funkce change string
				*/
				$key = change_string($key, $options);

			//	mame na vstupu hodnotu true, formatu boolean
			if(is_bool($value) && ($value === true)){
				//	vypis v textovem formatu (-l)
				if(array_key_exists('l', $options)){
					$var_2 .= "{$var_1}<$key>{$index}\n";
					$var_2 .= "{$var_1}\t<true/>\n";
					$var_2 .= "{$var_1}</{$key}>\n";
				}
				else{
					$var_2 .= "{$var_1}<{$key}{$index} value=\"true\"/>\n";
				}
			}

			//	mame na vstupu hodnotu false, formatu boolean
			elseif(is_bool($value) && ($value === false)){
				//vypis v textovem formatu (-l)
				if(array_key_exists('l', $options)){
					$var_2 .= "{$var_1}<$key>{$index}\n";
					$var_2 .= "{$var_1}\t<false/>\n";
					$var_2 .= "{$var_1}</{$key}>\n";
				}
				else
					$var_2 .= "{$var_1}<{$key}{$index} value=\"false\"/>\n";
			}

			//	mame na vstupu hodnotu NULL
			elseif(is_null($value)){
				//	vypis v textovem formatu (-l)
				if(array_key_exists('l', $options)){
					$var_2 .= "{$var_1}<$key>{$index}\n";
					$var_2 .= "{$var_1}\t<null/>\n";
					$var_2 .= "{$var_1}</{$key}>\n";
				}
				else
					$var_2 .= "{$var_1}<{$key}{$index} value=\"null\"/>\n";
			}

			//	mame na vstupu hodnotu typu string
			elseif(is_string($value)){

				//	preklad problematickych znaku pomoci funkce problem_char(-c)
				if(array_key_exists('c', $options)){
					$value = problem_char($value);
				}
				
				//	vypis v textovem formatu(-s)
				if(array_key_exists('s', $options)){
					$var_2 .= "{$var_1}<{$key}>{$index}\n";
					$var_2 .= "{$var_1}\t{$value}\n";
					$var_2 .= "{$var_1}</{$key}>\n";
				}
				else
					$var_2 .= "{$var_1}<{$key}{$index} value=\"{$value}\"/>\n";
			}

			//	mame na vstupu hodnotu typu number
			elseif(is_numeric($value)){

				//zaokrouhlovani	
				$value = floor($value);
			
				//vypis v textovem formatu(-i)
				if(array_key_exists('i', $options)){
					$var_2 .= "{$var_1}<{$key}>{$index}\n";
					$var_2 .= "{$var_1}\t{$value}\n";
					$var_2 .= "{$var_1}</{$key}>\n";
				}
				else
					$var_2 .= "{$var_1}<{$key}{$index} value=\"{$value}\"/>\n";
			}
		}
	}

	//vypis obalu </array>
	if(is_array($data)){
		if(array_key_exists(count($data)-1, $data)){
				$var_2 .= "{$var_1}</{$a_name}>\n";
		}
	}
	return $var_2;
}

?>