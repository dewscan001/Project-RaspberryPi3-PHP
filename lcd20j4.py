#!/usr/bin/python
#  https://raspberrytips.nl/lcd-scherm-20x4-i2c-raspberry-pi/

import sys
import time
import smbus
import subprocess
import RPi.GPIO as GPIO
import MFRC522
import signal
import os

import hashlib
from pyfingerprint.pyfingerprint import PyFingerprint

import MySQLdb


matrix = [['1','2','3'],
    ['4','5','6'],
    ['7','8','9'],
    ['*', '0', '#']]

spalte = [36, 38, 40]
zeile = [31, 33, 35, 37]
Motor = 32
OrgSensor = 13
CntSensor = 11
Solinois = 26
buzzer = 7
SensorDoor = 15
def KeyPadInit():

    GPIO.setup(Motor,GPIO.OUT)
    GPIO.setup(OrgSensor,GPIO.IN)
    GPIO.setup(CntSensor,GPIO.IN)
    GPIO.setup(Solinois,GPIO.OUT)
    GPIO.setup(buzzer,GPIO.OUT)
    GPIO.setup(SensorDoor,GPIO.IN)
    GPIO.output(buzzer,GPIO.HIGH)
    GPIO.output(Solinois,GPIO.LOW)
    GPIO.setwarnings(False)

    for j in range(3):
    	GPIO.setup(spalte[j], GPIO.OUT)
    	GPIO.output(spalte[j], GPIO.HIGH)

    for i in range(4):
    	GPIO.setup(zeile[i], GPIO.IN, pull_up_down = GPIO.PUD_UP) #defind input pull up 

def keypad():
    while True:
        for j in range(3):
            GPIO.output(spalte[j], 0)
            for i in range(4):
                if GPIO.input(zeile[i]) == 0:
                    benutzerEingabe = matrix[i][j]
                    while GPIO.input(zeile[i]) == 0:

                        pass
                    return benutzerEingabe
            GPIO.output(spalte[j], 1)
    return False

def QuiryDataMember(ID):

    db = MySQLdb.connect(host="localhost",user="root",passwd="1234qwer",db="project")
    cur = db.cursor()
    cur.execute("SELECT * FROM user  WHERE fingerid  = %s",ID)

    myfinger_id = ""
    userid = ""
    myname = ""


    for row in cur.fetchall():

        myfinger_id =  row[0]
        userid = row[1]
        myname = row[2]

    cur.close()
    db.close()

    return(myfinger_id,userid,myname)


def QuiryDataBook(TagID):

    db = MySQLdb.connect(host="localhost",user="root",passwd="1234qwer",db="project")
    cur = db.cursor()
    cur.execute("SELECT * FROM book  WHERE rfid  = %s",TagID)

    rfid = ""
    name = ""
    bstatus = ""

    for row in cur.fetchall():

        rfid =  row[0]
        name = row[1]
 	bstatus = row[4]

    cur.close()
    db.close()

    return(rfid,name,bstatus)


def hitory(Fingerr,KEY):

    KEY=int(KEY)

    db = MySQLdb.connect(host="localhost",user="root",passwd="1234qwer",db="project")
    cur = db.cursor()
    sql = "INSERT INTO memberhistory  (userid,histatus,hidate,hitime) VALUES('%s','%d',curdate(),ADDTIME(curtime(),'7:0:0.0'))" % \
          ((Fingerr),(KEY))

    try:
        # Execute the SQL command
        cur.execute(sql)
        # Commit your changes in the database
        db.commit()

    except:
        # Rollback in case there is any error
        db.rollback()

    cur.execute("SELECT * FROM memberhistory")

    userid = ""
    hidate = ""
    hitime = ""

    for row in cur.fetchall():

        userid =  row[1]
        hidate = row[2]
 	hitime = row[3]

    cur.close()
    db.close()

    return(userid,hidate,hitime)
    #####################


def hitatu(U,D,T,N):

    No=int(N)
    db = MySQLdb.connect(host="localhost",user="root",passwd="1234qwer",db="project")
    cur = db.cursor()

    sql = "UPDATE memberhistory SET bookno = '%d' WHERE userid = '%s' AND hidate = '%s' AND hitime = '%s'" % \
	  ((No),(U),(D),(T))

        # Execute the SQL command
    cur.execute(sql)
        # Commit your changes in the database
    db.commit()
                
    db.close()


def borrowbook(Finger,rfidbook):
	
    db = MySQLdb.connect(host="localhost",user="root",passwd="1234qwer",db="project")
    cur = db.cursor()
    
    sql = "INSERT INTO status  (userid, rfid , sdate, enddate) VALUES('%s', '%s', curdate(), date_add(curdate(),interval 7 day) )" % \
          ((Finger), (rfidbook) )

    try:
        # Execute the SQL command
        cur.execute(sql)
        # Commit your changes in the database
        db.commit()

    except:
        # Rollback in case there is any error
        db.rollback()

    cur.execute("UPDATE book SET bstatus='0' WHERE rfid = %s",rfidbook)
    db.commit()


    # disconnect from server
    db.close()
    ##################


def returnbook(Rfidbook):
    db = MySQLdb.connect(host="localhost",user="root",passwd="1234qwer",db="project")
    cur = db.cursor()

    sql = "UPDATE status SET wstatus = '1',wdate=curdate(),muclt=DATEDIFF(wdate,enddate) WHERE rfid = '%s' AND wstatus = '0'" % \
	  ((Rfidbook))

        # Execute the SQL command
    cur.execute(sql)
        # Commit your changes in the database
    db.commit()


    cur.execute("UPDATE book SET bstatus = '1'  WHERE rfid = %s",Rfidbook)
    db.commit()

    cur.execute("SELECT muclt FROM status  WHERE rfid = %s AND wstatus = '1' AND wdate = curdate()",Rfidbook)

    muclt = ""

    for row in cur.fetchall():
        muclt = row[0]
        sql = "UPDATE status SET muclt=0,mstatus=1  WHERE rfid = '%s' AND wstatus = '1' AND muclt <= 0" % \
              ((Rfidbook))

        # Execute the SQL command
        cur.execute(sql)
        # Commit your changes in the database
        db.commit()


    # disconnect from server
    db.close()
    ##################

def closedoor():
    while(GPIO.input(SensorDoor)==0):
        lcd_string("Close the Door!!",LCD_LINE_4)
        GPIO.output(Motor,GPIO.LOW)
        GPIO.output(buzzer,GPIO.LOW)
        time.sleep(0.5)
        GPIO.output(buzzer,GPIO.HIGH)

def bookdata(keyPadVal):
    keybuff = int(keyPadVal)
    db = MySQLdb.connect(host="localhost",user="root",passwd="1234qwer",db="project")
    cur = db.cursor()
    cur.execute("SELECT * FROM book WHERE no = '%s'",keybuff)

    rfid = ""
    bstatus = ""

    for row in cur.fetchall():

        rfid =  row[1]
        bstatus = row[4]

    cur.close()
    db.close()

    return(rfid,bstatus)

def Scanfinger():

    try:
    	f = PyFingerprint('/dev/ttyUSB0', 9600, 0xFFFFFFFF, 0x00000000) #57600

    	if ( f.verifyPassword() == False ):
        	raise ValueError('The given fingerprint sensor password is wrong!')

    except Exception as e:
    	print('The fingerprint sensor could not be initialized!')
    	print('Exception message: ' + str(e))
    	exit(1)

    ## Gets some sensor information
    print('Currently used templates: ' + str(f.getTemplateCount()) +'/'+ str(f.getStorageCapacity()))

    try:
    	print('Scan Finger...')
	lcd_string("Scan Finger:",LCD_LINE_1)		
    	## Wait that finger is read
    	while ( f.readImage() == False ):
        	pass

    	## Converts read image to characteristics and stores it in charbuffer 1
    	f.convertImage(0x01)

    	## Searchs template
    	result = f.searchTemplate()

    	positionNumber = result[0]
    	accuracyScore = result[1]

    	if ( positionNumber == -1 ):
        	print('No match found!')
		lcd_string('Finger Not Found',LCD_LINE_3)
		lcd_string('ERROR: ' ,LCD_LINE_2)
		time.sleep(5)
        	#exit(0)
    	else:
        	print('Found template at position #' + str(positionNumber))
        	print('The accuracy score is: ' + str(accuracyScore))
		

    	## OPTIONAL stuff
    	##

    	## Loads the found template to charbuffer 1
    	f.loadTemplate(positionNumber, 0x01)

    	## Downloads the characteristics of template loaded in charbuffer 1
    	characterics = str(f.downloadCharacteristics(0x01)).encode('utf-8')

    	## Hashes characteristics of template
    	print('SHA-2 hash of template: ' + hashlib.sha256(characterics).hexdigest())

    except Exception as e:
    	print('Operation failed!')
    	print('Exception message: ' + str(e))
    	#exit(1)

    return(positionNumber)

def cleanAndExit():
    print "Cleaning..."
    GPIO.cleanup()
    print "Bye!"
    sys.exit()

I2C_ADDR  = 0x27#0x3F # I2C device address
LCD_WIDTH = 20   # Maximum characters per line

# Define some device constants
LCD_CHR = 1 # Mode - Sending data
LCD_CMD = 0 # Mode - Sending command

LCD_LINE_1 = 0x80 # LCD RAM address for the 1st line
LCD_LINE_2 = 0xC0 # LCD RAM address for the 2nd line
LCD_LINE_3 = 0x94 # LCD RAM address for the 3rd line
LCD_LINE_4 = 0xD4 # LCD RAM address for the 4th line


LCD_BACKLIGHT  = 0x08  # On 0X08 / Off 0x00
ENABLE = 0b00000100 # Enable bit
E_PULSE = 0.0005
E_DELAY = 0.0005

bus = smbus.SMBus(1) # Rev 2 Pi uses 1

# Capture SIGINT for cleanup when the script is aborted
def end_read(signal,frame):
    global continue_reading
    print "Ctrl+C captured, ending read."
    continue_reading = False
    GPIO.cleanup()
    lcd_byte(0x01,LCD_CMD) #Clear LCD
    gpio.cleanup()

# Hook the SIGINT
signal.signal(signal.SIGINT, end_read)
# Create an object of the class MFRC522
MIFAREReader = MFRC522.MFRC522()

def run_cmd(cmd):
    return subprocess.check_output(cmd, shell=True).decode('utf-8')

def get_my_ipwlan():
    val = run_cmd("/sbin/ifconfig wlan0 | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'")[:-1]
    if val == "":
        val = "No connection!"
    return val

def get_my_ipeth():
    val = run_cmd("/sbin/ifconfig eth0 | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'")[:-1]
    if val == "":
        val = "No connection"
    return val

def lcd_init():
  lcd_byte(0x33,LCD_CMD) # 110011 Initialise
  lcd_byte(0x32,LCD_CMD) # 110010 Initialise
  lcd_byte(0x06,LCD_CMD) # 000110 Cursor move direction
  lcd_byte(0x0C,LCD_CMD) # 001100 Display On,Cursor Off, Blink Off
  lcd_byte(0x28,LCD_CMD) # 101000 Data length, number of lines, font size
  lcd_byte(0x01,LCD_CMD) # 000001 Clear display
  time.sleep(E_DELAY)

def lcd_byte(bits, mode):

  bits_high = mode | (bits & 0xF0) | LCD_BACKLIGHT
  bits_low = mode | ((bits<<4) & 0xF0) | LCD_BACKLIGHT

  bus.write_byte(I2C_ADDR, bits_high)
  lcd_toggle_enable(bits_high)

  bus.write_byte(I2C_ADDR, bits_low)
  lcd_toggle_enable(bits_low)

def lcd_toggle_enable(bits):
  time.sleep(E_DELAY)
  bus.write_byte(I2C_ADDR, (bits | ENABLE))
  time.sleep(E_PULSE)
  bus.write_byte(I2C_ADDR,(bits & ~ENABLE))
  time.sleep(E_DELAY)

def lcd_string(message,line):

  message = message.ljust(LCD_WIDTH," ")

  lcd_byte(line, LCD_CMD)

  for i in range(LCD_WIDTH):
    lcd_byte(ord(message[i]),LCD_CHR)

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

   


def main():
  os.system('sudo pkill -f rfid.py')
  os.system('sudo pkill -f enroll.py')
  lcd_init()
  KeyPadInit()
  stateRun = -1
  GPIO.output(Motor,GPIO.LOW)
  GPIO.output(buzzer,GPIO.HIGH)
  #
  lcd_byte(0x01,LCD_CMD) #Clear LCD  

  while True:

        if(stateRun==-1):
            flagView = False
            flagCnt = False
            key=""
            lcd_byte(0x01,LCD_CMD)
            
            closedoor()
                
            lcd_string("Borrow Select 1 ",LCD_LINE_1)
            lcd_string("Return Select 2 ",LCD_LINE_2)
            lcd_string("View Select 3",LCD_LINE_3)
            lcd_string("Select :",LCD_LINE_4)
            stateRun=0
            
          
	elif(stateRun==0):
            CntKeyPad=0
            keyPadVal = ""
            
            keyBuff = keypad()
            if((keyBuff>='0')and(keyBuff<='9')):
                key=keyBuff
                lcd_string("Select : "+keyBuff,LCD_LINE_4)
            elif(keyBuff=='*'):
                    key=""
                    lcd_string("Select :",LCD_LINE_4)
            elif(keyBuff=='#'):
                if(key=='1'):
                    select=0
                    stateRun=1
                elif(key=='2'):
                    select=1  
                    stateRun=1
                elif(key=='3'):
                    lcd_byte(0x01,LCD_CMD)
                    lcd_string("ViewBook:",LCD_LINE_1)
                    lcd_string("Set.ORG.",LCD_LINE_3)
                    select=3
                    stateRun=12
                else:
                    lcd_string("Select :",LCD_LINE_4)

        elif(stateRun==1):	
		lcd_byte(0x01,LCD_CMD)	
		FingerNum = Scanfinger()
                fingID,uid,uname = QuiryDataMember(FingerNum)
		keyPadVal = ""	
		if(FingerNum == fingID):
                        lcd_byte(0x01,LCD_CMD)
                        stateRun=2
		else:
			lcd_byte(0x01,LCD_CMD)
			stateRun = -1

        elif(stateRun==2):
                lcd_string("Run book:/Set ORG.",LCD_LINE_3)
                lcd_string("ID: "+uid,LCD_LINE_1)
                
                closedoor()
                    
                lcds = ""
                refkey = ""
                i = 60

		if(GPIO.input(OrgSensor)==1):
			GPIO.output(Motor,GPIO.LOW)
			CntKeyPad = 0
                        BookCnt = 0
                        lcd_string("",LCD_LINE_4)
                        if(select==0):
                            lcd_string("Borrow BOOK",LCD_LINE_2)
                            lcd_string("Select book: ",LCD_LINE_3)
                        else:
                            lcd_string("Return BOOK",LCD_LINE_2)
                            lcd_string("",LCD_LINE_3)

                        usid,hidate,hitime = hitory(uid,select)
                        stateRun = 6
                        flagCnt = False
		else:
			GPIO.output(Motor,GPIO.HIGH)

                
	elif(stateRun==3):
		keyBuff = keypad()
		if((keyBuff>='0')and(keyBuff<='9')):
			if(CntKeyPad<2):
				keyPadVal = (keyPadVal+keyBuff)
				lcd_string("Select book:"+keyPadVal,LCD_LINE_3)
				CntKeyPad = CntKeyPad+1
		elif(keyBuff=='*'):	#Clear
                    if(keyPadVal==""):
                        key=""
                        stateRun=-1
                    else:
			keyPadVal = ""
			CntKeyPad = 0
			lcd_string("Select book:",LCD_LINE_3)
		elif(keyBuff=='#'):	#Enter
			if(CntKeyPad==2):
                                
				if((int(keyPadVal)>0) and (int(keyPadVal)<17)):
                                        rf,bs = bookdata(keyPadVal)
                                        if((rf=="")or(bs!=1)):
                                            lcd_string("ERROR:",LCD_LINE_3)
                                            if(rf==""):
                                                lcd_string("Book: Null",LCD_LINE_4)
                                            if(bs==0):
                                                lcd_string("Book: Borrowing ",LCD_LINE_4)
                                            keyPadVal = ""
                                            CntKeyPad = 0
                                            time.sleep(3)
                                            lcd_string("",LCD_LINE_4)
                                            lcd_string("Select book:",LCD_LINE_3)
                                            stateRun=3
                                        else:
                                            stateRun = 4
                                            
				else:
					lcd_string("Select 01-16 only",LCD_LINE_4)
					CntKeyPad = 0
					keyPadVal = ""
					time.sleep(3)
					lcd_string("Select book:   ",LCD_LINE_3)
					lcd_string("",LCD_LINE_4)
			
	elif(stateRun==4):
		lcd_string("Run book:"+str(BookCnt)+"/"+str(keyPadVal),LCD_LINE_3)

                closedoor()
                    
        	if(BookCnt==int(keyPadVal)):
                        lcd_string("Open the Door!!",LCD_LINE_3)
			GPIO.output(Motor,GPIO.LOW)
			GPIO.output(Solinois,GPIO.HIGH)
			time.sleep(1)
			while(GPIO.input(SensorDoor)==1):
                            time.sleep(1)
                            GPIO.output(buzzer,GPIO.LOW)
                            time.sleep(0.5)
                            GPIO.output(buzzer,GPIO.HIGH)
                        lcd_string("",LCD_LINE_4)    
                        if(select==0):
                            stateRun=5
                        else:
                            stateRun=40      
		else:
                        if(GPIO.input(CntSensor)==1):
                            if(flagCnt==False):
				flagCnt = True
				BookCnt = (BookCnt+1)
                        else:
                            flagCnt = False

                        GPIO.output(Motor,GPIO.HIGH)


        elif(stateRun==40):
            if(refkey==""):
                refkey = getRFID()
                lcd_string("Scan RFID: ",LCD_LINE_3)
                if((GPIO.input(SensorDoor)==1)and(select==1)):
                    while(GPIO.input(SensorDoor)==1):
                        time.sleep(1)
                        GPIO.output(buzzer,GPIO.LOW)
                        time.sleep(0.5)
                        GPIO.output(buzzer,GPIO.HIGH)
                        
                                            
            if(refkey!=""):
                GPIO.output(buzzer,GPIO.LOW)
                time.sleep(0.3)
                GPIO.output(buzzer,GPIO.HIGH)
                if(refkey==ref):
                    stateRun=41
                else:
                    refkey=""
                    lcd_string("ERROR: ",LCD_LINE_3)
                    lcd_string("RFID Tag is not same",LCD_LINE_4)
                    time.sleep(5)
                    lcd_string("",LCD_LINE_4)
                                    

	elif(stateRun==41):
            lcd_string("Book No.:"+str(No),LCD_LINE_3)
            lcd_string("Close the Door!!",LCD_LINE_4)

            if((GPIO.input(SensorDoor)==1)and(select==1)):
                lcd_string("Please Wait..",LCD_LINE_4)
                time.sleep(2)
                if(GPIO.input(SensorDoor)==1):
                    GPIO.output(Solinois,GPIO.LOW)
                    lcd_string("Return Success!!",LCD_LINE_4)
                    hitatu(usid,hidate,hitime,No)
                    returnbook(refkey)
                    time.sleep(5)
                    stateRun=13
                else:
                    lcd_string("",LCD_LINE_4)

			
	elif(stateRun==5):
            if(refkey==""):
                refkey = getRFID()
                lcd_string("Scan RFID: ",LCD_LINE_3)
                if(select==1):
                    lcd_string("CountDown "+str(i),LCD_LINE_4)
                    time.sleep(1)
                    if(i==0):
                        stateRun=45
                    i=i-1
                if((GPIO.input(SensorDoor)==1)and(select==0)):
                    while(GPIO.input(SensorDoor)==1):
                        time.sleep(1)
                        GPIO.output(buzzer,GPIO.LOW)
                        time.sleep(0.5)
                        GPIO.output(buzzer,GPIO.HIGH)
                        
            if(refkey!=""):
                No,RFID,Bstatus = QuiryDataBook(refkey)
                lcd_string("",LCD_LINE_4)
                GPIO.output(buzzer,GPIO.LOW)
                time.sleep(0.3)
                GPIO.output(buzzer,GPIO.HIGH)
                if(refkey == RFID):
                    lcd_string("Book No.:"+str(No),LCD_LINE_3)
                    if(select==0):
                        stateRun = 51
                    elif(select==1):
                        ref=refkey
                        keyPadVal = str(No)
                        refkey=""
                        stateRun = 4
                else:
                    refkey = ""
                    stateRun = 5
                    

                        
        elif(stateRun==51):
            if(Bstatus != 0):
                lcd_string("Close the Door!!",LCD_LINE_4)
            else:
                lcd_string("ERROR: ",LCD_LINE_3)
                lcd_string("Book is borrowing",LCD_LINE_4)
                time.sleep(5)
                closedoor()
                key='2'
                stateRun = 12

            if((GPIO.input(SensorDoor)==1)and(select==0)):
                lcd_string("Please Wait..",LCD_LINE_4)
                time.sleep(1)
                if(GPIO.input(SensorDoor)==1):
                    GPIO.output(Solinois,GPIO.LOW)
                    lcd_string("",LCD_LINE_4)
                    if(Bstatus!=0):
                        lcd_string("Borrow Success!!",LCD_LINE_4)
                        hitatu(usid,hidate,hitime,No)
                        borrowbook(uid,refkey)
                        time.sleep(5)
                        stateRun=13
                else:
                    lcd_string("",LCD_LINE_4)


        elif(stateRun==45):

            lcd_string("Cancel Process...",LCD_LINE_3)
            time.sleep(3)
            stateRun=-1

        elif(stateRun==6):
            if(select==1):
                stateRun=5
            elif(select==0):
                keyPadVal=""
                CntKeyPad = 0
                stateRun=3


        elif(stateRun==11):
            
            if(BookCnt==16):
                GPIO.output(Motor,GPIO.LOW)
                lcd_string("Set ORG.",LCD_LINE_3)
                flagView = True
                flagCnt = False
                stateRun=12
            else:
                GPIO.output(Motor,GPIO.HIGH)
                
                closedoor()
                lcd_string("",LCD_LINE_4)
                
                if(GPIO.input(CntSensor)==1):
		    if(flagCnt==False):
			    flagCnt = True
			    BookCnt = (BookCnt+1)
			    GPIO.output(Motor,GPIO.LOW)
			    lcd_string("Book No."+str(BookCnt),LCD_LINE_3)
			    time.sleep(3)
			    GPIO.output(Motor,GPIO.HIGH)	    	    
                else:
		    flagCnt = False
		    lcd_string("",LCD_LINE_3)
                
        elif(stateRun==12):
            if(select!=3):
                    lcd_string("Run book:/Set ORG.",LCD_LINE_3)
                    lcd_string("ID: "+uid,LCD_LINE_1)
            
	    if(GPIO.input(OrgSensor)==1):
                    flagCnt = False
		    GPIO.output(Motor,GPIO.LOW)
		    BookCnt=0
                    time.sleep(3)
                    if(select==3):
                        if(flagView == False):
                            lcd_string("",LCD_LINE_3)
                            stateRun=11
                        else:
                            flagView = False
                            stateRun=-1
                    else:
                        if(key=='1'):
                            lcd_byte(0x01,LCD_CMD)
                            stateRun=2
                        elif (key=='2'):
                            lcd_byte(0x01,LCD_CMD)
                            stateRun=-1
                        
	    else:
                    
                    closedoor()
                    lcd_string("",LCD_LINE_4)
            
                    GPIO.output(Motor,GPIO.HIGH)
                    stateRun=12
                        


	elif(stateRun==13):
            lcd_string("Select 1: Continue",LCD_LINE_2)
            lcd_string("Select 2: Finish",LCD_LINE_3)
            lcd_string("Select : ",LCD_LINE_4)
            stateRun=14


        elif(stateRun==14):
            refkey = ""
            keyBuff = keypad()
            if((keyBuff>='0')and(keyBuff<='9')):
                key=keyBuff
                lcd_string("Select : "+keyBuff,LCD_LINE_4)
            elif(keyBuff=='*'):
                    key=""
                    lcd_string("Select :",LCD_LINE_4)
            elif(keyBuff=='#'):
                if((key=='1')or(key=='2')):
                    lcd_byte(0x01,LCD_CMD)
                    stateRun=12
                else:
                    lcd_string("Select :",LCD_LINE_4)

	

if __name__ == '__main__':

  try:
    main()
  except KeyboardInterrupt:
    cleanAndExit()
    GPIO.cleanup()
    pass
  finally:
    lcd_byte(0x01, LCD_CMD)
