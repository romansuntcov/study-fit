#!/usr/bin/env python3

#XTD:xsuntc00

import argparse
import xml.etree.ElementTree as etree
import sys
import re


#vypis chyb
def ErrPrint(errorMessage, errorCode):
	print(errorMessage, file=sys.stderr)
	exit(errorCode)

#vypis napovedy
def Help():

	napoveda = """\t--help - vypise napovedu ke skriptu\n
	--input - zadany vstupni soubor\n
	--output - zadany vystupni soubor\n
	--header - na zacatek se vypise zadana hlavicka\n
	--etc - urcuje max pocet sloupcu vzniklych ze stejnojmennych podelementu\n
	-a nebudou se generovat sloupce z atributu\n
	-b pokud bude element obsahovat vice elementu stejneho nazvu, bude se uvazovat\n
	jako by zde byl jediny takovy\n
	-g nereseno\n"""
	return(napoveda)

def typeOf(dataType):	
	if dataType == "BIT":
		return 0
	elif dataType == "INT":
		return 1
	elif dataType == "FLOAT":
		return 2
	elif dataType == "NVARCHAR":
		return 3
	else:
		return 4

#pomocna funkce pro zmenu datovych typu BIT < INT <FLOAT < NVARCHAR < NTEXT
def TypeChange(data, oldType, atribut=False):
	tmp = getType(data, atribut)

	if tmp == "BIT": new = 0
	elif tmp == "INT": new = 1
	elif tmp == "FLOAT": new = 2
	elif tmp == "NVARCHAR": new = 3
	else: new = 4

	if oldType == "BIT": old = 0
	elif oldType == "INT": old = 1
	elif oldType == "FLOAT": old = 2
	elif oldType == "NVARCHAR": old = 3
	else: old = 4
	
	if(new > old):
		return tmp
	else:
		return oldType

#pomocna funkce pro zjiskani datovych typu
def getType (data,atribut=False):
	data = str(data)
	if re.match (r"^(0|1|True|False)$",data) or data=="":
		return "BIT"
	elif re.match (r'^[0-9]+$',data):      
		return "INT"
	elif re.match(r"^\d+\.\d+$", data) or re.match(r"^\d+e[+|-|]\d+$", data) or re.match(r"^\d+\.\d+e[+|-|]\d+$", data):
		return "FLOAT"
	elif re.match (r"[\w\W\s]*", data) and atribut==True:
		return "NVARCHAR"
	else:
		return "NTEXT"

#parsing XML
def parserXML(root, data, arguments):
	#pomocny promenny pro spravny vypis
	predpona = "PRK_"
	pripona = "_ID"
	val = "value"
	

	#Prace s souberem ktery jsme dostali po parsingu XML 
	for elem in root.getchildren():
		elemName = elem.tag.lower()
		
		#ulozime vsechny elementy ve formatu PRK_elemName_ID INT PRIMARY KEY
		if elemName not in data.keys():
			data[elemName] = {predpona+elemName+pripona: "INT PRIMARY KEY"}

		if elem.text and elem.text.strip():
			if val in data[elemName].keys():
				data[elemName]['value'] = TypeChange(elem.text.strip(), data[elemName]['value'], False)
			else:
				data[elemName]['value'] = getType(elem.text.strip(), False)

		#prace s atributy
		if elem.attrib and not arguments.a:
			for key in elem.attrib.keys():
				if key in data[elemName].keys():
					data[elemName][key.lower()] = TypeChange(elem.attrib[key], data[elemName][key.lower()], True)
				else:
					data[elemName][key.lower()] = getType(elem.attrib[key], True)

		pocitadlo = {}

		#prace s subelementy
		for subElem in elem.getchildren():
			subElemName = subElem.tag.lower()
			
			#nastaveni pocitadla
			if subElemName not in pocitadlo.keys():
				pocitadlo[subElemName] = 1
			else:
				pocitadlo[subElemName] += 1

		for keys in pocitadlo.keys():
			#pokud etc je vetsi nez pocitadlo
			if not arguments.etc or pocitadlo[keys] <= int(arguments.etc):

				if pocitadlo[keys] == 1 or arguments.b:
					if keys.lower()+pripona in elem.attrib.keys():
						ErrPrint("Konflikt v nazvech sloupcu nebo atributu", 90)
						
					data[elemName][keys.lower()+pripona] = "INT"
				else:
					while pocitadlo[keys] > 0:
						if keys.lower()+str(pocitadlo[keys])+pripona in elem.attrib.keys():
							ErrPrint("Konflikt v nazvech sloupcu nebo atributu", 90)
							
						data[elemName][keys.lower()+str(pocitadlo[keys])+pripona] = "INT"
						pocitadlo[keys] -= 1
			#pokud je mensi
			else:
				for equal in elem.findall(keys):

					if elemName+pripona in equal.attrib.keys():
						printError("Konflikt v nazvech sloupcu nebo atributu", 90)

					if equal.tag.lower() not in data.keys():
						data[equal.tag.lower()] = {predpona+equal.tag.lower()+pripona: "INT PRIMARY KEY"}
					data[equal.tag.lower()][elemName+pripona] = "INT"

					

		parserXML(elem, data, arguments)

#vypis SQL
def printSQL(data, out, arguments):
	
	#vypis tabulek
	for table in data.keys():
		out.write("CREATE TABLE " + table + "(\n")
		
		var = int(len(data[table]))
		for var1 in data[table].keys():
			if var == 1:
				out.write("\t" + var1 + " " + data[table][var1] + "\n")
			else:
				out.write("\t" + var1 + " " + data[table][var1] + ",\n")	
			var -= 1
		
		out.write(");\n\n")	

#main

#prace s argumenty
parser = argparse.ArgumentParser(description="XML2DDL", add_help=False)
parser.add_argument("--help", action="store_true", dest="help", help="help")
parser.add_argument("--input", action="store", dest="inputfile", help="vstupni XML soubor")
parser.add_argument("--output", action="store", dest="outputfile", help="vystupni DDL soubor")
parser.add_argument("--header", action="store", dest="hlavicka", help="vypise hlavcku")
parser.add_argument("--etc", action="store", dest="etc", help="max pocet sloupcu vzniklych ze stejnomennych podelementu")
parser.add_argument("-a", action="store_true", dest="a", help="sloupce se negeneruji")
parser.add_argument("-b", action="store_true", dest="b", help="viz help")
parser.add_argument("-g", action="store_true", dest="g", help="viz help")

try:
	arguments = parser.parse_args()
except:
	ErrPrint("Chyba v argumentech", 1)

#kontrola argumentu etc+b
if arguments.etc and arguments.b:
	ErrPrint("Parametr b nesmi byt kombinovan s parametrem etc", 1)

#kontrola argumentu help
if arguments.help:
	if len(sys.argv) > 2:
		ErrPrint("argument --help nemuze byt skombinovan s jinymi parametry",1)
	else:
		sys.stdout.write(Help())
	exit(0)

#Vstupni soubor
try:
	if arguments.inputfile:
		inFile = open(arguments.inputfile, "r")
	else:
		inFile = sys.stdin
except:
	ErrPrint("Chyba pri otevreni vstupniho souboru", 2)
	
#Vystupni soubor
try:
	if arguments.outputfile:
		outFile = open(arguments.outputfile, "w")
	else:
		outFile = sys.stdout
except:
	ErrPrint("Chyba pri otevreni vystupniho souboru", 3)



if arguments.etc:
	if int(arguments.etc) < 0:
		ErrPrint("etc nesmi byt <0", 1)

if not arguments.etc:
	arguments.etc = 0;

#parsing XML souboru    
tree = etree.parse(inFile)
root = tree.getroot()
data={}
parserXML(root, data, arguments)

#generovani hlavicky
if arguments.hlavicka:
	outFile.write("--" + arguments.hlavicka +"\n\n")
#vypis SQL tabulek
printSQL(data, outFile, arguments)

#uzavreni vstupniho a vystupniho souboru
inFile.close()
outFile.close()

