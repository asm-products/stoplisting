package com.listingproduct.stoplisting.global;

public final class GlobalDefine {
	public static final class CommonState{
		public static final boolean debug_mode = false;
	}
	
	public static final String[] MONTH_Title = {"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"};
	
	public static final class DropBoxInfo{
		public static final String APP_KEY = "daxb0hx4xfzqe6p";
		public static final String APP_SECRET = "8oy8vdg8ioz2q2k";
		
		public static final String ACCOUNT_PREFS_NAME = "prefs";
		public static final String ACCESS_KEY_NAME = "ACCESS_KEY";
		public static final String ACCESS_SECRET_NAME = "ACCESS_SECRET";	
	}
	
	public static final class SiteURLs{
		public static final String homeURL = "http://www.manurewa.school.nz/api/ios/ios_school_status.json";
		public static final String newsURL = "http://www.manurewa.school.nz/api/ios/ios_page_list.json/11b921ef080f7736089c757404650e40";
		public static final String eventsURL = "http://www.manurewa.school.nz/api/ios/ios_event_list.json/";
		public static final String alertsURL = "http://www.manurewa.school.nz/api/ios/ios_alert_list.json";
		
		// StopListing APIs
		public static final String plansURL = "http://stoplisting.com/api/?get_plan";
		
		public static final String myPlanURL = "http://stoplisting.com/api/?get_plan&user_id=%d";
		public static final String myPlanTestURL = "http://stoplisting.com/api/?get_plan&user_id=1";
		
		public static final String loginURL = "http://stoplisting.com/api/?login&email=%s&password=%s";
		public static final String loginTestURL = "http://stoplisting.com/api/?login&email=kaleem.khan287@gmail.com&password=kaleem";
		
		public static final String passwdReqURL = "http://stoplisting.com/api/?passwd&email=%s";
		public static final String passwdReqTestURL = "http://stoplisting.com/api/?passwd&email=kaleem.khan287@gmail.com";
		
		// User Register
		public static final String registerURL = "http://stoplisting.com/api/?create&name=%s&email=%s&password=%s";
		
		// Listing item
		//public static final String listingURL = "http://stoplisting.com/api/?new&image_path=<image_path>&user_id=<user_id>";
		public static final String listingURL = "http://stoplisting.com/api/?new&image_path=%s&user_id=%d";
		public static final String listingTestURL = "http://stoplisting.com/api/?new&image_path=http://s7.postimg.org/oyx39j2i3/2014_06_11_17_48_17.jpg-http://s7.postimg.org/ffneg2ezv/2014_06_11_17_48_20.jpg-http://s7.postimg.org/4vdgxhai3/2014_06_11_17_48_22.jpg&user_id=1";
		
		// Swank Search
		//public static final String swankSearchURL = "http://stoplisting.com/api/?swank%s%s%s%s%s";
		public static final String swankSearchURL = "http://stoplisting.com/api/swank.php?%s%s%s%s%s";
		 
		// LiveFinder
		public static final String liveFinderRequestURL = "http://stoplisting.com/api/?find&image_path=%s&user_id=%d";
		public static final String liveFinderUpdateCheckingURL = "http://stoplisting.com/api/?find&lfid=%d&action=get&user_id=%d";
		public static final String liveFinderItemRejectingURL = "http://stoplisting.com/api/?find&lfid=%d&action=reject&user_id=%d";
		public static final String liveFinderItemListingURL = "http://stoplisting.com/api/?find&lfid=%d&action=list&user_id=%d";	
	
		// User Action
		public static final String getAction = "http://stoplisting.com/api/?getact&";
		public static final String setAction = "http://stoplisting.com/api/?setact&";
		public static final String setMultiAction = "http://stoplisting.com/api/?multisetact&";
		public static final String getSesstionIdForAuthToken = "http://stoplisting.com/api/?get_sessionid";
		public static final String setToken = "http://stoplisting.com/api/?set_token&";
		
		public static final String setItem = "http://stoplisting.com/api/?setitem&";
		public static final String getItem = "http://stoplisting.com/api/?getitem&";
		
		// About gumroad
		public static final String gumroadAppId = "dbf3060c9e741f4ce0272603dd09b8bfbb542da283dc990cb1c40f7bfbb3a50d";
		public static final String gumroadAppSecret = "0096a88590a67fc5a07a5c475237af9d3b87c41d9db765fe0b3f1a2af65389fc";
		public static final String gumroadAccessToken = "d2889534eeea6215d8a2bb7ae318fb388264381b8c8ceca4f417726d27e9c52b";
	}
}
