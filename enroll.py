#!/usr/bin/env python
# -*- coding: utf-8 -*-

"""
PyFingerprint
Copyright (C) 2015 Bastian Raschke <bastian.raschke@posteo.de>
All rights reserved.

"""
import sys
import smbus
import subprocess
import RPi.GPIO as GPIO
import signal
import signal
from getpass import getpass
import os

import MySQLdb
import time
import hashlib
from pyfingerprint.pyfingerprint import PyFingerprint


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


## Enrolls new finger
##
def fingerscan():
    lcd_byte(0x01,LCD_CMD)
## Tries to initialize the sensor
    try:
        f = PyFingerprint('/dev/ttyUSB0', 9600, 0xFFFFFFFF, 0x00000000)

        if ( f.verifyPassword() == False ):
            raise ValueError('The given fingerprint sensor password is wrong!')

    except Exception as e:
        print('The fingerprint sensor could not be initialized!')
        print('Exception message: ' + str(e))
        exit(1)

## Gets some sensor information
    print('Currently used templates: ' + str(f.getTemplateCount()) +'/'+ str(f.getStorageCapacity()))

## Tries to enroll new finger
    try:
        print('Scan Finger...')
        lcd_string("Scan Finger:",LCD_LINE_1)
    ## Wait that finger is read
        while ( f.readImage() == False ):
            pass

    ## Converts read image to characteristics and stores it in charbuffer 1
        f.convertImage(0x01)

    ## Checks if finger is already enrolled
        result = f.searchTemplate()
        positionNumber = result[0]

        if ( positionNumber >= 0 ):
            print('Template already exists at position #' + str(positionNumber))
            lcd_string("Finger ID #"+str(positionNumber),LCD_LINE_2)
            time.sleep(5)
            fingerscan()

        else:

    #    time.sleep(1)
        
    ## Wait that finger is read again
    #    while ( f.readImage() == False ):
    #        pass

    ## Converts read image to characteristics and stores it in charbuffer 2
    #    f.convertImage(0x02)

    ## Compares the charbuffers
    #    if ( f.compareCharacteristics() == 0 ):
    #        lcd_string("ERROR!",LCD_LINE_2)
    #        time.sleep(3)
    #        fingerscan()
        
    ## Creates a template
            f.createTemplate()

    ## Saves template at new position number
            positionNumber = f.storeTemplate()
            print('Finger enrolled successfully!')
            lcd_string("successfully!",LCD_LINE_2)
            print('New template position #' + str(positionNumber))

    except Exception as e:
        print('Operation failed!')
        print('Exception message: ' + str(e))
        pass

    return(positionNumber)

def database(positionNumber):
    db = MySQLdb.connect(host="localhost",user="root",passwd="1234qwer",db="project")
    cur = db.cursor()
    
    cur.execute("SELECT * FROM user WHERE fingerid  = %s",positionNumber)

    no = ""

    for row in cur.fetchall():
        no =  row[0]

    if((positionNumber!=no)and(positionNumber!=-1)):        
        sql = "INSERT INTO user (fingerid) VALUES ('%d')" % \
               (positionNumber)

        try:
        # Execute the SQL command
            cur.execute(sql)
        # Commit your changes in the database
            db.commit()

        except:
        # Rollback in case there is any error
            db.rollback()

        cur.execute("SELECT * FROM user WHERE fingerid  = %s",positionNumber)

        no = ""

        for row in cur.fetchall():
            no =  row[0]

        db.close()
        lcd_string("ADD User No. :"+str(positionNumber),LCD_LINE_3)
        lcd_string("",LCD_LINE_4)
        time.sleep(3)

    else:
        lcd_string("ERROR",LCD_LINE_2)
        lcd_string("",LCD_LINE_4)
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
    os.system('sudo pkill -f lcd20j4.py')
    positionNumber=""
    x=""
    y="y"
    n="n"
    username=""
    password=""
    statusRun=0
    while True:
        
        if(statusRun==0):
            lcd_byte(0x01,LCD_CMD)
            username = raw_input('USERNAME: ')
            password = getpass('PASSWORD: ')
            login(username,password)
            print("Welcome : "+str(username))
            statusRun=1
            
        elif(statusRun==1):
            positionNumber=fingerscan()
            statusRun=2
            
            
        elif(statusRun==2):
            database(positionNumber)
            x = input("exit? (y/n):")
            if(x==y):
                os.system('python lcd20j4.py')
            elif(x==n):
                statusRun=1
                lcd_byte(0x01,LCD_CMD) #Clear LCD
                



if __name__ == '__main__':

  try:
    main()
  except KeyboardInterrupt:
    cleanAndExit()
    GPIO.cleanup()
    pass
  finally:
    lcd_byte(0x01, LCD_CMD)

        
    
