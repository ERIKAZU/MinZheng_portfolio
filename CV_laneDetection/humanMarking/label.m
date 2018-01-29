for i = 1:4
    
    close all

%     imgTitle = ['curve',num2str(i)];
%     imgTitle = ['normal',num2str(i)];
    imgTitle = num2str(i);
    imgName = [imgTitle '.jpg'];
    I = imread(imgName);
    
    % show image and mark
    figure;
    imshow(I);hold on
    [x_left,y_left] = ginput()
    
    res = figure;
    imshow(I);hold on
    [x_right,y_right] = ginput();
    plot(x_left,y_left,'go','MarkerFaceColor','b');
    plot(x_right,y_right,'go','MarkerFaceColor','b');
    
     % save result
    left_data = horzcat(x_left,y_left);
    right_data = horzcat(x_right,y_right);
    l_str = ['B',imgTitle, '_l.csv'];
    r_str = ['B',imgTitle, '_r.csv'];
    
    csvwrite(l_str,left_data)
    csvwrite(r_str,right_data)
    pause(0.1);
    

   
    
end