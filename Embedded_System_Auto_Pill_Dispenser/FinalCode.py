########################################################################
#	Authors: Min Zheng (mz474), Qikun Wang (qw99)
#
#	Date:    12/8/2016
#
#	Lab:     Project
#
#	Brief:   Final version. GUI; Pill dispensing; Combined with one IR sensor.
#
########################################################################

import pygame
from pygame.locals import *

import os

import time

import subprocess

import RPi.GPIO as GPIO

#--------------Setup the servo----------------------------
#variables of PWM waves


lowTime=20#ms
#first servo p1
pos1=0.25#ms
pos1Dc = pos1/(pos1+lowTime)*100
pos1Freq=1000/(pos1+lowTime)
#second servo p2
pos2 = 2.25 #ms
pos2Dc = pos2/(pos2+lowTime)*100
pos2Freq=1000/(pos2+lowTime)

# Connect the RPi Broadcom

GPIO.setmode(GPIO.BCM)

# Setup pin # as output
servo1 = 4
servo2 = 13

#	GPIO setup
GPIO.setup(servo1,GPIO.OUT)
GPIO.setup(servo2,GPIO.OUT)
GPIO.setup(12, GPIO.IN, pull_up_down=GPIO.PUD_UP)  #for IR 1

#---------------put on piTFT-------------------------------------
#os.putenv('SDL_VIDEODRIVER','fbcon')
#os.putenv('SDL_FBDEV','/dev/fb1')
#os.putenv('SDL_MOUSEDRV','TSLIB')
#os.putenv('SDL_MOUSEDEV','/dev/input/touchscreen')

#----------------pygame start----------------------------------------

pygame.init() #start the pygame

size = width, height = 240,320  #size of PiTFT screen

screen = pygame.display.set_mode(size) #set a display canvas

#pygame.mouse.set_visible(False)


#define some color
yellow = 255,255,0
red = 255,0,0
green = 0,255,0
black = 0,0,0 #set up color
white = 255,255,255
blue = 0,0,255
my_font = pygame.font.Font(None,25)

#------------------self define function---------------
class Background(pygame.sprite.Sprite):
# Reference: http://stackoverflow.com/questions/28005641/how-to-add-a-background-image-into-pygame
	def __init__(self, image_file, location):
		pygame.sprite.Sprite.__init__(self)  #call Sprite initializer
		self.image = pygame.image.load(image_file)
		self.rect = self.image.get_rect()
		self.rect.left, self.rect.top = location

class button:
    def __init__(self,color,myText,textPos):
        self.color = color
        self.myText = myText
        self.textPos = textPos
# draw the button given color, text, location
    def drawButton(self):
        text_surface = my_font.render(self.myText,True,self.color)
        rect = text_surface.get_rect(center = self.textPos)
        screen.blit(text_surface,rect)
        pygame.display.flip()


def clearScreen():
# clear current screen no matter what
	pygame.draw.rect(screen,black,(0,0,240,320))

# p1
def p1welcome(lastTime):
    # display clock, let user select pill number, or view schedule
	menu = 1
	curTime = str(time.ctime()[11:19])  # current hour:minute:second
	# for updating the clock every second
	if curTime != lastTime:
		pygame.draw.rect(screen,black,(70,20,100,40))  # cover the old time

    #display the buttons and text
	p1s = {curTime:(120,40),'WELCOME':(120,80),'select pill to begin':(120,120),'pill 1':(60,200),'pill 2':(180,200),'show schedule':(120,260)}
	color = white
	for myText,textPos in p1s.items():
		testButton = button(color,myText,textPos)
		testButton.drawButton()
    # detect button press and store pill selection
	pillNum = ""  # pill1 or pill2
	for event in pygame.event.get():
		if(event.type is MOUSEBUTTONDOWN):
			pos = pygame.mouse.get_pos()
			#print(pos)
		elif(event.type is MOUSEBUTTONUP):
			pos = pygame.mouse.get_pos()
			x,y = pos
			
			if y > 180 and y < 220 and x < 120:  # pill 1
				pillNum = 1
			elif y > 180 and y < 220 and x > 120:  # pill 2
				pillNum = 2
			elif y > 240 and y < 280 and x > 80 and x < 160:
				menu = 5  # view schedule
	
	# if alarm = 1, clearscreen to display alarm
	global alarm
	if alarm == 1:
		menu = 7
		clearScreen()


	return menu,pillNum,curTime


# p2
def p2disp(pillNum):
	menu = 2
	tSelect = ''  # time1 or time2
    # display text
	p2s = {'pill'+str(pillNum):(120,30),'time1':(120,100),'time2':(120,160),'done':(120,240)}
	drawStuff(p2s,yellow)
    # detect the button pressed
	for event in pygame.event.get():
		if(event.type is MOUSEBUTTONDOWN):
			pos = pygame.mouse.get_pos()
            #print(pos)
		elif(event.type is MOUSEBUTTONUP):
			pos = pygame.mouse.get_pos()
			x,y = pos
            # setup button range and location
			dx = 30
			dy = 20
			t1x = p2s['time1'][0]
			t1y = p2s['time1'][1]
			t2x = p2s['time2'][0]
			t2y = p2s['time2'][1]
			doneX = p2s['done'][0]
			doneY = p2s['done'][1]

			if y > t1y-dy and y < t1y+dy and x > t1x-dx and  x < t1x+dx:
				tSelect = 1
			elif y > t2y-dy and y < t2y+dy and x > t2x-dx and  x < t2x+dx:
				tSelect = 2
			elif y > doneY-dy and y < doneY+dy and x > doneX-dx and  x < doneX+dx:
				menu = 1

			clearScreen()
	return tSelect,menu

#p3
def drawNumPad():
    # draw all buttons 0-9 del done
	numButtons = {'1':(40,110),'2':(120,110),'3':(200,110),'4':(40,170),'5':(120,170),'6':(200,170),'7':(40,230),'8':(120,230),'9':(200,230),'0':(40,290),'del':(120,290),'done':(200,290)}
	color = yellow
	for myText,textPos in numButtons.items():
		testButton = button(color,myText,textPos)
		testButton.drawButton()

def drawStuff(drawList,color):
	# given a list of text and location, and color, draw buttons
	for myText,textPos in drawList.items():
		testButton = button(color,myText,textPos)
		testButton.drawButton()

def addScreen(screenNum,intIn):
    # add the user input number to the display screen
	locs = [(40,40),(90,40),(150,40),(200,40)]
	
	if len(screenNum)<5:  # including the ":"
		nextInx = len(screenNum)-1
		loc = locs[nextInx]  # get the coor info for the new input number
		screenNum.append((str(intIn),loc))    # append to the dictionary
		# print the new display window
		color = green
		for myText,textPos in screenNum:
			testButton = button(color,myText,textPos)
			testButton.drawButton()

def delScreen(screenNum):
    # delete the last digit and update the display
	if len(screenNum)>1:
		screenNum.pop()
	# print the new display window
		color = green
	#pygame.draw.rect(screen ,black ,(x,y,width,height)
		pygame.draw.rect(screen,black,(0,0,240,70))


		for myText,textPos in screenNum:
			testButton = button(color,myText,textPos)
			testButton.drawButton()


def p3detect(screenNum,dones,intIn):
    # detect which button is pressed on p3
	hour = ''
	mins = ''
	drawNumPad()
	newdones = dones
	for event in pygame.event.get():
		if(event.type is MOUSEBUTTONDOWN):
			pos = pygame.mouse.get_pos()
			
		elif(event.type is MOUSEBUTTONUP):
			pos = pygame.mouse.get_pos()
			x,y = pos
			coverError()
			cmd = ""
			if y>80 and y < 140:  # row 1
				if x < 80:
					intIn = 1
				elif x > 80 and x < 160:
					intIn = 2
				else:
					intIn = 3

			elif y>140 and y < 200:  # row 2

				if x < 80:
					intIn = 4
				elif x > 80 and x < 160:
					intIn = 5
				else:
					intIn = 6

			elif y>200 and y < 260:  # row 3

				if x < 80:
					intIn = 7
				elif x > 80 and x < 160:
					intIn = 8
				else:
					intIn = 9

			elif y>260 and y < 320 and x<80:  # row 4
				if x < 80:
					intIn = 0
			elif x > 80 and x < 160 and y>260 and y < 320:
				cmd = 'del'
				newdones = 0
			elif x > 160 and y>260 and y < 320:
					cmd = "done"

			if cmd == 'del':
				delScreen(screenNum)

			elif cmd == "done":
				if dones == 0:
					hour,mins = storeTime(screenNum)
					if hour == '':   # if errorMsg displayed once
						newdones = dones + 1
						#print(screenNum)
				elif dones ==1:
					newdones = dones + 1
					hour = ''
					mins = ''        
			else:
				addScreen(screenNum,intIn)


	return hour,mins,newdones

def dispError2mins():
    # display error when the user's input is within 2mins
	color = red
	myText = "enter 2 mins later than now"
	textPos = (120,65)
	errorMsg = button(color,myText,textPos)
	errorMsg.drawButton()

def dispError():
    # display error when the time format is incorrect
	color = red
	myText = "invalid, hit done to quit"
	textPos = (120,65)
	errorMsg = button(color,myText,textPos)
	errorMsg.drawButton()
    
def coverError():
    # hide the error msg
	pygame.draw.rect(screen,black,(0,50,240,50))

def storeTime(screenNum):
# when "done" entered, check error and store/ convert time input
# check digit number
	isError = 1
	if len(screenNum) == 5:
# convert to hour and minute
		mHour = 10*int(screenNum[1][0]) + int(screenNum[2][0])
# screenNum[1]=('1', (40, 40)); screenNum[1][0] = '1'
		mMinute = 10*int(screenNum[3][0]) + int(screenNum[4][0])
# check number
		if mHour < 24 and mMinute < 60:  # time input valid
			isError = 0
			
			#check for +2 mins
			timedata = list(time.localtime())
			lchour = timedata[3]
			lcmin = timedata[4]

			if lcmin == 59 and lchour+1 == mHour:
				if mMinute == 0:
					isError = 2
			elif mMinute < lcmin+1:				
				isError = 2
		
# if error, display error msg
	if isError == 1:
		dispError()
		return '',''
	elif isError == 2:
		dispError2mins()
		return '',''
		
	else:
 # if no error, store the time
 #print(str(mHour)+":"+ str(mMinute))
		clearScreen()
#return (str(mHour)+":"+ str(mMinute))
		return mHour,mMinute

def p4Pills(timeIn,pillNum,numIn,dones2):
    # let user input how many pills to take
	menu = 4
	newdones2 = dones2  # for hit dones to quit function
	ifquit = 0
	rectX = 100
	rectY = 150
	rectH = 40
	rectW = 40
	triH = 15
	doneX = 120
	doneY = 230
	p4s = {'pill'+str(pillNum):(120,30),'please select # of pills':(120,60),'to take at'+str(timeIn):(120,90),str(numIn):(120,170),'done':(doneX,doneY)}
	drawStuff(p4s,yellow)
    # draw the triangles for + and -
	pygame.draw.polygon(screen,white,[[rectX, rectY],[rectX+rectW, rectY],[rectX+rectW,rectY+rectH],[rectX, rectY+rectH]],1)
	pygame.draw.polygon(screen,white,[[rectX, rectY],[rectX+rectW/2,rectY-triH],[rectX+rectW, rectY]],0)
	pygame.draw.polygon(screen,white,[[rectX, rectY+rectH],[rectX+rectW/2,rectY+triH+rectH],[rectX+rectW, rectY+rectH]],0)
# detect
	for event in pygame.event.get():
		if(event.type is MOUSEBUTTONUP):
			pos = pygame.mouse.get_pos()
			x,y = pos
			dx = 40
			dy = 20
			coverError2()
			if y > rectY-triH and y < rectY and x > rectX and  x < rectX+rectW:
				if numIn < 5:
					numIn += 1
					newdones2 = 0
			elif y > rectY+rectH and y < rectY+triH+rectH and x > rectX and  x < rectX+rectW:
				if numIn > 0:
					numIn -= 1
					newdones2 = 0
			elif y > doneY-dy and y < doneY+dy and x > doneX - dx and x < doneX +dx:
				if numIn == 0:
					if dones2 == 0:
						p4zeroPill()
						newdones2 = dones2 + 1
					elif dones2 == 1:
						newdones2 = dones2 + 1
				else:
					menu = 2
					clearScreen()

			pygame.draw.rect(screen,black,(rectX,rectY,rectW,rectH))

	return numIn,menu,newdones2
	
def coverError2():
    # cover the error msg on p4
	pygame.draw.rect(screen,black,(0,260,240,60))

def p4zeroPill():
    # error msg if user input 0 pills on p4
	texts = {'0 pill input':(120,270),'hit done again to quit':(120,290)}
	drawStuff(texts,red)

def pillInfo(pNum,tNum):
    # converts the current schedule to string
	myStr = ''
	hour = setA[pNum-1][0][tNum-1][0]
	Num2take = setA[pNum-1][1][tNum-1]
	timeStr = str(setA[pNum-1][0][tNum-1][0]) + ':' + str(setA[pNum-1][0][tNum-1][1])
	if (hour) != '':  # time not empty
		if Num2take != '': # Num 2 take not empty
			myStr = 'take ' + str(Num2take) + ' at ' + timeStr
		else:
			myStr = ''
	else:
		myStr = ''
	return myStr


def p5dispSch():
    # display the current schedule
	menu = 5
	cY = 260  # y position of confirm button
    # needs to separate because cannot have same key values/ same string
	p5s1 = {'pill 1':(120,40),str(pillInfo(1,1)):(120,70)}
	p5s2 = {str(pillInfo(1,2)):(120,100),'----------------':(120,140),'pill 2':(120,160)}
	p5s3 = {str(pillInfo(2,1)):(120,190)}
	p5s4 = {str(pillInfo(2,2)):(120,220),'confirm':(120,cY)}
	drawStuff(p5s1,yellow)
	drawStuff(p5s2,yellow)
	drawStuff(p5s3,yellow)
	drawStuff(p5s4,yellow)
    # detect if goes back to menu1
	for event in pygame.event.get():
		if(event.type is MOUSEBUTTONUP):
			pos = pygame.mouse.get_pos()
			x,y = pos
			dx = 40
			dy = 20
			if y > cY-dy and y < cY+dy and x > 120-dx and  x < 120+dx:
				menu = 1
	return menu

def p7alarm():
    # display alarm msg when the pills under dose
	menu = 7
	p7s1 = {'Pill stuck':(120,40),'you are under dose':(120,60),'call 3235282800 for help':(120,80)}
	
	cY = 220
	p7s2 = {'I know':(120,cY)}
	drawStuff(p7s1,red)
	drawStuff(p7s2,green)
	
	for event in pygame.event.get():
		if(event.type is MOUSEBUTTONUP):
			pos = pygame.mouse.get_pos()
			x,y = pos
			dx = 40
			dy = 20
			if y > cY-dy and y < cY+dy and x > 120-dx and  x < 120+dx:
				menu = 1
                # iknow for going back to main/don't show again
				global iknow
				iknow = 1
				global alarm
				alarm = 0
	return menu


def dispenseInfo(setA):
	myStr = ''
	# pill 1
	hour1 = setA[0][0][0][0]
	min1 = setA[0][0][0][1]
	Npill1 = setA[0][1][0]
	
	hour2 = setA[0][0][1][0]
	min2 = setA[0][0][1][1]
	Npill2 = setA[0][1][1]
	# pill 2
	hour3 = setA[1][0][0][0]
	min3 = setA[1][0][0][1]
	Npill3 = setA[1][1][0]
	hour4 = setA[1][0][1][0]
	min4 = setA[1][0][1][1]
	Npill4 = setA[1][1][1]
	return hour1,min1,Npill1,hour2,min2,Npill2,hour3,min3,Npill3,hour4,min4,Npill4

#--------------GPIO functions--------------------

def dispense(Npill,pillN,r):
	# input: which pill, how many, r stops from dispensing forever in the same minute
	if r == 0:
		if pillN == 1:
			pinNum = 13
		elif pillN == 2:
			pinNum = 4
			# dispense Npill times of pillN
			
		while Npill != 0:
			if pillN == 1:
				#up
				p1 = GPIO.PWM(pinNum,pos2Freq)
				p1.start(pos2Dc)
				
				time.sleep(1)
				
				#down
				p1.ChangeDutyCycle(pos1Dc)
				p1.ChangeFrequency(pos1Freq)
				
				time.sleep(1)
				
				#up
				p1.ChangeDutyCycle(pos2Dc)
				p1.ChangeFrequency(pos2Freq)
				
				time.sleep(1)
				
				p1.stop()
			elif pillN ==2:
				#up
				p2 = GPIO.PWM(pinNum,pos1Freq)
				p2.start(pos1Dc)
				time.sleep(1)
				
				#down
				
				p2.ChangeDutyCycle(pos2Dc)
				p2.ChangeFrequency(pos2Freq)
				time.sleep(1)
				
				#up
				
				p2.ChangeDutyCycle(pos1Dc)
				p2.ChangeFrequency(pos1Freq)
				time.sleep(1)
				
				p2.stop()
			
          
			Npill = Npill-1
		r = 1
	return r

def checkClock(setA,setB):
	# detect if any one or more time is matched with current hour:min, if so, dispense pills accordingly
	# then update the setB (r array for 4, prevent running forever at the same minute)
	#print(setA)

	global h
	global alarm 
	global iknow

    # pill 1
	hour1 = setA[0][0][0][0]
	min1 = setA[0][0][0][1]
	Npill1 = setA[0][1][0]
	hour2 = setA[0][0][1][0]
	min2 = setA[0][0][1][1]
	Npill2 = setA[0][1][1]

	# pill 2
	hour3 = setA[1][0][0][0]
	min3 = setA[1][0][0][1]
	Npill3 = setA[1][1][0]
	
	hour4 = setA[1][0][1][0]
	min4 = setA[1][0][1][1]
	Npill4 = setA[1][1][1]

	# put into array for convenience in operating
	hours = [hour1,hour2,hour3,hour4]
	mins = [min1,min2,min3,min4]
	Npills = [Npill1,Npill2,Npill3,Npill4]
	pillNs = [1,1,2,2]
	dispList = []
    # check p1t1, p1t2,p2t1,p2t2 if any time match
	for i in range(0,4):
		timedata = list(time.localtime())
		lchour = timedata[3]  # local time
		lcminute = timedata[4]
		timenow = time.ctime()
		hourInput = hours[i]  # input time
		minuteInput = mins[i]

		if lchour == hourInput and lcminute == minuteInput:
			dispList.append(i)  # add to a list if time match

			
	while len(dispList) != 0:  # dispense the pills that is in the dispList
		j = dispList.pop()
		setB[j] = dispense(Npills[j],pillNs[j],setB[j])
		print('h',h)
		print('Npill',Npills[j])
		time.sleep(1)
        # check for under dose
		if h < Npills[j]:
			if iknow == 0:
				global alarm
				alarm = 1
		
	return setB


def ifToday(Rtimedata,setB):  # Rtimedata is the last "today"\
	Ltimedata = list(time.localtime())
	lday = Ltimedata[2]   # LOCAL time data
	lmonth = Ltimedata[1]
	lyear = Ltimedata[0]
	
	rday = Rtimedata[2]   # last ran time data
	rmonth = Rtimedata[1]
	ryear = Rtimedata[0]


	if lday == rday and lmonth == rmonth and lyear == ryear:  # if still on the same day
		pass
	else:  # day changed
		Rtimedata = list(time.localtime())  # set current time to Rtimedata
		setB = [0,0,0,0] #reset r array
	return Rtimedata,setB


# MAIN LOOP

#initialization
dones = 0  # for p3, hit done twice to go back
dones2 = 0   #for p4

menu = 1
#setA = [[[['',''],['','']],[[''],['']]],[[['',''],['','']],[[''],['']]]]  # the biggest set
#setA = [[[['p1hour1','p1min1'],['p2hour2','p2min2']],[['p1t1#'],['p1t2#']]],[[['p2hour1','p2min1'],['p2hour2','p2min2']],[['p2t1#'],['p2t2#']]]]
p1set = [[['',''],['','']],[[''],['']]]
p2set = [[['',''],['','']],[[''],['']]]
setA = [p1set,p2set]

setB = [0,0,0,0]  # p1t1, p1t2, p2t1, p2t2

numIn = 0 #for now, will need a table
screenNum = [(":",(120,40))]
BackGround = Background('p1.png', [0,0])
screen.blit(BackGround.image, BackGround.rect)
cBGM = 0  # change background or not


Rtimedata = list(time.localtime()) 
lastTime = str(time.ctime()[11:19])

#IR stuff
global ikonw
iknow = 0

global alarm
alarm = 0
global h
h = 0
def GPIOIR1_callback(channel):

	global h
	h = h +1

#add interrupt for IR1
GPIO.add_event_detect(12, GPIO.FALLING, callback=GPIOIR1_callback,bouncetime=200)



def changeBGM(cBGM,pageNum):
    # for changing the background
	if cBGM == 0:
			clearScreen()
			BackGround = Background('p' + str(pageNum)+'.png', [0,0])
			screen.blit(BackGround.image, BackGround.rect)
			cBGM = 1
	return cBGM

def nextPage(curP,varName,cBGM):
    # for going to the next page
	if varName != "":
			curP = curP+1
			cBGM = 0

	return curP,cBGM


# MAIN LOOP
while True:
	
	Rtimedata,setB = ifToday(Rtimedata,setB)  # check/update for day change
	cBGM = changeBGM(cBGM,menu)  # change background

	if menu == 1:  # welcome, view schedule
		
		menu,pillNum,lastTime = p1welcome(lastTime)
		
		menu,cBGM = nextPage(menu,pillNum,cBGM)

		checkClock(setA,setB)
		
		if menu == 5:  # if view schedule
			clearScreen()
			cBGM = 0 # need to change background
		

	elif menu == 2:  # select pill
		tSelect,menu = p2disp(pillNum)

		menu,cBGM = nextPage(menu,tSelect,cBGM)
		if menu != 2:
			cBGM = 0

	elif menu == 3:  # input time
		mHour = ''
		mMin = ''

		mHour,mMin,dones = p3detect(screenNum,dones,intIn = "")
		setA[pillNum-1][0][tSelect-1][0] = mHour
		setA[pillNum-1][0][tSelect-1][1] = mMin

        # for hitting done twice to quit
		if dones == 2:
			menu = 2
			dones = 0
			cBGM = 0
			screenNum = [(":",(120,40))]
        # if time input is correct
		if mHour != '':

			clearScreen()
			menu = 4
			screenNum = [(":",(120,40))]
			numIn = 0
			cBGM = 0


	elif menu == 4:  # input how many pills
		timeIn = str(mHour)+':'+str(mMin)
		numIn,menu,dones2 = p4Pills(timeIn,pillNum,numIn,dones2)
        # for hit done twice to quit
		if dones2 == 2:
			menu = 2
			# clear the entire input
			setA[pillNum-1][0][tSelect-1] = ['','']
			setA[pillNum-1][1][tSelect-1] = ''
			dones2 = 0


		if menu != 4:
			cBGM = 0

		setA[pillNum-1][1][tSelect-1] = numIn  #stores how many pills to take
		
	elif menu == 5:  # view schedule
		menu = p5dispSch()
		if menu != 5:
			cBGM = 0
			
	elif menu == 7:  # display under dose alarm
		menu = p7alarm()
		if menu != 7:
			cBGM = 0



GPIO.cleanup()
