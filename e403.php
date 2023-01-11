

<!DOCTYPE html>
<html lang="en" >

<head>

  <meta charset="UTF-8">
<title>MotoDesh: 403 Forbidden</title>
  
  
  
  
<style>
@import url("https://fonts.googleapis.com/css?family=Bungee");

body {
  background: #0094d9;
  color: white;
  font-family: "Bungee", cursive;
  margin-top: 50px;
  text-align: center;
}
a {
  color: #0f1b1f;
  text-decoration: none;
}
a:hover {
  color: white;
}
svg {
  width: 50vw;
}
.lightblue {
  fill: #444;
}
.eye {
  cx: calc(115px + 30px * var(--mouse-x));
  cy: calc(50px + 30px * var(--mouse-y));
}
#eye-wrap {
  overflow: hidden;
}
.error-text {
  font-size: 120px;
}
.alarm {
  animation: alarmOn 0.5s infinite;
}

@keyframes alarmOn {
  to {
    fill: darkred;
  }
}
</style>

 
  
  


</head>

<body translate="no" >
  <svg xmlns="http://www.w3.org/2000/svg" id="robot-error" viewBox="0 0 260 118.9">
            <defs>
                <clipPath id="white-clip"><circle id="white-eye" fill="#cacaca" cx="130" cy="65" r="20" /> </clipPath>
             <text id="text-s" class="error-text" y="106"> 403 </text>
            </defs>
              <path class="alarm" fill="#e62326" d="M120.9 19.6V9.1c0-5 4.1-9.1 9.1-9.1h0c5 0 9.1 4.1 9.1 9.1v10.6" />
             <use xlink:href="#text-s" x="-0.5px" y="-1px" fill="black"></use>
             <use xlink:href="#text-s" fill="#2b2b2b"></use>
            <g id="robot">
              <g id="eye-wrap">
                <use xlink:href="#white-eye"></use>
                <circle id="eyef" class="eye" clip-path="url(#white-clip)" fill="#000" stroke="#2aa7cc" stroke-width="2" stroke-miterlimit="10" cx="130" cy="65" r="11" />
<ellipse id="white-eye" fill="#2b2b2b" cx="130" cy="40" rx="18" ry="12" />
              </g>
              <circle class="lightblue" cx="105" cy="32" r="2.5" id="tornillo" />
              <use xlink:href="#tornillo" x="50"></use>
              <use xlink:href="#tornillo" x="50" y="60"></use>
              <use xlink:href="#tornillo" y="60"></use>
            </g>
          </svg>
<h1>You are not allowed to enter here</h1>
<h2>Please Login <a href="/">Click Here!</a></h2>
 <div id="CountDown" >  
                You will be redirected after  
                <br />  
                <span id="CountDownLabel"></span> seconds.  
            </div>   
  <script type="text/javascript">  
        function RedirectAfterDelayFn() {  
            var seconds = 5;  
            var dvCountDown = document.getElementById("CountDown");  
            var lblCount = document.getElementById("CountDownLabel");  
            dvCountDown.style.display = "block";  
            lblCount.innerHTML = seconds;  
            setInterval(function () {  
                seconds--;  
                lblCount.innerHTML = seconds;  
                if (seconds == 0) {  
                    dvCountDown.style.display = "none";  
                    window.location = "/";  
                }  
            }, 1000);  
        } 
        RedirectAfterDelayFn(); 
    </script>  
      <script id="rendered-js" >
var root = document.documentElement;
var eyef = document.getElementById('eyef');
var cx = document.getElementById("eyef").getAttribute("cx");
var cy = document.getElementById("eyef").getAttribute("cy");

document.addEventListener("mousemove", evt => {
  let x = evt.clientX / innerWidth;
  let y = evt.clientY / innerHeight;

  root.style.setProperty("--mouse-x", x);
  root.style.setProperty("--mouse-y", y);

  cx = 115 + 30 * x;
  cy = 50 + 30 * y;
  eyef.setAttribute("cx", cx);
  eyef.setAttribute("cy", cy);

});

document.addEventListener("touchmove", touchHandler => {
  let x = touchHandler.touches[0].clientX / innerWidth;
  let y = touchHandler.touches[0].clientY / innerHeight;

  root.style.setProperty("--mouse-x", x);
  root.style.setProperty("--mouse-y", y);
});
//# sourceURL=pen.js
    </script>

  

</body>

</html>
 
