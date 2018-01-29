I = imread('1.png');
figure;
imshow(I);hold on
[x_left,y_left] = ginput(4)
plot(x_left,y_left,'r');
figure;
imshow(I);hold on
[x_right,y_right] = ginput(4)
plot(x_right,y_right,'r');