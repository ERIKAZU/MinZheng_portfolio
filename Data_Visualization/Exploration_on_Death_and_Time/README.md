# How Much Time You Have Left and How You Spend It
Through this project, we would like to expose the viewers to the topic of death, and make them realize how precious the time is in order to cherish their lives. We started by showing the top 5 death causes of U.S. residents at different age groups. Then, we designed an individualized experience to let viewers input their demographic data to see how much time they have spent in their whole life and how much time is left for them to spend in the future. By interacting with the time dots, users can also obtain information of their daily time usage in different life stages, and get a general sense of how they would be spending the rest of their lives.
<p align="center">
  <b>Team:</b><br>
  <a>Min Zheng</a> |
  <a>Kexin Jiang</a> |
  <a>Yicheng Zhu</a>
</p>

 ##  Design and Story
We use black color as the background to show how serious the death is. And we use the bright color to present data to show how precious the time we can live.
There are two layers of session one. The outer circle showed the top 5 death causes. The ratio of perimeter showed the percentage of each death causes. The rest of death causes will be counted into others to keep the color theme from being messy. The inner circle represents death, while a number is showing the specific age. The size of the small circles flying in is also proportional to the percentage of each death cause, and the color is consistent. The animation that outliner is blending into the inner center is for presenting the process of death. For all of the circles data, we used scaleSqrt() to avoid the confusions.
For session two, after two inputs - age and gender, the circle matrix showed your total life length. Each circle represents each year, in which grey presents the life you have already lived and the green shows the rest of life you could possibly live. The animation is inspired by hourglass to show the eclipse of time.
  
After clicking on each age group, a bubble chart will show the time spending breakdown, while different colors representing different activities and the radius is mapped by scaleSqrt() to the percentage of activity spending time.
[Sketch and Wirefram](/img/sketch.jpeg)

 ##  Visualization Layout


 ![](/img/layout1.png)

 ![](/img/layout2.png)

 ![](/img/layout3.png)
 


 ##  Project Report
 More details about the project can be found at:
 [Report](/report.pdf)
