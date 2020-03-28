#!/usr/bin/env python
# -*- coding: utf8 -*-

import RPi.GPIO as GPIO
import MFRC522
import signal

import socket
import sys
from thread import *
import threading

import MySQLdb
import os

import datetime

continue_reading = True

MyKey = ""

HOST = '192.168.1.40'   # Symbolic name, meaning all available interfaces
PORT = 8000 # Arbitrary non-privileged port
s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
print 'Socket created'
#Bind socket to local host and port
try:
    s.bind((HOST, PORT))
except socket.error as msg:
    print 'Bind failed. Error Code : ' + str(msg[0]) + ' Message ' + msg[1]
    sys.exit()
     
print 'Socket bind complete'
 
#Start listening on socket
s.listen(10)
print 'Socket now listening'

############################ Socket server ########################
#Function for handling connections. This will be used to create threads
def clientthread(conn):
    #Sending message to connected client
    #conn.send('Welcome to the server. Type something and hit enter\n') #send only takes string    
    #infinite loop so that function do not terminate and thread do not end.
    while True:
	#print("Update Logger....")
	retKey = getRFID()
	AckDoorMan = ManualOpenLogger()
	if(retKey != ""):
		TagID ,Name ,LastName ,ClassID ,FacCulty ,LogNum = QuiryDataMember(retKey)
		if(TagID != ""):
			print("TAG:"+TagID+" NAME:"+Name+" "+LastName+" CLASS:"+ClassID+" FACULTYL:"+FacCulty+" LOG:"+LogNum)
			ONLog = LogNum + "ON" 
			conn.sendall(ONLog)
			print("Save in history...")
			SaveHistoryMember(TagID,Name,ClassID,LogNum,FacCulty)
	### Convert Scada HEX ###
	#ScadaVal = list(StrScada)
	if(AckDoorMan != "0"):
		ManualClearLogger()
		ONLog = AckDoorMan + "ON"
                conn.sendall(ONLog)
	"""
	Protacal = ""
	for i in StrScada:
  		hex_ = hex(ord(i))
		#print(str(hex_))
		Protacal = Protacal + (str(hex_) + " ")
	#print()	
        #Receiving from client
	"""
	
        data = conn.recv(1024)
        reply = 'RCV: ' + data
	print(data)	
        if not data: 
            break
     	
        #conn.sendall(reply)
        #conn.sendall(Protacal)
    #came out of loop
    conn.close()

# Capture SIGINT for cleanup when the script is aborted
def end_read(signal,frame):
    global continue_reading
    print "Ctrl+C captured, ending read."
    continue_reading = False
    GPIO.cleanup()

def getRFID():
    # Scan for cards
    (status,TagType) = MIFAREReader.MFRC522_Request(MIFAREReader.PICC_REQIDL)
    # If a card is found
    if status == MIFAREReader.MI_OK:
        print "Card detected"

    # Get the UID of the card
    (status,uid) = MIFAREReader.MFRC522_Anticoll()

    # If we have the UID, continue
    if status == MIFAREReader.MI_OK:
        # Print UID
	
	MyKey = str(uid[0]) + str(uid[1]) + str(uid[2]) + str(uid[3])	
        print "Card read UID: "+str(uid[0])+","+str(uid[1])+","+str(uid[2])+","+str(uid[3])
	print("MY KEY:" + MyKey)	

        # This is the default key for authentication
        key = [0xFF,0xFF,0xFF,0xFF,0xFF,0xFF]

        # Select the scanned tag
        MIFAREReader.MFRC522_SelectTag(uid)

        # Authenticate
        status = MIFAREReader.MFRC522_Auth(MIFAREReader.PICC_AUTHENT1A, 8, key, uid)

        # Check if authenticated
        if status == MIFAREReader.MI_OK:
            MIFAREReader.MFRC522_Read(8)
            MIFAREReader.MFRC522_StopCrypto1()
        else:
            print "Authentication error"
    else:
	
	MyKey = ""
    return(MyKey)

def QuiryDataMember(TagID):
    db = MySQLdb.connect(host="localhost",user="root",passwd="root",db="SmartLogger")
    cur = db.cursor()
    cur.execute("SELECT * FROM LoggerMember WHERE TAGID = %s",TagID)

    mytag_id = ""
    myname = ""
    mylastname = ""
    myClassID = ""
    myfaculty = ""
    myLogNum = ""
    for row in cur.fetchall():
	mytag_id =  row[0]
	myname = row[1]
	mylastname = row[2]
	myClassID = row[4]
	myfaculty = row[6]
	myLogNum = row[7]

    cur.close()
    db.close()

    return(mytag_id,myname,mylastname,myClassID,myfaculty,myLogNum)

def ManualOpenLogger():
    db = MySQLdb.connect(host="localhost",user="root",passwd="root",db="SmartLogger")
    cur = db.cursor()
    cur.execute("SELECT * FROM LogNum ")

    OpenAck = ""
    for row in cur.fetchall():
	OpenAck = row[0]
    cur.close()
    db.close()

    return(OpenAck)

def ManualClearLogger():
    db = MySQLdb.connect(host="localhost",user="root",passwd="root",db="SmartLogger")
    cur = db.cursor()
    cur.execute("SELECT * FROM LogNum ")

    sql = "UPDATE LogNum SET LogOpen = (%s) "
    
    try:
	cur.execute(sql,("0"))
	db.commit()
    except:
	db.rollback()

    db.close()

def SaveHistoryMember(tagID,Name,classID,personID,faculTy):
    db = MySQLdb.connect(host="localhost",user="root",passwd="root",db="SmartLogger")
    cur = db.cursor()

    # Prepare SQL query to INSERT a record into the database.
    sql = "INSERT INTO HistoryLooger(TAGID, Name, ClassID, PersonID, Faculty, DateTime) VALUES('%s', '%s', '%s', '%s', '%s', '%s' )" % \
          ((tagID), (Name), (classID), (personID), faculTy, str(datetime.datetime.now()) )

    try:
   	# Execute the SQL command
   	cur.execute(sql)
   	# Commit your changes in the database
   	db.commit()
    except:
   	# Rollback in case there is any error
   	db.rollback()
    # disconnect from server
    db.close()
    ##################


# Hook the SIGINT
signal.signal(signal.SIGINT, end_read)
# Create an object of the class MFRC522
MIFAREReader = MFRC522.MFRC522()
# Welcome message
print "Welcome to the MFRC522 data read example"
print "Press Ctrl-C to stop."

# This loop keeps checking for chips. If one is near it will get the UID and authenticate
while continue_reading:
    
    conn, addr = s.accept()
    print 'Connected with ' + addr[0] + ':' + str(addr[1])     
    #start new thread takes 1st argument as a function name to be run, second is the tuple of arguments to the function.
    start_new_thread(clientthread ,(conn,))

    """
    # Scan for cards    
    (status,TagType) = MIFAREReader.MFRC522_Request(MIFAREReader.PICC_REQIDL)
    # If a card is found
    if status == MIFAREReader.MI_OK:
        print "Card detected"
    
    # Get the UID of the card
    (status,uid) = MIFAREReader.MFRC522_Anticoll()

    # If we have the UID, continue
    if status == MIFAREReader.MI_OK:

        # Print UID
        print "Card read UID: "+str(uid[0])+","+str(uid[1])+","+str(uid[2])+","+str(uid[3])
    
        # This is the default key for authentication
        key = [0xFF,0xFF,0xFF,0xFF,0xFF,0xFF]
        
        # Select the scanned tag
        MIFAREReader.MFRC522_SelectTag(uid)

        # Authenticate
        status = MIFAREReader.MFRC522_Auth(MIFAREReader.PICC_AUTHENT1A, 8, key, uid)

        # Check if authenticated
        if status == MIFAREReader.MI_OK:
            MIFAREReader.MFRC522_Read(8)
            MIFAREReader.MFRC522_StopCrypto1()
        else:
            print "Authentication error"
     """
