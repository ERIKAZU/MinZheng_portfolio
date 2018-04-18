close all;
clc;

I = imread('curve3.jpg');
figure;
imshow(I);hold on
[x_left,y_left] = ginput();
plot(x_left,y_left,'r');
figure;
imshow(I);hold on
[x_right,y_right] = ginput();
plot(x_right,y_right,'r');