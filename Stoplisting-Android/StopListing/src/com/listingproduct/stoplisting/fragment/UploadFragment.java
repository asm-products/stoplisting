package com.listingproduct.stoplisting.fragment;

import java.util.ArrayList;

import com.dropbox.client2.DropboxAPI;
import com.dropbox.client2.android.AndroidAuthSession;
import com.dropbox.client2.android.AuthActivity;
import com.dropbox.client2.session.AccessTokenPair;
import com.dropbox.client2.session.AppKeyPair;
import com.listingproduct.stoplisting.Application;
import com.listingproduct.stoplisting.ChooseFileActivity;
import com.listingproduct.stoplisting.R;
import com.listingproduct.stoplisting.TakePhotoActivity;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.graphics.Typeface;
import android.net.Uri;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.View.OnClickListener;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import com.listingproduct.stoplisting.data.StopListingItem;
import com.listingproduct.stoplisting.global.GlobalDefine;
import com.nostra13.universalimageloader.core.DisplayImageOptions;
import com.nostra13.universalimageloader.core.ImageLoader;
import com.nostra13.universalimageloader.core.assist.FailReason;
import com.nostra13.universalimageloader.core.listener.ImageLoadingListener;

public class UploadFragment extends Fragment implements OnClickListener {

	// About Dropbox Link
	// Key and Secret assigned by Dropbox.
	final static private String APP_KEY = GlobalDefine.DropBoxInfo.APP_KEY;
	final static private String APP_SECRET = GlobalDefine.DropBoxInfo.APP_SECRET;

	// You don't need to change these, leave them alone.
	final static private String ACCOUNT_PREFS_NAME = GlobalDefine.DropBoxInfo.ACCOUNT_PREFS_NAME;
	final static private String ACCESS_KEY_NAME = GlobalDefine.DropBoxInfo.ACCESS_KEY_NAME;
	final static private String ACCESS_SECRET_NAME = GlobalDefine.DropBoxInfo.ACCESS_SECRET_NAME;

	private static final boolean USE_OAUTH1 = false;
	DropboxAPI<AndroidAuthSession> mApi;

	private boolean mLoggedIn;

	// Android widgets
	private Button mSubmit;
	private LinearLayout mDisplay;
	private ProgressBar mLoadingProgBar;

	ListView uploadListView;
	ArrayList<StopListingItem> uploadItems;

	private Activity mActivity;
	private Typeface font;
	private Application mApp;

	ImageLoader imgLoader;
	DisplayImageOptions optionsPhoto;

	String TAG = "stUploadFragment";

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container,
			Bundle savedInstanceState) {

		// get Activity object
		mActivity = getActivity();
		mApp = (Application) mActivity.getApplication();

		// For Image Loader
		imgLoader = mApp.getImageLoader();
		optionsPhoto = new DisplayImageOptions.Builder()
				.showImageOnLoading(R.drawable.white_back)
				.showImageForEmptyUri(R.drawable.no_media)
				.showImageOnFail(R.drawable.no_media).cacheInMemory(true)
				.cacheOnDisk(true).considerExifParams(true)
				.bitmapConfig(Bitmap.Config.RGB_565).build();

		// We create a new AuthSession so that we can use the Dropbox API.
		AndroidAuthSession session = buildSession();
		mApi = new DropboxAPI<AndroidAuthSession>(session);

		View view = inflater.inflate(R.layout.upload_screen, container, false);

		checkAppKeySetup();

		// load Font
		font = mApp.getFont();
		// Link Dropbox button
		mSubmit = (Button) view.findViewById(R.id.authBtn);
		mSubmit.setTypeface(font);
		mSubmit.setOnClickListener(new View.OnClickListener() {
			public void onClick(View v) {
				// This logs you out if you're logged in, or vice versa
				if (mLoggedIn) {
					logOut();
				} else {
					// Start the remote authentication
					if (USE_OAUTH1) {
						mApi.getSession().startAuthentication(mActivity);
					} else {
						mApi.getSession().startOAuth2Authentication(mActivity);
					}
				}
			}
		});

		// Logged Layout
		mDisplay = (LinearLayout) view.findViewById(R.id.logged_in_display);

		View takePhotoFL = view.findViewById(R.id.takePhoto);
		View chooseFromLibFL = view.findViewById(R.id.chooseFromLib);
		takePhotoFL.setOnClickListener(this);
		chooseFromLibFL.setOnClickListener(this);

		// Loading progressbar
		mLoadingProgBar = (ProgressBar) view.findViewById(R.id.loadingProgBar);
		mLoadingProgBar.setVisibility(View.GONE);
		
		// recently uploaded item list
		uploadListView = (ListView) view.findViewById(R.id.uploadPhotoList);

		// Display the proper UI state if logged in or not
		setLoggedIn(mApi.getSession().isLinked());
		mApp.setDropboxApi(mApi);

		Log.e(TAG, "OnCreate()********************************");
		return view;
	}

	@Override
	public void onViewCreated(View view, Bundle savedInstanceState) {
		super.onViewCreated(view, savedInstanceState);
		loadRecentlyUploadItems();
		RecentUploadItemAdapter mAdapter = new RecentUploadItemAdapter(
				getActivity(), uploadItems);
		uploadListView.setAdapter(mAdapter);
	}

	@Override
	public void onResume() {
		super.onResume();
		AndroidAuthSession session = mApi.getSession();

		// The next part must be inserted in the onResume() method of the
		// activity from which session.startAuthentication() was called, so
		// that Dropbox authentication completes properly.
		if (session.authenticationSuccessful()) {
			try {
				// Mandatory call to complete the auth
				session.finishAuthentication();

				// Store it locally in our app for later use
				storeAuth(session);
				setLoggedIn(true);
			} catch (IllegalStateException e) {
				showToast("Couldn't authenticate with Dropbox:"
						+ e.getLocalizedMessage());
				Log.i(TAG, "Error authenticating", e);
			}
		}
	}

	private void loadRecentlyUploadItems() {
		uploadItems = mApp.mRecentItems;
	}

	public class RecentUploadItemAdapter extends BaseAdapter implements
			OnClickListener {

		Context mContext;
		ArrayList<StopListingItem> planList;

		public RecentUploadItemAdapter(Context context,
				ArrayList<StopListingItem> list) {

			mContext = context;
			planList = list;
		}

		@Override
		public int getCount() {

			return planList.size();
		}

		@Override
		public long getItemId(int position) {
			return position;
		}

		@Override
		public StopListingItem getItem(int position) {
			return planList.get(position);
		}

		@Override
		public View getView(int position, View convertView, ViewGroup parent) {

			View view = convertView;
			if (convertView == null) {
				view = LayoutInflater.from(mContext).inflate(
						R.layout.upload_listitem, null);
			}

			TextView itemDateTv = (TextView) view.findViewById(R.id.itemDateTv);
			ImageView itemPhotoIv = (ImageView) view
					.findViewById(R.id.itemPhotoIv);
			final ProgressBar progBar = (ProgressBar) view.findViewById(R.id.progBar);

			// set data
			StopListingItem curItem = getItem(position);

			itemDateTv.setText(curItem.getDate());

			String strPhotoFile = curItem.getPhotoUrl();
			if (!strPhotoFile.isEmpty()) {
				int firstPhotoEndPos = strPhotoFile.indexOf("-");
				if (firstPhotoEndPos != -1)
					strPhotoFile = strPhotoFile.substring(0, firstPhotoEndPos);
				 
				String strPhotoUrl = mApp.mImagePath + strPhotoFile;
				imgLoader.displayImage(strPhotoUrl, itemPhotoIv, optionsPhoto,
						new ImageLoadingListener() {

							@Override
							public void onLoadingStarted(String imageUri,
									View view) {
								progBar.setVisibility(View.VISIBLE);
							}

							@Override
							public void onLoadingFailed(String imageUri,
									View view, FailReason failReason) {
								progBar.setVisibility(View.INVISIBLE);
							}

							@Override
							public void onLoadingComplete(String imageUri,
									View view, Bitmap loadedImage) {
								progBar.setVisibility(View.INVISIBLE);
							}

							@Override
							public void onLoadingCancelled(String imageUri,
									View view) {
								progBar.setVisibility(View.INVISIBLE);
							}
						});
			}

			return view;
		}

		@Override
		public void onClick(View v) {
			String strTag = v.getTag().toString();
			int viewIndex = Integer.parseInt(strTag);
		}
	}

	@Override
	public void onClick(View v) {

		switch (v.getId()) {
		case R.id.takePhoto:

			if (!isDeviceSupportCamera()) {
				showToast("No camera on device. Cannot take photo.");
				return;
			}

			Intent takePhotoIntent = new Intent(mActivity,
					TakePhotoActivity.class);
			startActivity(takePhotoIntent);
			break;
		case R.id.chooseFromLib:
			Intent chooseFromFileIntent = new Intent(mActivity,
					ChooseFileActivity.class);
			startActivity(chooseFromFileIntent);
			break;
		}
	}

	// Check if Camera exist or not
	private boolean isDeviceSupportCamera() {
		if (mApp.getPackageManager().hasSystemFeature(
				PackageManager.FEATURE_CAMERA)) {
			// this device has a camera
			return true;
		} else {
			// no camera on this device
			return false;
		}
	}

	// About Dropbox
	private AndroidAuthSession buildSession() {
		AppKeyPair appKeyPair = new AppKeyPair(APP_KEY, APP_SECRET);

		AndroidAuthSession session = new AndroidAuthSession(appKeyPair);
		loadAuth(session);
		return session;
	}

	/**
	 * Shows keeping the access keys returned from Trusted Authenticator in a
	 * local store, rather than storing user name & password, and
	 * re-authenticating each time (which is not to be done, ever).
	 */
	private void loadAuth(AndroidAuthSession session) {
		SharedPreferences prefs = getActivity().getSharedPreferences(
				ACCOUNT_PREFS_NAME, 0);
		String key = prefs.getString(ACCESS_KEY_NAME, null);
		String secret = prefs.getString(ACCESS_SECRET_NAME, null);
		if (key == null || secret == null || key.length() == 0
				|| secret.length() == 0)
			return;

		if (key.equals("oauth2:")) {
			// If the key is set to "oauth2:", then we can assume the token is
			// for OAuth 2.
			session.setOAuth2AccessToken(secret);
		} else {
			// Still support using old OAuth 1 tokens.
			session.setAccessTokenPair(new AccessTokenPair(key, secret));
		}
	}

	private void checkAppKeySetup() {
		// Check to make sure that we have a valid app key
		if (APP_KEY.startsWith("CHANGE") || APP_SECRET.startsWith("CHANGE")) {
			showToast("You must apply for an app key and secret from developers.dropbox.com, and add them to the app before trying it.");
			mActivity.finish();
			return;
		}

		// Check if the app has set up its manifest properly.
		Intent testIntent = new Intent(Intent.ACTION_VIEW);
		String scheme = "db-" + APP_KEY;
		String uri = scheme + "://" + AuthActivity.AUTH_VERSION + "/test";
		testIntent.setData(Uri.parse(uri));
		PackageManager pm = mActivity.getPackageManager();
		if (0 == pm.queryIntentActivities(testIntent, 0).size()) {
			showToast("URL scheme in your app's "
					+ "manifest is not set up correctly. You should have a "
					+ "com.dropbox.client2.android.AuthActivity with the "
					+ "scheme: " + scheme);
			mActivity.finish();
		}
	}

	private void logOut() {
		// Remove credentials from the session
		mApi.getSession().unlink();

		// Clear our stored keys
		clearKeys();
		// Change UI state to display logged out version
		setLoggedIn(false);
	}

	private void clearKeys() {
		SharedPreferences prefs = mActivity.getSharedPreferences(
				ACCOUNT_PREFS_NAME, 0);
		Editor edit = prefs.edit();
		edit.clear();
		edit.commit();
	}

	/**
	 * Convenience function to change UI state based on being logged in
	 */
	private void setLoggedIn(boolean loggedIn) {
		mLoggedIn = loggedIn;
		if (loggedIn) {
			mSubmit.setText(getString(R.string.title_unlink_dropbox));
			mDisplay.setVisibility(View.VISIBLE);
		} else {
			mSubmit.setText(getString(R.string.title_link_dropbox));
			mDisplay.setVisibility(View.GONE);
		}
	}

	/**
	 * Shows keeping the access keys returned from Trusted Authenticator in a
	 * local store, rather than storing user name & password, and
	 * re-authenticating each time (which is not to be done, ever).
	 */
	private void storeAuth(AndroidAuthSession session) {
		// Store the OAuth 2 access token, if there is one.
		String oauth2AccessToken = session.getOAuth2AccessToken();
		if (oauth2AccessToken != null) {
			SharedPreferences prefs = mActivity.getSharedPreferences(
					ACCOUNT_PREFS_NAME, 0);
			Editor edit = prefs.edit();
			edit.putString(ACCESS_KEY_NAME, "oauth2:");
			edit.putString(ACCESS_SECRET_NAME, oauth2AccessToken);
			edit.commit();
			return;
		}
		// Store the OAuth 1 access token, if there is one. This is only
		// necessary if
		// you're still using OAuth 1.
		AccessTokenPair oauth1AccessToken = session.getAccessTokenPair();
		if (oauth1AccessToken != null) {
			SharedPreferences prefs = mActivity.getSharedPreferences(
					ACCOUNT_PREFS_NAME, 0);
			Editor edit = prefs.edit();
			edit.putString(ACCESS_KEY_NAME, oauth1AccessToken.key);
			edit.putString(ACCESS_SECRET_NAME, oauth1AccessToken.secret);
			edit.commit();
			return;
		}
	}

	private void showToast(String msg) {
		Toast error = Toast.makeText(mActivity, msg, Toast.LENGTH_LONG);
		error.show();
	}
}
