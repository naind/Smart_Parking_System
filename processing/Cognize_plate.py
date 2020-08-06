#!/usr/bin/python
#-*- coding:utf-8 -*-

#192.168.0.14
import unicodedata
import MySQLdb
import sys
import picamera
import RPi.GPIO as GPIO
import time
import cv2
import numpy as np
import pytesseract
from PIL import Image
import re
#####  DB  #####
reload(sys)
sys.setdefaultencoding('utf-8')
# Open database connection
db = MySQLdb.connect("192.168.0.9","hhj","123456","phptest", charset='utf8' )
# prepare a cursor object using cursor() method
cursor = db.cursor()
cursor.execute("set charset utf8")
# Prepare SQL query to INSERT a record into the database.
sql = "SELECT * FROM member"
#============================================#

###CDS###
GPIO.setmode(GPIO.BOARD)
#define the pin that goes to the circuit
pin_to_circuit = 7
#=================#
##sorvo Motor##
servoPIN = 11
GPIO.setup(servoPIN, GPIO.OUT)
p = GPIO.PWM(servoPIN, 50) # GPIO 17 for PWM with 50Hz
p.start(12) # Initialization

#==================#

def rc_time (pin_to_circuit):
    count = 0
  
    #Output on the pin for 
    GPIO.setup(pin_to_circuit, GPIO.OUT)
    GPIO.output(pin_to_circuit, GPIO.LOW)
    #time.sleep(0.1)

    #Change the pin back to input
    GPIO.setup(pin_to_circuit, GPIO.IN)
  
    #Count until the pin goes high
    while (GPIO.input(pin_to_circuit) == GPIO.LOW):
        count += 1

    return count

#Catch when script is interupted, cleanup correctly
try:
    # Main loop
    while True:
        #print rc_time(pin_to_circuit)
		
	if rc_time(pin_to_circuit) >= 1000:
		
		
		#time.sleep(2)
		
		print("About to take a picture.")
		with picamera.PiCamera() as camera:
			camera.resolution = (1000,250)
			camera.capture("/home/pi/Downloads/test/image.jpg")
		
		print("Picture taken.")


		class Recognition:
			 def ExtractNumber(self):

				#cap = cv2.VideoCapture(-1)
				#ret,img_Color= cap.read()
				img_Color=cv2.imread('image.jpg',cv2.IMREAD_COLOR)
				copy_img=img_Color.copy()

				img_Gray=cv2.cvtColor(img_Color,cv2.COLOR_BGR2GRAY)
				#cv2.imshow('Gray', img_Gray)

				img_Blur = cv2.GaussianBlur(img_Gray,(3,3),0)
				#cv2.imshow("gaussian blur", img_Blur)

				ret,img_Binary = cv2.threshold(img_Blur, 127, 255, cv2.THRESH_BINARY)
				
				
				img_Canny=cv2.Canny(img_Binary,100,200)
				
				#cv2.imshow('Color', img_Color)
				#cv2.imshow('Gray', img_Gray)
				#cv2.imshow('Gaussian', img_Blur)
				#cv2.waitKey(0)


				#img_Canny=cv2.Canny(img_Binary,100,200)
				#cv2.waitKey(0)
				contours,hierarchy = cv2.findContours(img_Canny, cv2.RETR_TREE, cv2.CHAIN_APPROX_SIMPLE)

				box1=[]
				f_count=0
				select=0
				plate_width=0

				for i in range(len(contours)):
				        cnt=contours[i]
					area = cv2.contourArea(cnt)
					x,y,w,h = cv2.boundingRect(cnt)
					rect_area=w*h  #area size
					aspect_ratio = float(w)/h # ratio = width/height
					if  (aspect_ratio>=0.2)and(aspect_ratio<=1.0)and(rect_area>=100)and(rect_area<=700):
						cv2.rectangle(img_Color,(x,y),(x+w,y+h),(0,255,0),1)
						box1.append(cv2.boundingRect(cnt))


				for i in range(len(box1)): ##Buble Sort on python
					for j in range(len(box1)-(i+1)):
						if box1[j][0]>box1[j+1][0]:
							temp=box1[j]
							box1[j]=box1[j+1]
							box1[j+1]=temp
				#to find number plate measureing length between rectangles
				for m in range(len(box1)):
					count=0
					for n in range(m+1,(len(box1)-1)):
						delta_x=abs(box1[n+1][0]-box1[m][0])
						if delta_x > 150:
							break
						delta_y =abs(box1[n+1][1]-box1[m][1])
						if delta_x ==0:
							delta_x=1
						if delta_y ==0:
							delta_y=1
						gradient =float(delta_y) /float(delta_x)
						if gradient<0.25:
							count=count+1
				#measure number plate size
					if count > f_count:
						select = m
						f_count = count;
						plate_width=delta_x
				cv2.imwrite('snake.jpg',img_Color)


				number_plate=copy_img[box1[select][1]-20:box1[select][3]+box1[select][1]+20,box1[select][0]-20:165+box1[select][0]]
				resize_plate=cv2.resize(number_plate,None,fx=1.8,fy=1.8,interpolation=cv2.INTER_CUBIC+cv2.INTER_LINEAR)
				plate_gray=cv2.cvtColor(resize_plate,cv2.COLOR_BGR2GRAY)
				ret,th_plate = cv2.threshold(plate_gray,127,255,cv2.THRESH_BINARY)
				
				cv2.imwrite('plate_th.jpg',th_plate)
				kernel = np.ones((3,3),np.uint8)
				er_plate = cv2.erode(th_plate,kernel,iterations=1)
				
				#cv2.imshow("Binary", img_Binary) 
				#cv2.imshow("Canny Edge", img_Canny)
				#cv2.imshow('BoxBinary', th_plate)
				#cv2.imshow("erode", er_plate)
				#cv2.waitKey(0)
				
				er_invplate = er_plate
				cv2.imwrite('er_plate.jpg',er_invplate)
				result = pytesseract.image_to_string(Image.open('er_plate.jpg'), lang='kor')
				return(result.replace(" ",""))

		recogtest=Recognition()
		result=recogtest.ExtractNumber()
		result_str = unicodedata.normalize('NFKD', result).encode('ascii','ignore')
		print(result)
		#print(result[2])
		print(result_str)
		

		
		# Execute the SQL command
		cursor.execute(sql)
		# Fetch all the rows in a list of lists.
		results = cursor.fetchall()
		for row in results:
                    num = row[5]
		    num1 = unicodedata.normalize('NFKD', num).encode('ascii','ignore')
                    # Now print fetched result
                    #print "%s" % (num)
                    if num1 == result_str :
                        if num[2] == result[2]:
                            print "great"
                            #####sorvo Motor#####
                            p.ChangeDutyCycle(5.5)
                            time.sleep(5)
                            p.ChangeDutyCycle(12)
                            time.sleep(5)
except KeyboardInterrupt:
    pass
finally:
    p.stop()
    GPIO.cleanup()



