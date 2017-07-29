function animationLoad(url)
{
	var isAnimationLoad = 0;

	if( isAnimationLoad == 1 ) //连续平移效果
	{
		rexseeTransition.clearStyle(); 
		rexseeTransition.clearPostStyle(); 
		rexseeTransition.setStyle('animation-repeat:repeat;background-color:#000000;animation-type:translate;animation-translate-x-from:0;animation-translate-x-to:-100;animation-translate-y-from:0;animation-translate-y-to:0;animation-translate-repeat-count:0;animation-translate-duration:1000;'); 
		rexseeTransition.setPostStyle('animation-post-start:start;animation-repeat:repeat;background-color:#000000;animation-type:translate;animation-translate-x-from:100;animation-translate-x-to:0;animation-translate-y-from:0;animation-translate-y-to:0;animation-translate-repeat-count:0;animation-translate-duration:1000;'); 
		rexseeTransition.load(url,'');
	}
	else if ( isAnimationLoad == 2 )  //Y轴3D翻页
	{

		rexseeTransition.clearPostStyle(); 
		rexseeTransition.setStyle('animation-type:rotate_3dy;background-color:#000000;animation-start-sound:none;animation-end-sound:none;animation-rotate-3dy-start-time:0;animation-rotate-3dy-repeat-count:0;animation-rotate-3dy-repeat-mode:reverse;animation-rotate-3dy-duration:1000;animation-rotate-3dy-from:0;animation-rotate-3dy-to:90;animation-rotate-3dy-depth-z:0;animation-rotate-3dy-reverse:false;animation-rotate-3dy-center-x:50;animation-rotate-3dy-center-y:50;'); 
		rexseeTransition.setPostStyle('animation-type:rotate_3dy;background-color:#000000;animation-start-sound:none;animation-end-sound:none;animation-rotate-3dy-start-time:0;animation-rotate-3dy-repeat-count:0;animation-rotate-3dy-repeat-mode:reverse;animation-rotate-3dy-duration:1000;animation-rotate-3dy-from:-90;animation-rotate-3dy-to:0;animation-rotate-3dy-depth-z:0;animation-rotate-3dy-reverse:false;animation-rotate-3dy-center-x:50;animation-rotate-3dy-center-y:50;'); 
		rexseeTransition.load(url,'');
	}
	else if ( isAnimationLoad == 3 )  //X轴3D翻页
	{

		rexseeTransition.clearPostStyle(); 
		rexseeTransition.setStyle('animation-type:scale+rotate_3dx;background-color:transparent;animation-start-sound:none;animation-end-sound:none;animation-scale:1 1 0.5 0.5;animation-scale-duration:1000;animation-scale-start-time:0;animation-rotate-3dx-start-time:1000;animation-rotate-3dx-duration:1000;animation-rotate-3dx-from:0;animation-rotate-3dx-to:90;animation-rotate-3dx-depth-z:0;animation-rotate-3dx-reverse:false;animation-rotate-3dx-center-x:50;animation-rotate-3dx-center-y:50;'); 
		rexseeTransition.setPostStyle('animation-type:scale+rotate_3dx;background-color:transparent;animation-start-sound:none;animation-end-sound:none;animation-scale:0.5 0.5 1 1;animation-scale-duration:1000;animation-scale-start-time:0;animation-rotate-3dx-start-time:1000;animation-rotate-3dx-start-time:0;animation-rotate-3dx-duration:1000;animation-rotate-3dx-from:-90;animation-rotate-3dx-to:0;animation-rotate-3dx-depth-z:0;animation-rotate-3dx-reverse:false;animation-rotate-3dx-center-x:50;animation-rotate-3dx-center-y:50;');
		rexseeTransition.load(url,'');
	}
	else
	{
		rexseeBrowser.go(url);
	}
}