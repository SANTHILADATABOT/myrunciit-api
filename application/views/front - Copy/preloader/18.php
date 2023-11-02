

<style>

#loading-center{
	width: 100%;
	height: 100%;
	position: relative;
	}
#loading-center-absolute {
	position: absolute;
	left: 50%;
	top: 50%;
	height: 50px;
	/*width: 150px;
	margin-top: -25px;
	margin-left: -75px;*/
	height: 50px;
	width: 450px;
	margin-top: -50px;
	margin-left: -150px;

}
.object{
	/*width: 8px;
	height: 50px;*/
	width: auto;
	height: auto;
	margin-right:5px;
	background-color: transparent<?php /*?><?php echo $preloader_obj; ?><?php */?>;
	-webkit-animation: animate 1s infinite;
	animation: animate 1s infinite;
	float: left;
	color: #fff;
font-size: 50px;
	}

.object:last-child {
	margin-right: 0px;
	}

.object:nth-child(10){
	-webkit-animation-delay: 0.9s;
    animation-delay: 0.9s;	
	}
.object:nth-child(9){
	-webkit-animation-delay: 0.8s;
    animation-delay: 0.8s;	
	}	
.object:nth-child(8){
	-webkit-animation-delay: 0.7s;
    animation-delay: 0.7s;	
	}
.object:nth-child(7){
	-webkit-animation-delay: 0.6s;
    animation-delay: 0.6s;	
	}
.object:nth-child(6){
	-webkit-animation-delay: 0.5s;
    animation-delay: 0.5s;	
	}
.object:nth-child(5){
	-webkit-animation-delay: 0.4s;
    animation-delay: 0.4s;
	}
.object:nth-child(4){
	-webkit-animation-delay: 0.3s;
    animation-delay: 0.3s;		
	}
.object:nth-child(3){
	-webkit-animation-delay: 0.2s;
    animation-delay: 0.2s;	
	}
.object:nth-child(2){
	-webkit-animation-delay: 0.1s;
    animation-delay: 0.1s;
	}						
	


@-webkit-keyframes animate {
 
  50% {
	-ms-transform: translateX(-25px) scaleY(0.5); 
   	-webkit-transform: translateX(-25px) scaleY(0.5);
    transform: translateX(-25px) scaleY(0.5);
	
	  }
	  
	  

}

@keyframes animate {
  50% {
	-ms-transform: translateX(-25px) scaleY(0.5); 
   	-webkit-transform: translateX(-25px) scaleY(0.5);
    transform: translateX(-25px) scaleY(0.5);
	  }

  
}


#preloader{
    width: 100%;
    height: 100%;
    -webkit-animation: preloader_6 5s infinite linear;
    -moz-animation: preloader_6 5s infinite linear;
    -o-animation: preloader_6 5s infinite linear;
    animation: preloader_6 5s infinite linear;
    background: #000;
    display: block;
    text-align: center;
    position: fixed;
    z-index: 999999;
    opacity: 0.99;
    top: 0;
}

#loader-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1000;
}
.cssload-loader {
	width: 175px;
	height: 55px;
	line-height: 35px;
	text-align: center;
	position: absolute;
	left:0 ;
    right: 0;
    margin: 0 auto;
    top: 46%;
	font-family: 'Great Vibes', Arial, sans-serif;
	font-size:50px;
	color: #fff;
	letter-spacing: 0.2em;
}
.cssload-loader span{
    color:rgb(197,155,95);
}

.cssload-loader::before, .cssload-loader::after {
	content: "";
	display: block;
	width: 10px;
	height: 10px;
	background: rgb(197,155,95);
	position: absolute;
	animation: cssload-load 2.17s infinite alternate ease-in-out;
		-o-animation: cssload-load 2.17s infinite alternate ease-in-out;
		-ms-animation: cssload-load 2.17s infinite alternate ease-in-out;
		-webkit-animation: cssload-load 2.17s infinite alternate ease-in-out;
		-moz-animation: cssload-load 2.17s infinite alternate ease-in-out;
}
.cssload-loader::before {
	top: 0;
}
.cssload-loader::after {
	bottom: 0;
}

@keyframes cssload-load {
	0% {
		left: 0;
		height: 21px;
		width: 10px;
	}
	50% {
		height: 6px;
		width: 28px;
	}
	100% {
		left: 164px;
		height: 21px;
		width: 10px;
	}
}

@-o-keyframes cssload-load {
	0% {
		left: 0;
		height: 21px;
		width: 10px;
	}
	50% {
		height: 6px;
		width: 28px;
	}
	100% {
		left: 164px;
		height: 21px;
		width: 10px;
	}
}

@-ms-keyframes cssload-load {
	0% {
		left: 0;
		height: 21px;
		width: 10px;
	}
	50% {
		height: 6px;
		width: 28px;
	}
	100% {
		left: 164px;
		height: 21px;
		width: 10px;
	}
}

@-webkit-keyframes cssload-load {
	0% {
		left: 0;
		height: 21px;
		width: 10px;
	}
	50% {
		height: 6px;
		width: 28px;
	}
	100% {
		left: 164px;
		height: 21px;
		width: 10px;
	}
}

@-moz-keyframes cssload-load {
	0% {
		left: 0;
		height: 21px;
		width: 10px;
	}
	50% {
		height: 6px;
		width: 28px;
	}
	100% {
		left: 164px;
		height: 21px;
		width: 10px;
	}
}


</style>
<div id="loading">
    <div id="loading-center">
        <div id="loading-center-absolute">
            <div class="object">o</div>
            <div class="object">y</div>
            <div class="object">a</div>
            <div class="object">b</div>
            <div class="object">u</div>
            <div class="object">y</div>
            <div class="object">.</div>
            <div class="object">n</div>
            <div class="object">e</div>
            <div class="object">t</div>
        </div>
    </div>
    <!--<div id="preloader">
            <div id="loader-wrapper">
                <div class="cssload-loader">oyabuy</div>
            </div>
        </div>-->
</div>



