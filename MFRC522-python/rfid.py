
import sys
import time
import smbus
import subprocess
import MFRC522
import signal
import RPi.GPIO as GPIO
import os
from getpass import getpass

import MySQLdb

matrix = [['1','2','3'],
    ['4','5','6'],
    ['7','8','9'],
    ['*', '0', '#']]

spalte = [36, 38, 40]
zeile = [31, 33, 35, 37]
Moter1 = 32
OrgSensor = 13
CntSensor = 11
Solinois = 26
buzzer = 7
SensorDoor = 15

def KeyPadInit():

    GPIO.setwarnings(False)
    GPIO.setup(Moter1,GPIO.OUT)
    GPIO.setup(OrgSensor,GPIO.IN)
    GPIO.setup(CntSensor,GPIO.IN)
    GPIO.setup(Solinois,GPIO.OUT)
    GPIO.setup(SensorDoor,GPIO.IN)
    GPIO.setup(buzzer,GPIO.OUT)
    GPIO.output(buzzer,GPIO.HIGH)
    GPIO.output(Solinois,GPIO.LOW)

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
# Welcome message
print "Welcome to the MFRC522 data read example"
print "Press Ctrl-C to stop."

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

def indata(RFID):
    
    db = MySQLdb.connect(host="localhost",user="root",passwd="1234qwer",db="project")
    cur = db.cursor()

    cur.execute("SELECT * FROM book")

    No = ""
    rfid=""

    for row in cur.fetchall():
        No = row[0]
        rfid = row[1]

        if(rfid==RFID):
            status=1
            break

        elif(rfid!=RFID):
            status=0

    db.close()

    return (No,status)

def closedoor():
    while(GPIO.input(SensorDoor)==0):
        lcd_string("Close the Door!!",LCD_LINE_4)
        GPIO.output(Moter1,GPIO.LOW)
        GPIO.output(buzzer,GPIO.LOW)
        time.sleep(0.5)
        GPIO.output(buzzer,GPIO.HIGH)

def insertbook(refkey,no):
    db = MySQLdb.connect(host="localhost",user="root",passwd="1234qwer",db="project")
    cur = db.cursor()
    sql = "UPDATE book SET rfid = %s, bstatus = 1 WHERE no = %d" % \
        ((refkey),(no))

    try:
        # Execute the SQL command
        cur.execute(sql)
        # Commit your changes in the database
        db.commit()

    except:
        # Rollback in case there is any error
        db.rollback()

    db.close()
    time.sleep(3)

    
def login(username,password):
    if(password == ""):
        print("Username Or Password Invalid!")
        os.system('python lcd20j4.py')
    else:
        db = MySQLdb.connect(host="localhost",user="root",passwd="1234qwer",db="project")
        cur = db.cursor()

        cur.execute("SELECT * FROM admin WHERE username = %s",username)

        passw=""
        a=""

        for row in cur.fetchall():
            passw=row[1]
            a=row[3]

        if(password!=passw):
            print("Username Or Password Invalid!")
            os.system('python lcd20j4.py')

    db.close()
 
def main():
    GPIO.setwarnings(False)
    os.system('sudo pkill -f lcd20j4.py')
    lcd_init()
    KeyPadInit()
    stateRun = 0
    GPIO.output(Moter1,GPIO.LOW)
    GPIO.output(buzzer,GPIO.HIGH)
    lcd_byte(0x01,LCD_CMD) #Clear LCD  

    while True:
            
        
        if(stateRun == 0):
            lcd_byte(0x01,LCD_CMD)
            username = raw_input('USERNAME: ')
            password = getpass('PASSWORD: ')
            login(username,password)
            print("Welcome : "+str(username))
            stateRun=1
            
        elif(stateRun == 1):
            
            lcd_string("ADD BOOK:",LCD_LINE_1)
            db = MySQLdb.connect(host="localhost",user="root",passwd="1234qwer",db="project")
            cur = db.cursor()

            cur.execute('SELECT * FROM book WHERE rfid  = ""')

            no = ""
            
            for row in cur.fetchall():
                no = row[0]
                break

            if(no==""):
                print("Book : Full")
                lcd_string("Book : Full",LCD_LINE_3)
                time.sleep(3)
                stateRun=7

            else:
                print("ADD BOOK:"+str(no)+"/16")
                stateRun=2
                

            db.close()

            

        elif(stateRun==2):
                closedoor()
                lcd_string("",LCD_LINE_4)
                
                
                lcd_string("Run book:"+str(no)+"/Set ORG.",LCD_LINE_2)
                
		if(GPIO.input(OrgSensor)==1):
			GPIO.output(Moter1,GPIO.LOW)
			BookCnt = 0
			flagCnt = False
			stateRun = 3
			time.sleep(3)
		else:
			GPIO.output(Moter1,GPIO.HIGH)

			
	elif(stateRun==3):
		# Run mOter
		closedoor()
		lcd_string("",LCD_LINE_4)
		
		lcd_string("Run book:"+str(BookCnt)+"/"+str(no),LCD_LINE_2)

		if(BookCnt==int(no)):
                        time.sleep(0.2)
                        lcd_string("Open the Door!!",LCD_LINE_3)
			GPIO.output(Moter1,GPIO.LOW)
			GPIO.output(Solinois,GPIO.HIGH)
                        while(GPIO.input(SensorDoor)==1):
                            time.sleep(1)
                            GPIO.output(buzzer,GPIO.LOW)
                            time.sleep(0.5)
                            GPIO.output(buzzer,GPIO.HIGH)
                        lcd_string("",LCD_LINE_4)
			stateRun=4
			
		else:
                    if(GPIO.input(CntSensor)==1):
			if(flagCnt==False):
                                BookCnt = (BookCnt+1)
				flagCnt = True
                    else:
			flagCnt = False

		    GPIO.output(Moter1,GPIO.HIGH)

                    


        elif(stateRun == 4):
            lcd_string("Scan RFID",LCD_LINE_3)
            lcd_string("",LCD_LINE_4)
            refkey = getRFID()
            if(refkey!=""):
                GPIO.output(buzzer,GPIO.LOW)
                time.sleep(0.3)
                GPIO.output(buzzer,GPIO.HIGH)
                No,status = indata(refkey)
                if(status==0):
                    stateRun=5
                else:
                    lcd_string("ERROR: ",LCD_LINE_3)
                    lcd_string("This is Book No. "+str(No),LCD_LINE_4)
                    time.sleep(5)
                

            if(GPIO.input(SensorDoor)==1):
                lcd_string("Please Wait..",LCD_LINE_4)
                time.sleep(2)
                if(GPIO.input(SensorDoor)==1):
                        lcd_string("Cancel Process...",LCD_LINE_3)
                        lcd_string("",LCD_LINE_4)
                        time.sleep(3)
                        GPIO.output(Solinois,GPIO.LOW)
                        stateRun=6
                else:
                        lcd_string("",LCD_LINE_4)
            

        elif(stateRun == 5):
            lcd_string("Close the Door!!",LCD_LINE_4)
            lcd_string("ADD Book No.: "+str(no),LCD_LINE_3)
            if(GPIO.input(SensorDoor)==1):
                lcd_string("Please Wait..",LCD_LINE_4)
                time.sleep(2)
                if(GPIO.input(SensorDoor)==1):
                        GPIO.output(Solinois,GPIO.LOW)
                        insertbook(refkey,no)
                        lcd_string("Add Book Success!!",LCD_LINE_4)
                        time.sleep(5)
                        stateRun=6
                else:
                        lcd_string("",LCD_LINE_4)
            

        elif(stateRun == 6):
            lcd_string("Select : ",LCD_LINE_4)
            lcd_string("Select 1: Exit",LCD_LINE_2)
            lcd_string("Select 2: Continue",LCD_LINE_3)
            stateRun=61

        elif(stateRun == 61):
            keyBuff = keypad()
            if((keyBuff>='0')and(keyBuff<='9')):
                key=keyBuff
                lcd_string("Select : "+keyBuff,LCD_LINE_4)
            elif(keyBuff=='*'):
                    key=""
                    lcd_string("Select :",LCD_LINE_4)
            elif(keyBuff=='#'):
                if(key=='1'):
                    stateRun=7
                    lcd_byte(0x01,LCD_CMD)
                elif(key=='2'):
                    stateRun=1
                    lcd_byte(0x01,LCD_CMD)
                else:
                    lcd_string("Select :",LCD_LINE_4)


        elif(stateRun == 7):
            lcd_string("Run book:/Set ORG.",LCD_LINE_3)
            if(GPIO.input(OrgSensor)==1):
			GPIO.output(Moter1,GPIO.LOW)
			time.sleep(3)
			os.system('python lcd20j4.py')
			
            else:
			GPIO.output(Moter1,GPIO.HIGH)

            
            
if __name__ == '__main__':

  try:
    main()
  except KeyboardInterrupt:
    cleanAndExit()
    GPIO.cleanup()
    pass
  finally:
    lcd_byte(0x01, LCD_CMD)
