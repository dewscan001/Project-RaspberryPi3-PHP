#!/usr/bin/env python
# -*- coding: utf-8 -*-

"""
PyFingerprint
Copyright (C) 2015 Bastian Raschke <bastian.raschke@posteo.de>
All rights reserved.

"""

import sys
from getpass import getpass

import MySQLdb
import time
import os
import hashlib
from pyfingerprint.pyfingerprint import PyFingerprint
import string


## Deletes a finger from sensor
##


## Tries to initialize the sensor
def delete(fingernumber):
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

## Tries to delete the template of the finger
    try:
        positionNumber = int(fingernumber)

        if ( f.deleteTemplate(positionNumber) == True ):
            print('Template deleted!')

    except Exception as e:
        print('Operation failed!')
        print('Exception message: ' + str(e))
        exit(1)

def database(finger):
    db = MySQLdb.connect(host="localhost",user="root",passwd="1234qwer",db="project")
    cur = db.cursor()

    cur.execute("SELECT * FROM user WHERE fingerid  = %s",finger)

    no = ""
    userid=""

    for row in cur.fetchall():
        no =  row[0]
        userid=row[1]
            
        cur.execute("DELETE FROM user WHERE userid  = %s",userid)
        db.commit()

        db.close()


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
    positionNumber=""
    x=""
    y="y"
    n="n"
    username=""
    password=""
    statusRun=0
    while True:    
            
        if(statusRun==0):
            username = raw_input('USERNAME: ')
            password = getpass('PASSWORD: ')
            login(username,password)
            print("Welcome : "+str(username))
            statusRun=1
            
        elif(statusRun==1):
            
            db = MySQLdb.connect(host="localhost",user="root",passwd="1234qwer",db="project")
            cur = db.cursor()

            cur.execute('SELECT * FROM user WHERE userid  = ""')

            no = ""
            
            for row in cur.fetchall():
                no = row[0]
                database(no)
                delete(no)
                print("DELETE User No.:"+str(no))

            if(no==""):
                print("delete template: null")
                os.system('python lcd20j4.py')
            
            statusRun=2
            
        elif(statusRun==2):
            os.system('python lcd20j4.py')
                

if __name__ == '__main__':

  try:
    main()
  except KeyboardInterrupt:
    cleanAndExit()
    GPIO.cleanup()
    pass
