function __zShowClock() {
	var now = new Date();
	/*
	2 a.m. on the Second Sunday in March 
	to 2 a.m. on the First Sunday of November, 
	GMT - 4 (Other time, GMT - 5)
	*/
	var secSundayInMar = new Date();
	var frtSundayInNov = new Date();
	secSundayInMar.setUTCMonth(2);
	secSundayInMar.setUTCDate(1);
	secSundayInMar.setUTCHours(2);
	secSundayInMar.setUTCMinutes(0);
	secSundayInMar.setUTCSeconds(0);
	secSundayInMar.setUTCMilliseconds(0);
	var i = 0;
	while (secSundayInMar.getUTCDay() != 0) {
		i++;
		secSundayInMar.setUTCDate(i);
	}
	secSundayInMar.setUTCDate(i + 7);
	frtSundayInNov.setUTCMonth(10);
	frtSundayInNov.setUTCDate(1);
	frtSundayInNov.setUTCHours(2);
	frtSundayInNov.setUTCMinutes(0);
	frtSundayInNov.setUTCSeconds(0);
	frtSundayInNov.setUTCMilliseconds(0)
	i = 0;
	while (frtSundayInNov.getUTCDay() != 0) {
		i++;
		frtSundayInNov.setUTCDate(i);
	}

	if (now >= secSundayInMar && now <= frtSundayInNov) {
		now.setHours(now.getHours() - 4);
	} else {
		now.setHours(now.getHours() - 5);
    };
	
	var nowStr = now.toUTCString();
	nowStr = nowStr.replace("GMT", "EDT"); //for firefox browser
	nowStr = nowStr.replace("UTC", "EDT"); //for IE browser

	//nowStr += ("(" + secSundayInMar.toUTCString() + "_" + frtSundayInNov.toUTCString() + ")");
	
	jQuery("#lblClock").html(nowStr);
	setTimeout("__zShowClock()", 1000);
}