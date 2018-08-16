var canvas = document.querySelector("canvas");
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

//Context
var c = canvas.getContext('2d');

//Rectangle
// c.fillStyle = "rgba(255, 0, 0, .4)";
// c.fillRect(0, 0, '210', '210');

//Linr
// c.beginPath();
// c.moveTo(50, 300);
// c.lineTo(300, 100);
// c.lineTo(400, 200);
// c.strokeStyle = "orchid";
// c.stroke();

// for(n=0; n<150; n++){
//     var color = "rgba("+(Math.random()*255)+", "+(Math.random()*255)+", "+ (Math.random()*255) +", "+ Math.random() +")";;
//     console.log(color);
//     // c.strokeStyle = "rgba(255, 0, 0, .5)";
//     c.strokeStyle = color;
//     x = Math.random() * window.innerWidth;
//     y = Math.random() * window.innerHeight;
//     radius = Math.random() * 50;
//     c.beginPath();
//     c.arc(x, y, radius, 0, Math.PI * 2  , false);
//     c.stroke();
//     c.fillStyle = "rgba(0, 255, 220, .6)";
//     c.fill();
// ;}

x=120;
y=120;
radius = 50;
speed = 12;

function animate(){
    requestAnimationFrame(animate);
    c.clearRect(0, 0, innerWidth, innerHeight);

    c.beginPath();
    c.arc(x, y, radius, 0, Math.PI * 2  , false);
    c.strokeStyle = "rgba(255, 0, 0, .5)";
    c.stroke();

    if(x+radius > innerWidth || x-radius<0){
        //go back
        speed=-speed;
    }else{
        
    }
    x+=speed;

    
}
animate();