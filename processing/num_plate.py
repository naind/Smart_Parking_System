## 번호판 식별

import cv2
import numpy as np
import pytesseract
from PIL import Image

class Recognition:
     def ExtractNumber(self):
          img_Color = cv2.imread('img/test_img.jpg', cv2.IMREAD_COLOR)
          copy_img = img_Color.copy()
          img_Gray = cv2.cvtColor(img_Color, cv2.COLOR_BGR2GRAY)
          img_Blur = cv2.GaussianBlur(img_Gray, (3,3), 0)
          ret, img_Binary = cv2.threshold(img_Blur, 100, 255, cv2.THRESH_BINARY)
          img_Canny = cv2.Canny(img_Binary, 100, 200)
          contours, _ = cv2.findContours(img_Canny, cv2.RETR_TREE,
                                                                                  cv2.CHAIN_APPROX_SIMPLE)

          ##출력##
          #cv2.imshow('Color', img_Color) ## 원본 이미지
          #cv2.imshow('Gray', img_Gray) ## 그레이스케일 이미지
          #cv2.imshow('Gaussian blur', img_Blur) ## 블러링 이미지
          #cv2.imshow('Bianry', img_Binary) ## 쓰레스홀드 이미지
          ##cv2.imshow('Canny', img_Canny) ## 캐니 이미지
          ##img_Contours = cv2.drawContours(img_Color, contours, -1, (0, 0, 255), 1 )
          ##cv2.imshow('Contours', img_Contours) ## 컨투어 이미지

          box1=[] ## 리스트 생성
          f_count=0
          select=0
          plate_width=0


          for i in range(len(contours)):
              cnt = contours[i] ## 픽셀값의 영역의 경계선 정보
              area = cv2.contourArea(cnt) ## 컨투어의 면적
              x,y,w,h = cv2.boundingRect(cnt) ## 컨투어를 둘러싸는 박스
              ##    print(x,y,w,h)
              rect_area = w*h ## area size
              ##    print(rect_area)
              aspect_ratio = float(w)/h # ratio = width/height
              ##    print(aspect_ratio)
              if (aspect_ratio>=0.2) and (aspect_ratio<=1.0) and (rect_area>= 500) and (rect_area<=10000):
                  cv2.rectangle(img_Color, (x, y), (x+w, y+h), (0, 255, 0), 3)  ## 상자를 그려 넣는다.
                  box1.append(cv2.boundingRect(cnt)) ## 컨투어 박스 좌표를 넣는다.

          ##cv2.imshow("result", img_Color)
          print(box1)

          for i in range(len(box1)): ## 버블 정렬 (오름차순)
              for j in range(len(box1)-(i+1)):
                  if box1[j][0]>box1[j+1][0]:
                      temp=box1[j]
                      box1[j]=box1[j+1]
                      box1[j+1]=temp
              
          print(box1) ## 정렬된 box 리스트

          for m in range(len(box1)):  ## 0 1 2 3 4 5 6
              count = 0
              for n in range(m+1, (len(box1)-1)):  ## 1 2 3 4 5
                  delta_x=abs(box1[n+1][0]-box1[m][0])  ## abs 절댓값으로 표현
                  
                  if delta_x > 150:
                      break
                  print(n, m)
                  print(delta_x)
                  delta_y = abs(box1[n+1][1]-box1[m][1])
                  if delta_x ==0:
                      delta_x=1
                  if delta_y ==0:
                      delta_y=1
                  gradient =float(delta_y) /float(delta_x)
                  if gradient<0.25:  ## 구배 (gradient)
                      count=count+1

          #measure number plate size
              if count > f_count:
                  select = m
                  f_count = count;
                  plate_width=delta_x
          cv2.imwrite('snake.jpg',img_Color)


        number_plate=copy_img[box1[select][1]-10:box1[select][3]+box1[select][1]+20,box1[select][0]-10:140+box1[select][0]]
        resize_plate=cv2.resize(number_plate,None,fx=1.8,fy=1.8,interpolation=cv2.INTER_CUBIC+cv2.INTER_LINEAR)
        plate_gray=cv2.cvtColor(resize_plate,cv2.COLOR_BGR2GRAY)
        ret,th_plate = cv2.threshold(plate_gray,110,255,cv2.THRESH_BINARY)

        cv2.imwrite('plate_th.jpg',th_plate)
        kernel = np.ones((3,3),np.uint8)
        er_plate = cv2.erode(th_plate,kernel,iterations=1)
        er_invplate = er_plate
        cv2.imwrite('er_plate.jpg',er_invplate)
        result = pytesseract.image_to_string(Image.open('er_plate.jpg'), lang='kor')
        return(result.replace(" ",""))

recogtest=Recognition()
result=recogtest.ExtractNumber()
print(result)


