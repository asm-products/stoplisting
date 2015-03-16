package com.listingproduct.stoplisting;

import java.util.ArrayList;

import com.dropbox.client2.DropboxAPI;
import com.dropbox.client2.android.AndroidAuthSession;
import com.listingproduct.stoplisting.data.Plan;
import com.listingproduct.stoplisting.data.StopListingItem;
import com.nostra13.universalimageloader.core.ImageLoader;
import com.nostra13.universalimageloader.core.ImageLoaderConfiguration;

import android.app.Activity;
import android.graphics.Typeface;

public class Application extends android.app.Application {

	// Debugging switch
	public static final boolean APPDEBUG = false;

	// Debugging tag for the application
	public static final String APPTAG = "StopListing";

	// for fontawesome icon
	private Typeface font;

	// for Cashing Bitmap
	protected ImageLoader imageLoader;

	// User name
	private String mUserName = "";

	// Email
	private String mEmail = "";

	// Email
	private String mPasswd = "";

	// User id
	private int mUserid = -1;

	// User id
	private boolean mAuthToken = false;

	// User Plan
	private int mUserPlan = -1;

	// Plan List data
	private ArrayList<Plan> mPlanLists;

	// Current Activity
	private Activity mCurrentActivity = null;

	private DropboxAPI<AndroidAuthSession> mCurrentDropboxApi;
	private ListingItemManager listingItemMan;

	public int mListingsRemaining = 0;
	public int mQueue = 0;

	public int mWeekTotal = 0;
	public int mNeedMore = 0;
	public int mReadyPublish = 0;
	public String mImagePath = "";

	public ArrayList<StopListingItem> mPublishedItems = new ArrayList<StopListingItem>();
	public ArrayList<StopListingItem> mUnPublishedItems = new ArrayList<StopListingItem>();
	public ArrayList<StopListingItem> mRejectedItems = new ArrayList<StopListingItem>();
	public ArrayList<StopListingItem> mRecentItems = new ArrayList<StopListingItem>();
	
	String Tag = "Application";

	@Override
	public void onCreate() {
		super.onCreate();
		// load Font
		font = Typeface.createFromAsset(getAssets(), "fontawesome-webfont.ttf");
		imageLoader = ImageLoader.getInstance();
		imageLoader.init(ImageLoaderConfiguration
				.createDefault(getBaseContext()));

		listingItemMan = new ListingItemManager(this);
	}

	@Override
	public void onTerminate() {
		imageLoader.clearDiskCache();
		imageLoader.clearMemoryCache();
		listingItemMan.stop();
		super.onTerminate();
	}

	// return fontawesome icon
	public Typeface getFont() {
		return font;
	}

	// return ImageLoader object
	public ImageLoader getImageLoader() {
		return imageLoader;
	}

	// set and get current User Name
	public void setUserName(String userName) {
		mUserName = userName;
	}

	public String getUserName() {
		return mUserName;
	}

	// set and get Email
	public void setEmail(String email) {
		mEmail = email;
	}

	public String getEmail() {
		return mEmail;
	}

	// set and get Passwd
	public void setPw(String pw) {
		mPasswd = pw;
	}

	public String getPw() {
		return mPasswd;
	}

	// set and get current User Id
	public void setUserId(int userId) {
		mUserid = userId;
		listingItemMan.setUserId(userId);
	}

	public int getUserId() {
		return mUserid;
	}

	// set and get current User Id
	public void setTokenStatus(boolean authToken) {
		mAuthToken = authToken;
	}

	public boolean getTokenStatus() {
		return mAuthToken;
	}

	// set and get current User Plan
	public void setUserPlan(int userPlan) {
		mUserPlan = userPlan;
	}

	public int getUserPlan() {
		return mUserPlan;
	}

	// set and get Plan List
	public void setPlanList(ArrayList<Plan> plans) {
		if (plans == null || plans.size() == 0)
			return;

		mPlanLists = plans;

		// Sort Plan List
		int nPlanCnt = mPlanLists.size();
		for (int i = 0; i < nPlanCnt - 1; i++) {
			for (int j = i + 1; j < nPlanCnt; j++) {
				if (mPlanLists.get(i).getPlanPrice() > mPlanLists.get(j)
						.getPlanPrice()) {
					Plan tempPlan = mPlanLists.get(i);
					mPlanLists.set(i, mPlanLists.get(j));
					mPlanLists.set(j, tempPlan);
				}
			}
		}
	}

	public ArrayList<Plan> getPlanList() {
		return mPlanLists;
	}

	// set and get Active Activity

	public Activity getCurrentActivity() {
		return mCurrentActivity;
	}

	public void setCurrentActivity(Activity mCurrentActivity) {
		this.mCurrentActivity = mCurrentActivity;
	}

	// get current user Plan Name
	public String getUserPlanName() {
		if (mPlanLists == null || mPlanLists.size() == 0)
			return "";
		if (mUserPlan < 1) // start at 1
			return "";
		if (mPlanLists.size() < mUserPlan) // end at mPlanLists.size()
			return "";
		return mPlanLists.get(mUserPlan - 1).getPlanTitle();
	}

	// set and get Dropbox Api
	public void setDropboxApi(DropboxAPI<AndroidAuthSession> mApi) {
		mCurrentDropboxApi = mApi;
		listingItemMan.setDropboxApi(mApi);
	}

	public DropboxAPI<AndroidAuthSession> getDropboxApi() {
		return mCurrentDropboxApi;
	}

	// Add item listing task
	public void listNewitem(ArrayList<String> photoList) {
		listingItemMan.listItem(photoList);
	}
}